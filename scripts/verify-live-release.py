"""Read back approved public static files; never request private settings or mail endpoints."""
from concurrent.futures import ThreadPoolExecutor
import hashlib
import json
from pathlib import PurePosixPath
import ssl
import sys
import urllib.error
import urllib.parse
import urllib.request


def verify(entry):
    relative = entry['path']
    path = PurePosixPath(relative)
    if path.is_absolute() or '..' in path.parts or '\\' in relative or relative.endswith('.php') or relative == '.htaccess':
        raise ValueError()
    request = urllib.request.Request('https://inbes.jp/' + urllib.parse.quote(relative, safe='/'),
                                     headers={'User-Agent': 'INBES-Website-Verification/1.0', 'Accept-Encoding': 'identity'})
    try:
        with urllib.request.urlopen(request, timeout=35, context=ssl.create_default_context()) as response:
            if urllib.parse.urlsplit(response.url).hostname != 'inbes.jp' or not response.url.startswith('https://'):
                raise ValueError()
            data = response.read(entry['bytes'] + 1)
            ok = response.status == 200 and len(data) == entry['bytes'] and hashlib.sha256(data).hexdigest() == entry['sha256']
            return {'path': relative, 'ok': ok, 'status': response.status}
    except urllib.error.HTTPError as error:
        return {'path': relative, 'ok': False, 'status': error.code}
    except Exception:
        return {'path': relative, 'ok': False, 'status': 0}


if __name__ == '__main__':
    from pathlib import Path
    plan = json.loads(Path(sys.argv[1]).read_text())
    entries = [f for f in plan['files'] if not f['path'].endswith('.php') and f['path'] != '.htaccess']
    with ThreadPoolExecutor(max_workers=4) as pool:
        results = list(pool.map(verify, entries))
    failures = [r for r in results if not r['ok']]
    print(json.dumps({'static_files_checked': len(results), 'matched': len(results) - len(failures), 'failures': failures}))
    sys.exit(1 if failures else 0)
