#!/usr/bin/env python3
"""Offline transport, scope and reporting tests; never fetch credentials."""
from datetime import date
import importlib.util
from pathlib import Path
import unittest
from unittest.mock import patch
import requests

spec = importlib.util.spec_from_file_location('gsc', Path(__file__).with_name('search-console.py'))
gsc = importlib.util.module_from_spec(spec)
spec.loader.exec_module(gsc)


class Tests(unittest.TestCase):
    def test_endpoint_boundary(self):
        for method, url in [('GET', gsc.SITE), ('POST', gsc.TOKEN),
                            ('POST', gsc.SITE + '/searchAnalytics/query')]:
            self.assertTrue(gsc.allowed(method, url))
        for method, url in [('PUT', gsc.SITE), ('GET', gsc.SITE + '?other=1'),
                            ('GET', gsc.SITE.replace('inbes.jp', 'other.jp')),
                            ('POST', gsc.SITE + '/sitemaps'),
                            ('GET', gsc.SITE.replace('www.googleapis.com', 'evil.example'))]:
            self.assertFalse(gsc.allowed(method, url))

    def test_transport_lock(self):
        r = requests.Response()
        r.status_code = 200
        r._content = b'{}'
        r._content_consumed = True
        with patch.object(requests.Session, 'request', return_value=r) as send:
            with gsc.make_session() as session:
                self.assertFalse(session.trust_env)
                session.request('GET', gsc.SITE, allow_redirects=True, verify=False)
                kwargs = send.call_args.kwargs
                self.assertFalse(kwargs['allow_redirects'])
                self.assertTrue(kwargs['verify'])
                self.assertEqual(kwargs['timeout'], (10, 45))
                with self.assertRaises(gsc.SafeError):
                    session.get('https://evil.example/')
                self.assertEqual(send.call_count, 1)

    def test_error_body_suppressed(self):
        r = requests.Response()
        r.status_code = 302
        r._content = b'SECRET_SENTINEL'
        r._content_consumed = True
        with patch.object(requests.Session, 'request', return_value=r):
            with gsc.make_session() as session:
                with self.assertRaises(gsc.SafeError) as caught:
                    session.get(gsc.SITE)
                self.assertNotIn('SECRET_SENTINEL', str(caught.exception))

    def test_equal_length_previous_period(self):
        a, b = gsc.comparison_dates(date(2026, 10, 9), date(2026, 10, 21))
        self.assertEqual((a, b), (date(2026, 9, 26), date(2026, 10, 8)))
        with self.assertRaises(gsc.SafeError):
            gsc.comparison_dates(date(2026, 10, 9), date(2026, 10, 8))

    def test_query_final_and_metrics(self):
        with patch.object(gsc, 'request_json', return_value={'rows': []}) as read:
            self.assertEqual(gsc.query(None, gsc.START, gsc.START, ('page',)), [])
            payload = read.call_args.args[-1]
            self.assertEqual(payload['dataState'], 'final')
            self.assertEqual(payload['aggregationType'], 'auto')
        for bad in [float('nan'), -1, True, 'secret']:
            row = {'clicks': bad, 'impressions': 2, 'ctr': .1, 'position': 3}
            with patch.object(gsc, 'request_json', return_value={'rows': [row]}):
                with self.assertRaises(gsc.SafeError):
                    gsc.query(None, gsc.START, gsc.START)

    def test_no_other_property(self):
        with patch.object(gsc, 'request_json', return_value={
                'siteUrl': 'sc-domain:other.jp', 'permissionLevel': 'siteOwner'}):
            with self.assertRaises(gsc.SafeError):
                gsc.report(None, date(2026, 10, 23))

    def test_grant_widening_rejected(self):
        from google.oauth2.credentials import Credentials
        values = dict(zip(gsc.KEYS, ['id', 'SECRET_SENTINEL', 'refresh']))
        with patch.object(Credentials, 'refresh'), patch.object(
                Credentials, 'granted_scopes', new_callable=unittest.mock.PropertyMock,
                return_value=[gsc.SCOPE, 'https://www.googleapis.com/auth/webmasters']):
            with self.assertRaises(gsc.SafeError):
                gsc.connect(values, requests.Session())


if __name__ == '__main__':
    unittest.main()
