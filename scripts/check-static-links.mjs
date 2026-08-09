import { existsSync, readdirSync, readFileSync, statSync } from "node:fs";
import { join, relative } from "node:path";

const distRoot = join(process.cwd(), "dist");

if (!existsSync(distRoot)) {
  console.error("dist/がありません。先に npm run build を実行してください。");
  process.exit(1);
}

const htmlFiles = [];
const visit = (directory) => {
  for (const entry of readdirSync(directory)) {
    const filePath = join(directory, entry);
    if (statSync(filePath).isDirectory()) visit(filePath);
    else if (entry.endsWith(".html")) htmlFiles.push(filePath);
  }
};
visit(distRoot);

const localTargets = new Set();
const failures = [];

const targetCandidates = (href) => {
  const cleanPath = href.split("#")[0].split("?")[0];
  const normalized = cleanPath.replace(/^\//, "");
  if (!normalized) return [join(distRoot, "index.html")];
  return [
    join(distRoot, normalized, "index.html"),
    join(distRoot, `${normalized}.html`),
    join(distRoot, normalized)
  ];
};

for (const filePath of htmlFiles) {
  const html = readFileSync(filePath, "utf8");
  for (const match of html.matchAll(/href="([^"]+)"/g)) {
    const href = match[1];
    if (!href.startsWith("/")) continue;
    const candidates = targetCandidates(href);
    if (!candidates.some(existsSync)) {
      failures.push(`${relative(process.cwd(), filePath)} -> ${href}`);
    } else {
      localTargets.add(href.split("#")[0].split("?")[0] || "/");
    }
  }
}

if (failures.length) {
  console.error(`リンク切れ ${failures.length}件`);
  failures.forEach((failure) => console.error(`- ${failure}`));
  process.exit(1);
}

console.log(`内部リンク確認済み: ${localTargets.size}種類 / ${htmlFiles.length}ページ`);
