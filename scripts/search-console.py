#!/usr/bin/env python3
"""NAS-only INBES Search Console reader. Never writes Google configuration."""
import argparse
from datetime import date, datetime, timedelta, timezone
import importlib.util
import json
import math
import os
from pathlib import Path
import resource
import sys
from urllib.parse import quote, urlencode
from zoneinfo import ZoneInfo

SCOPE = 'https://www.googleapis.com/auth/webmasters.readonly'
PROPERTY = 'sc-domain:inbes.jp'
SITE = 'https://www.googleapis.com/webmasters/v3/sites/' + quote(PROPERTY, safe='')
TOKEN = 'https://oauth2.googleapis.com/token'
KEYS = ('GSC_CLIENT_ID', 'GSC_CLIENT_SECRET', 'GSC_REFRESH_TOKEN')
CLIENT_ID = '883879471726-n6il1k7tgchpcgb4026pn5gq5tqhnm7f.apps.googleusercontent.com'
OUTPUT = Path('/mnt/sites/.agents/inbes.jp/search-console')
START = date(2026, 10, 9)


class SafeError(Exception):
    """Fixed, non-secret diagnostics only."""


def allowed(method, url):
    return (method, url) in {('POST', TOKEN), ('GET', SITE),
                            ('GET', SITE + '/sitemaps'),
                            ('POST', SITE + '/searchAnalytics/query')}


def fetch_credentials():
    spec = importlib.util.spec_from_file_location(
        'inbes_identity', '/mnt/sites/.agents/tools/inbes_infisical.py')
    helper = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(helper)
    cfg = helper.configuration()
    token = helper.authenticate(cfg, helper.read_registered_secret(Path(cfg['client_secret_file'])))
    query = urlencode({'projectId': cfg['project_id'], 'environment': 'prod',
                       'secretPath': '/sites/inbes-jp', 'type': 'shared',
                       'includeImports': 'false', 'expandSecretReferences': 'false'})
    values = {}
    for key in KEYS:
        item = helper.request_json('/api/v4/secrets/' + key + '?' + query, token=token).get('secret')
        if (not isinstance(item, dict) or item.get('secretKey') != key
                or item.get('workspace') != cfg['project_id']
                or item.get('environment') != 'prod' or item.get('secretPath') != '/sites/inbes-jp'
                or item.get('type') != 'shared' or item.get('secretValueHidden') is True):
            raise SafeError('Infisical key metadata did not match the approved scope.')
        value = item.get('secretValue')
        if not isinstance(value, str) or not value or '${' in value or value != value.strip():
            raise SafeError('A required Google credential is empty or malformed.')
        values[key] = value
    if values['GSC_CLIENT_ID'] != CLIENT_ID:
        raise SafeError('Google client ID does not match the owner-created INBES client.')
    return values


def make_session():
    import requests

    class LockedSession(requests.Session):
        def request(self, method, url, **kwargs):
            if not allowed(method.upper(), url):
                raise SafeError('Request outside the approved read-only endpoint list.')
            kwargs.update(allow_redirects=False, timeout=(10, 45), verify=True, stream=True)
            try:
                response = super().request(method, url, **kwargs)
                if response.status_code != 200:
                    status = response.status_code
                    response.close()
                    raise SafeError('Google request rejected (HTTP %d); response suppressed.' % status)
                chunks, length = [], 0
                for chunk in response.iter_content(65536):
                    length += len(chunk)
                    if length > 4 * 1024 * 1024:
                        raise SafeError('Google response exceeded the permitted size.')
                    chunks.append(chunk)
                response._content = b''.join(chunks)
                response._content_consumed = True
                response.close()
                return response
            except requests.RequestException:
                raise SafeError('Google HTTPS request failed; details suppressed.') from None
    session = LockedSession()
    session.trust_env = False
    return session


def connect(values, session):
    from google.oauth2.credentials import Credentials
    from google.auth.transport.requests import Request
    credentials = Credentials(None, refresh_token=values['GSC_REFRESH_TOKEN'],
                              client_id=values['GSC_CLIENT_ID'],
                              client_secret=values['GSC_CLIENT_SECRET'],
                              token_uri=TOKEN, scopes=[SCOPE])
    credentials.refresh(Request(session=session))
    granted = credentials.granted_scopes
    if granted is not None and set(granted) != {SCOPE}:
        raise SafeError('Google returned a grant outside the requested read-only scope.')
    # If Google omits scope, RFC 6749 defines it as identical to the requested scope.
    session.headers['Authorization'] = 'Bearer ' + credentials.token
    return {'scope_requested': SCOPE, 'scope_returned': sorted(granted) if granted else None,
            'scope_response_note': 'omitted means requested scope; endpoint allowlist always enforced'}


def request_json(session, method, url, payload=None):
    response = session.request(method, url, **({'json': payload} if payload is not None else {}))
    try:
        body = response.json()
    except ValueError:
        raise SafeError('Google returned invalid JSON; contents suppressed.') from None
    if not isinstance(body, dict):
        raise SafeError('Google returned an unexpected response structure.')
    return body


def query(session, start, end, dimensions=(), limit=1000):
    payload = {'startDate': start.isoformat(), 'endDate': end.isoformat(),
               'dimensions': list(dimensions), 'type': 'web', 'dataState': 'final',
               'rowLimit': limit, 'aggregationType': 'auto' if 'page' in dimensions else 'byProperty'}
    body = request_json(session, 'POST', SITE + '/searchAnalytics/query', payload)
    rows = body.get('rows', [])
    if not isinstance(rows, list) or len(rows) > limit:
        raise SafeError('Unexpected Search Console row count.')
    for row in rows:
        if not isinstance(row, dict):
            raise SafeError('Unexpected Search Console row.')
        for key in ('clicks', 'impressions', 'ctr', 'position'):
            val = row.get(key)
            if isinstance(val, bool) or not isinstance(val, (int, float)) or not math.isfinite(val) or val < 0:
                raise SafeError('Invalid Search Console metric.')
        keys = row.get('keys', [])
        if len(keys) != len(dimensions) or not all(isinstance(k, str) for k in keys):
            raise SafeError('Unexpected Search Console dimensions.')
    return rows


def comparison_dates(start, end):
    if end < start:
        raise SafeError('Final data does not yet overlap the renewal period.')
    return start - timedelta(days=(end - start).days + 1), start - timedelta(days=1)


def report(session, today):
    info = request_json(session, 'GET', SITE)
    if info.get('siteUrl') != PROPERTY or info.get('permissionLevel') not in (
            'siteOwner', 'siteFullUser', 'siteRestrictedUser'):
        raise SafeError('Google property/access does not match the requested INBES property.')
    daily = query(session, START, today, ('date',))
    if not daily:
        return {'property': PROPERTY, 'permission': info['permissionLevel'], 'daily': [],
                'note': 'No final rows returned; not evidence of zero site visitors.'}
    dates = [date.fromisoformat(row['keys'][0]) for row in daily]
    if any(d < START or d > today for d in dates):
        raise SafeError('Returned dates are outside the requested range.')
    end = max(dates)
    before_start, before_end = comparison_dates(START, end)
    return {'property': PROPERTY, 'permission': info['permissionLevel'],
            'period': [START.isoformat(), end.isoformat()],
            'comparison_period': [before_start.isoformat(), before_end.isoformat()],
            'daily': sorted(daily, key=lambda row: row['keys'][0]),
            'totals': query(session, START, end, limit=1),
            'comparison_totals': query(session, before_start, before_end, limit=1),
            'queries': query(session, START, end, ('query',)),
            'pages': query(session, START, end, ('page',)),
            'devices': query(session, START, end, ('device',)),
            'sitemaps': request_json(session, 'GET', SITE + '/sitemaps'),
            'limits': ['Dates use Search Console Pacific Time; final rows only.',
                       'Last date means latest returned row, not proof of the global data cutoff.',
                       'Query/page rows are bounded top rows; anonymized queries may be omitted.',
                       'Google Search web traffic only, not all visitors or AI citations.',
                       'Domain property includes subdomains; before/after is not causal proof.']}


def save_report(body):
    OUTPUT.mkdir(mode=0o700, exist_ok=True)
    info = OUTPUT.stat()
    if OUTPUT.is_symlink() or info.st_uid != os.getuid() or info.st_mode & 0o077:
        raise SafeError('Report directory must be owner-only and not a symlink.')
    path = OUTPUT / ('report-' + datetime.now(timezone.utc).strftime('%Y%m%dT%H%M%SZ') + '.json')
    fd = os.open(path, os.O_CREAT | os.O_EXCL | os.O_WRONLY | os.O_NOFOLLOW, 0o600)
    with os.fdopen(fd, 'w') as handle:
        json.dump(body, handle, ensure_ascii=False, indent=2)
        handle.write('\n')
    return path


def main():
    resource.setrlimit(resource.RLIMIT_CORE, (0, 0))
    os.umask(0o077)
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('action', choices=['report'])
    parser.parse_args()
    try:
        with make_session() as session:
            grant = connect(fetch_credentials(), session)
            result = report(session, datetime.now(ZoneInfo('America/Los_Angeles')).date())
        path = save_report({'checked_at_utc': datetime.now(timezone.utc).isoformat(),
                            'authentication': 'success', 'grant': grant, 'result': result})
        print('Read-only Google Search Console report saved: ' + str(path))
        return 0
    except (KeyboardInterrupt, EOFError):
        print('Cancelled; no Google configuration was changed.', file=sys.stderr)
    except SafeError as exc:
        print(str(exc), file=sys.stderr)
    except Exception:
        print('Read-only check failed; sensitive exception details suppressed.', file=sys.stderr)
    return 1


if __name__ == '__main__':
    sys.exit(main())
