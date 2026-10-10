"""Offline tests only. No authentication, real credentials, files or network."""
import importlib.util
from pathlib import Path
import sys
import unittest
from unittest.mock import Mock, patch
from urllib.parse import urlsplit, parse_qs

spec = importlib.util.spec_from_file_location('turnstile_binding', Path(__file__).with_name('turnstile-settings.py'))
binding = importlib.util.module_from_spec(spec)
sys.modules[spec.name] = binding
spec.loader.exec_module(binding)


class BindingTests(unittest.TestCase):
    def setUp(self):
        self.cfg = {'project_id': binding.PROJECT, 'environment': binding.ENVIRONMENT, 'secret_path': binding.SECRET_PATH}
        self.reader = Mock()
        self.reader.request_json.side_effect = self.response

    def response(self, path, **kwargs):
        key = urlsplit(path).path.rsplit('/', 1)[1]
        self.assertIn(key, binding.KEYS)
        query = parse_qs(urlsplit(path).query)
        self.assertEqual(query['secretPath'], ['/sites/inbes-jp'])
        self.assertEqual(query['includeImports'], ['false'])
        self.assertEqual(query['expandSecretReferences'], ['false'])
        return {'secret': {'secretKey': key, 'workspace': binding.PROJECT,
                'environment': 'prod', 'secretPath': '/sites/inbes-jp', 'type': 'shared',
                'secretValue': 'public-test' if key == binding.KEYS[0] else 'private-test'}}

    def test_two_exact_keys_and_separate_consumers(self):
        settings = binding._fetch_pair(self.reader, self.cfg, 'dummy-token')
        self.assertEqual(self.reader.request_json.call_count, 2)
        self.assertEqual(settings.public_build_environment(), {'PUBLIC_TURNSTILE_SITE_KEY': 'public-test'})
        self.assertEqual(settings.private_php_fields(), {'turnstile_secret': 'private-test'})
        self.assertNotIn('private-test', repr(settings))
        self.assertNotIn('turnstile_enabled', settings.private_php_fields())

    def test_unapproved_never_loads_reader(self):
        with patch.object(binding.importlib.util, 'spec_from_file_location') as loader:
            for value in (False, None, 1):
                with self.assertRaises(binding.BindingError):
                    binding.consume_settings(lambda value: value, approved=value)
            loader.assert_not_called()

    def test_wrong_project_path_environment_never_calls_network(self):
        for key in self.cfg:
            with self.subTest(key=key):
                with self.assertRaises(binding.BindingError):
                    binding._fetch_pair(self.reader, dict(self.cfg, **{key: 'other'}), 'dummy-token')
        self.reader.request_json.assert_not_called()

    def test_response_scope_hidden_empty_and_injection_rejected(self):
        for key, value in [('workspace', 'other'), ('environment', 'dev'), ('secretPath', '/other'),
                           ('secretKey', 'FTP_PASSWORD'), ('type', 'personal'), ('secretValueHidden', True),
                           ('secretValue', ''), ('secretValue', None), ('secretValue', 'bad\nvalue')]:
            with self.subTest(key=key, value=value):
                def response(path, **kwargs):
                    row = self.response(path)
                    row['secret'][key] = value
                    return row
                self.reader.request_json.side_effect = response
                with self.assertRaises(binding.BindingError):
                    binding._fetch_pair(self.reader, self.cfg, 'dummy-token')

    def test_failure_does_not_retry_or_echo_provider_error(self):
        self.reader.request_json.side_effect = RuntimeError('private-test provider data')
        with self.assertRaises(binding.BindingError) as error:
            binding._fetch_pair(self.reader, self.cfg, 'dummy-token')
        self.assertNotIn('private-test', str(error.exception))
        self.assertEqual(self.reader.request_json.call_count, 1)


if __name__ == '__main__':
    unittest.main()
