"""One approved test submission, in-memory cookies, no retries or raw response logs."""
import http.cookiejar
import json
import re
import ssl
import sys
import urllib.error
import urllib.parse
import urllib.request

from importlib.util import module_from_spec, spec_from_file_location
from pathlib import Path

submission_started = False


def main():
    global submission_started
    if len(sys.argv) != 3 or sys.argv[2] not in ('--check-only', '--send-one-approved-test'):
        raise ValueError()
    spec = spec_from_file_location('guards', Path(__file__).with_name('prepare-contact-settings.py'))
    guards = module_from_spec(spec)
    spec.loader.exec_module(guards)
    fd = guards.secure_open(sys.argv[1])
    import os
    with os.fdopen(fd, 'r', encoding='utf-8') as stream:
        operation = json.load(stream)

    class NoRedirect(urllib.request.HTTPRedirectHandler):
        def redirect_request(self, *args, **kwargs):
            raise ValueError()

    opener = urllib.request.build_opener(
        NoRedirect(), urllib.request.HTTPSHandler(context=ssl.create_default_context()),
        urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()),
    )
    opener.addheaders = [('User-Agent', 'INBES-Website-Verification/1.0'), ('Accept', 'application/json')]
    origin = 'https://inbes.jp'
    endpoint = origin + '/contact/send.php'
    with opener.open(endpoint, timeout=35) as response:
        body = json.loads(response.read(8193))
        token = body.get('token')
        if response.status != 200 or body.get('ok') is not True or not isinstance(token, str) or not re.fullmatch(r'[a-f0-9]{64}', token):
            raise ValueError()
    print('token_check=true; cookies=in_memory; no response values logged')
    if sys.argv[2] == '--check-only':
        return
    if operation.get('test_approval') != 'approved-single-smtp-test-2026-10-09':
        raise ValueError()
    recipient = operation['test_recipient']
    if not isinstance(recipient, str) or not re.fullmatch(r'[^\s@<>\x00-\x1f]+@[^\s@<>\x00-\x1f]+', recipient):
        raise ValueError()
    form = {'category': '\u5546\u54c1\u5316\u306e\u76f8\u8ac7', 'privacy': 'on', 'website': '',
            'company': 'INBES', 'name': 'Website SMTP test', 'email': recipient, 'tel': '',
            'message': 'Authorized website renewal SMTP test. No customer inquiry. No attachment.', 'token': token}
    request = urllib.request.Request(endpoint, data=urllib.parse.urlencode(form).encode('utf-8'),
                                     headers={'Origin': origin, 'Content-Type': 'application/x-www-form-urlencoded'}, method='POST')
    submission_started = True
    with opener.open(request, timeout=35) as response:
        body = json.loads(response.read(8193))
        if response.status != 200 or body.get('ok') is not True or body.get('replySent') is not False:
            raise ValueError()
    print('smtp_accepted=true; submissions=1; autoreply=false; mailbox_delivery_unverified=true')


if __name__ == '__main__':
    try:
        main()
    except BaseException as error:
        status = error.code if isinstance(error, urllib.error.HTTPError) else 0
        outcome = 'submission_outcome=unknown; do_not_resubmit=true' if submission_started else 'test_failed_before_submission=true; submissions=0'
        print(outcome + '; error_type=' + type(error).__name__ + '; http_status=' + str(status) + '; no automatic retry; no response values logged')
        sys.exit(1)
