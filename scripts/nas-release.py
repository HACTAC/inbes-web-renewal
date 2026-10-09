"""Plan by default; apply only an independently reviewed, backed-up fixed delta.
Credentials stay in the trusted NAS adapter. This tool never mirrors or deletes.
"""
import argparse
import hashlib
import importlib.util
import io
import json
import os
from pathlib import Path, PurePosixPath
import subprocess

REPO = Path(__file__).resolve().parents[1]
PRIVATE_ROOT = Path('/mnt/sites/.agents')
EXCLUSIONS = Path('/mnt/sites/.agents/inbes.jp/public-removed-paths.json')
FORBIDDEN = {'.git', '.agents', '@eaDir', '#recycle', '.DS_Store', 'AGENTS.md', '_notes', 'node_modules'}


def digest(data):
    return hashlib.sha256(data).hexdigest()


def safe_path(value, metadata=False):
    p = PurePosixPath(value)
    if not value or p.is_absolute() or str(p) != value or any((not metadata and (x in FORBIDDEN or x.startswith('.env'))) or x in ('.', '..') for x in p.parts) or '\\' in value or any(ord(c) < 32 for c in value):
        raise ValueError('Unsafe public path')
    return value


def regular(path):
    if not path.is_file() or any(p.is_symlink() for p in (path, *path.parents)):
        raise ValueError('Expected regular file without symlink ancestors')
    return path.read_bytes()


def atomic(path, value):
    temp = path.with_suffix('.tmp')
    temp.write_text(json.dumps(value, ensure_ascii=False, indent=2) + '\n')
    temp.chmod(0o600)
    os.replace(temp, path)


def private_state(path, create=False):
    path = path.absolute()
    if path != path.resolve():
        raise ValueError("State must be normalized")
    # Evidence must be outside any tracked/public source or canonical public site.
    if not path.is_relative_to(PRIVATE_ROOT) or path == PRIVATE_ROOT or path.is_relative_to(REPO) or path.is_relative_to(Path('/mnt/sites/inbes.jp')) or any(p.is_symlink() for p in (path, *path.parents)):
        raise ValueError('State must be outside source/public site, without symlinks')
    if create:
        path.mkdir(mode=0o700, parents=True, exist_ok=False)
    if not path.is_dir():
        raise ValueError('Missing private release state')
    path.chmod(0o700)
    return path


def exclusion_paths(path):
    if path.absolute() != EXCLUSIONS:
        raise ValueError('Use the current canonical NAS exclusions')
    data = json.loads(regular(path))
    return {safe_path(e if isinstance(e, str) else e['path'], metadata=True) for e in data['files']}


def candidates(build, excluded):
    build = build.absolute()
    # Only the project's Astro output, never a NAS-site directory or repo root.
    if build != REPO / 'dist':
        raise ValueError('Build must be this checkout dist')
    result = {}
    for p in build.rglob('*'):
        if p.is_symlink():
            raise ValueError('Symlink in build')
        if p.is_file():
            name = safe_path(p.relative_to(build).as_posix())
            if p.suffix.lower() in {'.php', '.ini', '.env', '.pem', '.key', '.zip', '.log'}:
                raise ValueError('Non-static file in Astro output')
            result[name] = regular(p)
    if 'index.html' not in result:
        raise ValueError('Missing built homepage')
    html = [v.decode('utf-8') for k, v in result.items() if k.endswith('.html')]
    if any('http://localhost:' in h or 'name="robots" content="noindex,' in h for h in html):
        raise ValueError('Development/review build cannot be published')
    for name in json.loads(regular(REPO / 'deployment/legacy-files.json'))['files']:
        name = safe_path(name)
        if name in result:
            raise ValueError('Build/legacy overlap')
        result[name] = regular(REPO / 'legacy-public' / name)
    if excluded.intersection(result):
        raise ValueError('Removed public file found in candidates')
    return result


def adapter_connection(path):
    if path != Path('/mnt/sites/.agents/tools/inbes_readonly_audit.py') or path.is_symlink():
        raise ValueError('Use an absolute trusted NAS adapter')
    spec = importlib.util.spec_from_file_location('nas_adapter', path)
    module = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(module)
    return module.connect(module.credentials())


def remote(ftp, name):
    # A successful listing proves absence. Permission/network errors never mean absent.
    current = '.'
    for part in PurePosixPath(name).parts[:-1]:
        entry = dict(ftp.mlsd(current)).get(part)
        if entry is None or entry.get('type') != 'dir':
            raise ValueError('Remote parent missing, linked or unknown')
        current = part if current == '.' else current + '/' + part
    parent = str(PurePosixPath(name).parent)
    facts = dict(ftp.mlsd(parent)).get(PurePosixPath(name).name)
    if facts is None:
        return None
    if facts.get('type') != 'file':
        raise ValueError('Remote target is not a file')
    data = bytearray()
    ftp.retrbinary('RETR ' + name, data.extend, blocksize=1024 * 1024)
    if len(data) != int(facts['size']):
        raise ValueError('Remote read incomplete')
    return bytes(data)


def plan_release(ftp, build, exclusions, state):
    files = candidates(build, exclusion_paths(exclusions))
    rows = []
    same = 0
    for index, (name, payload) in enumerate(sorted(files.items()), 1):
        if index % 25 == 0:
            print('Read-only comparisons: ' + str(index), flush=True)
        old = remote(ftp, name)
        if old == payload:
            same += 1
            continue
        if old is not None:
            dst = state / 'original' / name
            dst.parent.mkdir(mode=0o700, parents=True, exist_ok=True)
            dst.write_bytes(old)
            dst.chmod(0o600)
        dst = state / 'payload' / name
        dst.parent.mkdir(mode=0o700, parents=True, exist_ok=True)
        dst.write_bytes(payload)
        dst.chmod(0o600)
        rows.append({'path': name, 'before_sha256': digest(old) if old is not None else None,
                     'sha256': digest(payload), 'bytes': len(payload)})
    plan = {'site': 'inbes.jp', 'commit': subprocess.check_output(['git', 'rev-parse', 'HEAD'], cwd=REPO).decode().strip(),
            'candidate_files': len(files), 'unchanged': same, 'files': rows, 'deletions': [],
            'exclusions_sha256': digest(regular(exclusions)), 'tool_sha256': digest(regular(Path(__file__))),
            'policy': 'Only fixed delta; no mirror, no remote mkdir, no deletion. Missing parent stops planning.'}
    atomic(state / 'plan.json', plan)
    return plan


def apply_release(ftp, exclusions, state, review_path, approval):
    plan = json.loads(regular(state / 'plan.json'))
    review = json.loads(regular(review_path))
    if review.get('verdict') != 'PASS' or review.get('plan_sha256') != digest(regular(state / 'plan.json')) or not approval.strip():
        raise ValueError('Fixed-plan independent PASS and explicit approval required')
    if plan['site'] != 'inbes.jp' or plan['deletions'] or digest(regular(Path(__file__))) != plan['tool_sha256'] or digest(regular(exclusions)) != plan['exclusions_sha256']:
        raise ValueError('Plan/tool/exclusions changed')
    if (state / 'journal.json').exists():
        raise ValueError('Existing attempt; investigate instead of retrying')
    excluded = exclusion_paths(exclusions)
    seen = set()
    for e in plan['files']:
        name = safe_path(e['path'])
        if name in excluded or name in seen:
            raise ValueError('Excluded or duplicate target')
        seen.add(name)
        if digest(regular(state / 'payload' / name)) != e['sha256']:
            raise ValueError('Payload changed')
        if e['before_sha256'] and digest(regular(state / 'original' / name)) != e['before_sha256']:
            raise ValueError('Backup changed')

    def guard(e):
        old = remote(ftp, e['path'])
        if (digest(old) if old is not None else None) != e['before_sha256']:
            raise ValueError('Concurrent remote modification; stop')

    for e in plan['files']:
        guard(e)
    journal = {'plan_sha256': digest(regular(state / 'plan.json')), 'approval': approval, 'events': []}
    atomic(state / 'journal.json', journal)
    # Assets first, HTML last. Keep old assets; cache invalidation may need explicit versioned HTML.
    for e in sorted(plan['files'], key=lambda e: e['path'].endswith(('.html', '.php'))):
        guard(e)
        journal['events'].append({'path': e['path'], 'state': 'started'})
        atomic(state / 'journal.json', journal)
        ftp.storbinary('STOR ' + e['path'], io.BytesIO(regular(state / 'payload' / e['path'])))
        if digest(remote(ftp, e['path'])) != e['sha256']:
            raise ValueError('Upload readback mismatch; inspect journal and restore verified originals')
        journal['events'][-1]['state'] = 'verified'
        atomic(state / 'journal.json', journal)
    atomic(state / 'result.json', {'all_ok': True, 'uploaded': len(plan['files']), 'deletions': 0})


def rollback_release(ftp, state, review_path, approval):
    plan = json.loads(regular(state / 'plan.json'))
    review = json.loads(regular(review_path))
    journal = json.loads(regular(state / 'journal.json'))
    plan_sha = digest(regular(state / 'plan.json'))
    if review.get('verdict') != 'PASS' or review.get('plan_sha256') != plan_sha or journal['plan_sha256'] != plan_sha or not approval.strip() or digest(regular(Path(__file__))) != plan['tool_sha256']:
        raise ValueError('Rollback requires unchanged plan/tool, review and approval')
    if (state / 'rollback-journal.json').exists():
        raise ValueError('Existing rollback attempt; investigate')
    attempted = {e['path'] for e in journal['events']}
    items = [e for e in plan['files'] if e['path'] in attempted]
    if any(e['before_sha256'] is None for e in items):
        raise ValueError('New-file removal requires separate recovery; this tool never deletes')
    for e in items:
        safe_path(e['path'])
        if digest(regular(state / 'original' / e['path'])) != e['before_sha256'] or digest(remote(ftp, e['path'])) != e['sha256']:
            raise ValueError('Changed or partial upload; manual investigation required')
    progress = {'plan_sha256': plan_sha, 'approval': approval, 'events': []}
    for e in sorted(items, key=lambda e: not e['path'].endswith(('.html', '.php'))):
        if digest(remote(ftp, e['path'])) != e['sha256']:
            raise ValueError('Concurrent change during rollback')
        progress['events'].append({'path': e['path'], 'state': 'started'})
        atomic(state / 'rollback-journal.json', progress)
        ftp.storbinary('STOR ' + e['path'], io.BytesIO(regular(state / 'original' / e['path'])))
        if digest(remote(ftp, e['path'])) != e['before_sha256']:
            raise ValueError('Rollback readback mismatch')
        progress['events'][-1]['state'] = 'verified'
        atomic(state / 'rollback-journal.json', progress)


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('action', nargs='?', choices=['plan', 'apply', 'rollback'], default='plan')
    parser.add_argument('--state', required=True, type=Path)
    parser.add_argument('--adapter', required=True, type=Path)
    parser.add_argument('--excluded', required=True, type=Path)
    parser.add_argument('--review', type=Path)
    parser.add_argument('--approval')
    args = parser.parse_args()
    if args.action in ('apply', 'rollback') and (not args.review or not args.approval):
        parser.error('apply/rollback requires --review and --approval')
    os.umask(0o077)
    state = private_state(args.state, create=args.action == 'plan')
    ftp = adapter_connection(args.adapter)
    try:
        if args.action == 'plan':
            plan = plan_release(ftp, REPO / 'dist', args.excluded, state)
            print(json.dumps({'compared': plan['candidate_files'], 'unchanged': plan['unchanged'], 'changes': len(plan['files']), 'writes': 0}))
        elif args.action == 'apply':
            apply_release(ftp, args.excluded, state, args.review, args.approval)
            print('Fixed delta verified. Run HTTP/browser health checks before recording completion.')
        else:
            rollback_release(ftp, state, args.review, args.approval)
            print('Original files restored and verified; check HTTP/browser health.')
    finally:
        ftp.close()


if __name__ == '__main__':
    try:
        main()
    except Exception:
        # Credentials/endpoints might occur in transport exceptions; never print them.
        raise SystemExit('Release stopped. Inspect private evidence and connectivity without exposing credentials.')
