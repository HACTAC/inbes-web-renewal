"""Prepare the reviewed news-only release; no server writes."""
import hashlib
import json
import os
from pathlib import Path
import stat
import zipfile
from datetime import datetime


def sha(data):
    return hashlib.sha256(data).hexdigest()


def regular(path):
    if any(p.is_symlink() for p in (path, *path.parents)) or not path.is_file():
        raise SystemExit("Unsafe source path")
    return path.read_bytes()


repo = Path(__file__).resolve().parent.parent
state = Path("/Users/tacky/Web管理/.inbes-backup-state")
if state.resolve() != state or stat.S_IMODE(state.stat().st_mode) != 0o700:
    raise SystemExit("Unsafe state directory")
previous = state / "migration-20261009-final"
old_plan = json.loads(regular(previous / "plan.json"))
baseline = {}
for item in old_plan["files"]:
    name = item["path"]
    if name.endswith(".php") or name == ".htaccess":
        continue
    if Path(name).is_absolute() or ".." in Path(name).parts or "\\" in name:
        raise SystemExit("Unsafe baseline path")
    data = regular(previous / "payload" / name)
    if sha(data) != item["sha256"]:
        raise SystemExit("Baseline digest mismatch")
    baseline[name] = data
analytics_plan = json.loads(regular(state / "analytics-20261009" / "plan.json"))
archive_bytes = regular(state / "analytics-20261009" / "analytics-html.zip")
if sha(archive_bytes) != analytics_plan["archiveSha256"]:
    raise SystemExit("Analytics archive digest mismatch")
with zipfile.ZipFile(state / "analytics-20261009" / "analytics-html.zip") as archive:
    for item in analytics_plan["files"]:
        data = archive.read(item["path"])
        if sha(data) != item["sha256"]:
            raise SystemExit("Analytics entry digest mismatch")
        baseline[item["path"]] = data

allowed = {
    "index.html", "news/index.html", "news/corporate-site-renewal/index.html",
    "news/capital-increase-2025/index.html", "sitemap.xml"
}
deleted = {"news/product-information-update/index.html", "news/development-works-update/index.html"}
current = {p.relative_to(repo / "dist").as_posix(): regular(p)
           for p in (repo / "dist").rglob("*") if p.is_file()}
if set(baseline) - set(current) != deleted or set(current) - set(baseline) != {"news/capital-increase-2025/index.html"}:
    raise SystemExit("Unexpected addition or deletion")
changed = {name for name, data in current.items() if baseline.get(name) != data}
if changed != allowed:
    raise SystemExit("Unexpected release scope")
os.umask(0o077)
output = state / "news-20261009-v3"
output.mkdir(mode=0o700, exist_ok=False)
files = []
for name in sorted(allowed | deleted):
    if name in baseline:
        backup = output / "rollback" / name
        backup.parent.mkdir(parents=True, exist_ok=True, mode=0o700)
        backup.write_bytes(baseline[name])
    if name in allowed:
        data = current[name]
        files.append({"path": name, "bytes": len(data), "sha256": sha(data),
                      "previousSha256": sha(baseline[name]) if name in baseline else None})
zip_path = output / "news-release.zip"
with zipfile.ZipFile(zip_path, "w", compression=zipfile.ZIP_DEFLATED) as archive:
    for item in files:
        entry = zipfile.ZipInfo(item["path"], date_time=datetime.now().timetuple()[:6])
        entry.create_system = 3
        entry.external_attr = (stat.S_IFREG | 0o644) << 16
        entry.compress_type = zipfile.ZIP_DEFLATED
        archive.writestr(entry, current[item["path"]])
plan = {"files": files, "deletions": [{"path": name, "previousSha256": sha(baseline[name])}
                                    for name in sorted(deleted)], "archiveSha256": sha(regular(zip_path))}
(output / "plan.json").write_text(json.dumps(plan, ensure_ascii=False, indent=2) + "\n")
print(json.dumps({"updates": len(files), "deletions": len(deleted), "archiveSha256": plan["archiveSha256"]}))
