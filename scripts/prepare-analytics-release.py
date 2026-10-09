import hashlib
import json
import os
from pathlib import Path
import stat
import zipfile


def digest(data):
    return hashlib.sha256(data).hexdigest()


repo = Path(__file__).resolve().parent.parent
previous = Path("/Users/tacky/Web管理/.inbes-backup-state/migration-20261009-final")
output = previous.parent / "analytics-20261009"
parent = output.parent
if parent.is_symlink() or parent.resolve() != parent or stat.S_IMODE(parent.stat().st_mode) != 0o700:
    raise SystemExit("Unsafe output parent")
os.umask(0o077)
old_plan = json.loads((previous / "plan.json").read_text())
html_files = [item for item in old_plan["files"] if item["path"].endswith(".html")]
if len(html_files) != 17:
    raise SystemExit("Unexpected HTML scope")
for item in old_plan["files"]:
    name = item["path"]
    if name.endswith(".html") or name.endswith(".php") or name == ".htaccess":
        continue
    if digest((repo / "dist" / name).read_bytes()) != item["sha256"]:
        raise SystemExit("Non-HTML build changed")

files = []
for item in html_files:
    name = item["path"]
    if Path(name).is_absolute() or ".." in Path(name).parts:
        raise SystemExit("Unexpected path")
    old_file = previous / "payload" / name
    new_file = repo / "dist" / name
    if old_file.is_symlink() or new_file.is_symlink():
        raise SystemExit("Unexpected symlink")
    old = old_file.read_bytes()
    new = new_file.read_bytes()
    if digest(old) != item["sha256"]:
        raise SystemExit("Rollback file changed")
    if name == "process/index.html" and new != old:
        raise SystemExit("Redirect page changed")
    if name != "process/index.html" and (new.count(b"allow_google_signals") != 1 or b"G-5SQ5RBJXCT" not in new):
        raise SystemExit("Unexpected analytics tag")
    files.append({"path": name, "bytes": len(new), "sha256": digest(new), "previousSha256": digest(old)})

output.mkdir(mode=0o700, exist_ok=False)
with zipfile.ZipFile(output / "analytics-html.zip", "w", compression=zipfile.ZIP_DEFLATED) as archive:
    for item in files:
        name = item["path"]
        rollback = output / "rollback" / name
        rollback.parent.mkdir(parents=True, mode=0o700, exist_ok=True)
        rollback.write_bytes((previous / "payload" / name).read_bytes())
        os.chmod(rollback, 0o600)
        data = (repo / "dist" / name).read_bytes()
        entry = zipfile.ZipInfo(name)
        entry.create_system = 3
        entry.external_attr = (stat.S_IFREG | 0o644) << 16
        entry.compress_type = zipfile.ZIP_DEFLATED
        archive.writestr(entry, data)
archive_path = output / "analytics-html.zip"
plan = {"files": files, "changes": {"htmlReplacements": 17, "deletions": 0}, "archiveSha256": digest(archive_path.read_bytes())}
(output / "plan.json").write_text(json.dumps(plan, ensure_ascii=False, indent=2) + "\n")
with zipfile.ZipFile(archive_path) as archive:
    if sorted(archive.namelist()) != sorted(item["path"] for item in files):
        raise SystemExit("Archive scope mismatch")
    for item in files:
        if digest(archive.read(item["path"])) != item["sha256"]:
            raise SystemExit("Archive content mismatch")
print("Analytics release prepared: HTML 17, non-HTML unchanged, deletions 0, rollback 17.")
print("Archive SHA256:", plan["archiveSha256"])
