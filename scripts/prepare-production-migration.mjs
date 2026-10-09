import { createHash } from "node:crypto";
import { cpSync, existsSync, lstatSync, mkdirSync, readFileSync, readdirSync, realpathSync, writeFileSync } from "node:fs";
import { dirname, isAbsolute, join, relative, resolve, sep } from "node:path";

const [backupArg, manifestArg, distArg, outputArg] = process.argv.slice(2);
if (![backupArg, manifestArg, distArg, outputArg].every((p) => p && isAbsolute(p))) {
  throw new Error("Four absolute paths are required: backup, manifest, dist, output");
}
const [backup, manifestPath, dist, output] = [backupArg, manifestArg, distArg, outputArg].map((p) => resolve(p));
const inside = (a, b) => b === a || b.startsWith(a + sep);
if (existsSync(output) || inside(backup, output) || inside(dist, output) || inside(output, backup) || inside(output, dist)) {
  throw new Error("Output must be new and separate from inputs");
}
for (const p of [backup, dist, dirname(output)]) {
  if (!lstatSync(p).isDirectory() || lstatSync(p).isSymbolicLink() || realpathSync(p) !== p) throw new Error("Unsafe input directory");
}
const container = lstatSync(dirname(output));
if ((container.mode & 0o077) !== 0 || container.uid !== process.getuid()) throw new Error("Output container must be private and owned by this user");
if (!lstatSync(manifestPath).isFile() || realpathSync(manifestPath) !== manifestPath) throw new Error("Unsafe manifest path");
const manifest = JSON.parse(readFileSync(manifestPath, "utf8"));
if (!manifest.complete || !manifest.remote_consistent || manifest.failure || manifest.skip) {
  throw new Error("A complete, consistent backup is required");
}
const inventory = manifest.listing_after;
if (!inventory || typeof inventory !== "object" || Array.isArray(inventory) ||
    Object.entries(inventory).some(([p, v]) => !p || p.startsWith("/") || p.includes("\\") || p.split("/").some((part) => !part || part === "." || part === "..") || !["file", "dir"].includes(v?.type))) {
  throw new Error("Invalid server inventory");
}
const hash = (bytes) => createHash("sha256").update(bytes).digest("hex");
const files = [];
function walk(dir) {
  for (const entry of readdirSync(dir, { withFileTypes: true })) {
    const p = join(dir, entry.name);
    if (entry.isSymbolicLink()) throw new Error("Symlinks are forbidden");
    if (entry.isDirectory()) {
      const rel = relative(dist, p).split(sep).join("/");
      if (inventory[rel] && inventory[rel].type !== "dir") throw new Error("Directory type conflict");
      walk(p);
    } else if (entry.isFile()) {
      const rel = relative(dist, p).split(sep).join("/");
      if (inventory[rel]) throw new Error("Unexpected existing file: approval plan must be revised");
      const bytes = readFileSync(p);
      files.push({ path: rel, operation: "add", bytes: bytes.length, sha256: hash(bytes) });
    } else throw new Error("Special files are forbidden");
  }
}
walk(dist);
const index = readFileSync(join(dist, "index.html"), "utf8");
const sitemap = readFileSync(join(dist, "sitemap.xml"), "utf8");
if (/noindex/i.test(index) || !sitemap.includes("https://inbes.jp/")) throw new Error("Not a production build");

const htaccess = join(backup, ".htaccess");
if (!inventory[".htaccess"] || !lstatSync(htaccess).isFile() || lstatSync(htaccess).isSymbolicLink()) {
  throw new Error("Original server configuration is required");
}
const original = readFileSync(htaccess);
const text = original.toString("utf8");
if (!Buffer.from(text).equals(original) || /# BEGIN INBES RENEWAL/.test(text) || /^\s*DirectoryIndex\b/im.test(text)) {
  throw new Error("Configuration requires manual review");
}
const lines = text.split(/(?<=\n)/);
const kept = [];
let removed = 0;
let conditions = [];
// The independently inspected legacy rule is pinned without recording its endpoint.
const obsoleteRuleSha256 = "68fe9d813c301616713b00201f1a9e02218d471d974bfd659437ddccfa601e65";
for (const line of lines) {
  if (/^\s*RewriteCond\s/i.test(line)) {
    conditions.push(line);
    continue;
  }
  const obsolete = hash(Buffer.from(line)) === obsoleteRuleSha256;
  if (obsolete) {
    if (conditions.length) throw new Error("Conditional old listing redirect requires manual review");
    removed += 1;
  } else {
    kept.push(...conditions, line);
  }
  conditions = [];
}
kept.push(...conditions);
if (removed !== 1) throw new Error("Expected exactly one obsolete listing redirect");
const block = [
  "# BEGIN INBES RENEWAL - temporary redirects pending live verification",
  "DirectoryIndex index.html index.php",
  "RewriteEngine On",
  "RewriteCond %{THE_REQUEST} \\s/+index\\.php(?:[?\\s]) [NC]",
  "RewriteRule ^index\\.php$ / [R=302,L]",
  "RewriteCond %{THE_REQUEST} \\s/+products\\.php(?:[?\\s]) [NC]",
  "RewriteRule ^products\\.php$ /products/ [R=302,L]",
  "# END INBES RENEWAL",
  ""
].join("\n");
const candidate = Buffer.from(block + kept.join(""));
mkdirSync(output, { mode: 0o700 });
const payload = join(output, "payload");
mkdirSync(payload, { mode: 0o700 });
cpSync(dist, payload, { recursive: true });
for (const file of files) {
  const copied = readFileSync(join(payload, file.path));
  if (copied.length !== file.bytes || hash(copied) !== file.sha256) throw new Error("Source changed during copy");
}
writeFileSync(join(payload, ".htaccess"), candidate, { mode: 0o600, flag: "wx" });
mkdirSync(join(output, "rollback"), { mode: 0o700 });
writeFileSync(join(output, "rollback", "original.htaccess"), original, { mode: 0o600, flag: "wx" });
const rollbackGuard = Buffer.from([
  "# BEGIN INBES RENEWAL ROLLBACK - explicitly select the legacy root entry",
  "DirectoryIndex index.php index.html",
  "RewriteEngine On",
  "RewriteRule ^$ index.php [L]",
  "# END INBES RENEWAL ROLLBACK",
  ""
].join("\n") + text);
writeFileSync(join(output, "rollback", ".htaccess"), rollbackGuard, { mode: 0o600, flag: "wx" });
files.sort((a, b) => a.path.localeCompare(b.path));
files.push({ path: ".htaccess", operation: "replace-last", bytes: candidate.length, sha256: hash(candidate), previousSha256: hash(original) });
const plan = {
  status: "prepared-not-approved-not-uploaded",
  backupManifest: manifestPath,
  serverWritesPerformed: false,
  deletionCount: 0,
  additions: files.filter((f) => f.operation === "add").length,
  replacements: 1,
  retainedDirectories: ["products", "cubele", "bulenu"],
  removedObsoleteRedirectRules: removed,
  rollback: { path: "rollback/.htaccess", sha256: hash(rollbackGuard), originalPath: "rollback/original.htaccess", originalSha256: hash(original), status: "pending-independent-review" },
  files,
  blockers: ["SMTP form handler not integrated or runtime verified", "Independent review of payload and rollback rewrite interactions", "Explicit production approval"]
};
writeFileSync(join(output, "plan.json"), JSON.stringify(plan, null, 2) + "\n", { mode: 0o600, flag: "wx" });
console.log(JSON.stringify({ prepared: true, additions: plan.additions, replacements: plan.replacements, deletions: 0, output }));
