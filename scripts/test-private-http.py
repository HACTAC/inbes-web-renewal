"""Offline checks for HTTP-denial evidence, not live-site probes."""
import importlib.util
from pathlib import Path
import unittest
import urllib.error

spec = importlib.util.spec_from_file_location("private_http", Path(__file__).with_name("check-private-http.py"))
module = importlib.util.module_from_spec(spec)
spec.loader.exec_module(module)


class FakeOpener:
    def __init__(self, status):
        self.status = status
        self.calls = []

    def open(self, request, timeout):
        self.calls.append((request.full_url, request.method))
        raise urllib.error.HTTPError(request.full_url, self.status, "fixed", {}, None)


class DenialTests(unittest.TestCase):
    def test_all_named_files_and_methods_must_be_denied(self):
        opener = FakeOpener(403)
        result = module.inspect(opener)
        self.assertTrue(result["http_denial_verified"])
        self.assertEqual(len(opener.calls), len(module.PATHS) * len(module.METHODS))
        self.assertTrue(all(url.startswith(module.ORIGIN + "/.inbes-private/") for url, _ in opener.calls))

    def test_missing_redirect_or_server_error_is_not_denial(self):
        for status in [200, 301, 302, 404, 500, 503]:
            with self.subTest(status=status):
                self.assertFalse(module.inspect(FakeOpener(status))["http_denial_verified"])

    def test_network_failure_is_not_denial(self):
        class Broken:
            def open(self, *args, **kwargs):
                raise OSError("fixed")
        self.assertFalse(module.inspect(Broken())["http_denial_verified"])


if __name__ == "__main__":
    unittest.main()
