"""INBES-only, in-memory Turnstile binding. No CLI, writes, or implicit retrieval.

Call consume_settings only after explicit authorization to retrieve and transfer
these two values. Importing this module performs no authentication or retrieval.
The consumer owns the separately approved private-runtime provisioning step.
"""
from dataclasses import dataclass, field
import importlib.util
from pathlib import Path
import re
import urllib.parse

READER = Path('/mnt/sites/.agents/tools/inbes_infisical.py')
PROJECT = '9c44515d-195c-42bd-8d67-46eedf30f896'
ENVIRONMENT = 'prod'
SECRET_PATH = '/sites/inbes-jp'
KEYS = ('TURNSTILE_SITE_KEY', 'TURNSTILE_SECRET_KEY')


class BindingError(Exception):
    """Only fixed, non-secret messages."""


@dataclass(frozen=True)
class Settings:
    site_key: str = field(repr=False)
    secret_key: str = field(repr=False)

    def public_build_environment(self):
        return {'PUBLIC_TURNSTILE_SITE_KEY': self.site_key}

    def private_php_fields(self):
        return {'turnstile_secret': self.secret_key}


def _fetch_pair(reader, cfg, token):
    if (cfg.get('project_id') != PROJECT or cfg.get('environment') != ENVIRONMENT
            or cfg.get('secret_path') != SECRET_PATH):
        raise BindingError('Turnstile scope mismatch; no fallback.')
    query = urllib.parse.urlencode({
        'projectId': PROJECT, 'environment': ENVIRONMENT, 'secretPath': SECRET_PATH,
        'type': 'shared', 'includeImports': 'false', 'expandSecretReferences': 'false',
    })
    values = {}
    for key in KEYS:
        try:
            body = reader.request_json('/api/v4/secrets/' + key + '?' + query, token=token)
        except Exception:
            raise BindingError('Turnstile key unavailable; no fallback or retry.') from None
        item = body.get('secret') if isinstance(body, dict) else None
        if (not isinstance(item, dict) or item.get('secretKey') != key
                or item.get('workspace') != PROJECT or item.get('environment') != ENVIRONMENT
                or item.get('secretPath') != SECRET_PATH or item.get('type') != 'shared'
                or item.get('secretValueHidden') is True
                or not isinstance(item.get('secretValue'), str)
                or not re.fullmatch(r'[A-Za-z0-9_-]{1,256}', item['secretValue'])):
            raise BindingError('Turnstile key invalid in approved scope.')
        values[key] = item['secretValue']
    return Settings(values[KEYS[0]], values[KEYS[1]])


def consume_settings(consumer, *, approved=False):
    """No network unless approved=True; values reach only the given consumer.

    No value is printed or persisted here. The returned PHP fields deliberately
    omit turnstile_enabled: activation requires a separately reviewed rollout.
    """
    if approved is not True or not callable(consumer):
        raise BindingError('Explicit retrieval authorization and consumer required.')
    try:
        spec = importlib.util.spec_from_file_location('inbes_dedicated_reader', READER)
        reader = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(reader)
        # Retain the existing dedicated identity, origin, config and credential
        # guards; do not use a Personal/Business bundle or other project.
        cfg = reader.configuration()
        token = reader.authenticate(cfg, reader.read_registered_secret(Path(cfg['client_secret_file'])))
        settings = _fetch_pair(reader, cfg, token)
    except BindingError:
        raise
    except Exception:
        raise BindingError('Dedicated Turnstile retrieval unavailable; no fallback.') from None
    try:
        return consumer(settings)
    except Exception:
        raise BindingError('Turnstile consumer failed; details suppressed.') from None
