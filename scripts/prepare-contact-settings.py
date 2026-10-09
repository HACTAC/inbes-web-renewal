"""Approved, bounded SMTP configuration persistence; no remote actions or secret output."""
import base64
import ctypes
import errno
import importlib
import json
import os
from pathlib import Path
import re
import secrets
import stat
import sys

READER = Path('/Users/tacky/Documents/Codex/2026-10-08/task/inbes-project-reader')
KEYS = {
    'smtp_host': 'SMTP_HOST', 'smtp_port': 'SMTP_PORT',
    'smtp_security': 'SMTP_ENCRYPTION', 'smtp_user': 'SMTP_USERNAME',
    'smtp_password': 'SMTP_PASSWORD', 'from_address': 'MAIL_FROM_ADDRESS',
    'to_address': 'MAIL_TO_ADDRESS',
}


def acl_present(fd, allow_deny=False):
    libc = ctypes.CDLL('/usr/lib/libSystem.B.dylib', use_errno=True)
    libc.acl_get_fd_np.argtypes = [ctypes.c_int, ctypes.c_int]
    libc.acl_get_fd_np.restype = ctypes.c_void_p
    libc.acl_get_entry.argtypes = [ctypes.c_void_p, ctypes.c_int, ctypes.POINTER(ctypes.c_void_p)]
    libc.acl_free.argtypes = [ctypes.c_void_p]
    before = os.fstat(fd)
    ctypes.set_errno(0)
    acl = libc.acl_get_fd_np(fd, 0x100)
    failure = ctypes.get_errno()
    if not acl:
        after = os.fstat(fd)
        if (failure == errno.ENOENT and after.st_nlink > 0
                and (before.st_dev, before.st_ino) == (after.st_dev, after.st_ino)
                and (stat.S_ISDIR(after.st_mode) or stat.S_ISREG(after.st_mode))):
            return False
        raise ValueError()
    try:
        libc.acl_valid.argtypes = [ctypes.c_void_p]
        libc.acl_get_tag_type.argtypes = [ctypes.c_void_p, ctypes.POINTER(ctypes.c_int)]
        if libc.acl_valid(acl) != 0:
            raise ValueError()
        entry, entry_id = ctypes.c_void_p(), 0
        while True:
            ctypes.set_errno(0)
            result = libc.acl_get_entry(acl, entry_id, ctypes.byref(entry))
            if result == -1 and ctypes.get_errno() == errno.EINVAL:
                return False
            if result != 0:
                raise ValueError()
            if not allow_deny:
                return True
            tag = ctypes.c_int()
            if libc.acl_get_tag_type(entry, ctypes.byref(tag)) != 0 or tag.value != 2:
                return True
            entry_id = -1
    finally:
        libc.acl_free(acl)


def secure_open(path, directory=False, private=True):
    path = Path(path)
    if not path.is_absolute() or path.resolve(strict=True) != path:
        raise ValueError()
    fd = os.open('/', os.O_RDONLY | os.O_DIRECTORY)
    try:
        for index, part in enumerate(path.parts[1:]):
            final = index == len(path.parts) - 2
            flags = os.O_RDONLY | os.O_NOFOLLOW
            if not final or directory:
                flags |= os.O_DIRECTORY
            following = os.open(part, flags, dir_fd=fd)
            os.close(fd)
            fd = following
            info = os.fstat(fd)
            if info.st_uid not in (0, os.getuid()) or info.st_mode & 0o022 or acl_present(fd, allow_deny=not final):
                raise ValueError()
            if final and (info.st_uid != os.getuid() or (private and info.st_mode & 0o077)):
                raise ValueError()
            if final and not directory and (not stat.S_ISREG(info.st_mode) or info.st_nlink != 1):
                raise ValueError()
        return fd
    except BaseException:
        os.close(fd)
        raise


def build_configs(values, operation):
    if any(not isinstance(v, str) or not v or len(v) > 8192 for v in values.values()):
        raise ValueError()
    if not re.fullmatch(r'[A-Za-z0-9](?:[A-Za-z0-9.-]*[A-Za-z0-9])?', values['smtp_host']):
        raise ValueError()
    security = values['smtp_security']
    if security not in ('implicit_tls', 'starttls'):
        raise ValueError()
    port = int(values['smtp_port'])
    if (security, port) not in (('implicit_tls', 465), ('starttls', 587)):
        raise ValueError()
    for field in ('from_address', 'to_address'):
        if not re.fullmatch(r'[^\s@<>\x00-\x1f]+@[^\s@<>\x00-\x1f]+', values[field]):
            raise ValueError()
    recipient = operation['test_recipient']
    if not isinstance(recipient, str) or not re.fullmatch(r'[^\s@<>\x00-\x1f]+@[^\s@<>\x00-\x1f]+', recipient):
        raise ValueError()
    remote = operation['private_directory']
    public = operation['document_root']
    if not isinstance(remote, str) or not isinstance(public, str) or not remote.startswith('/home/') or not public.endswith('/public_html/inbes.jp'):
        raise ValueError()
    if Path(remote) != Path(public).parents[1] / '.inbes-contact':
        raise ValueError()
    if any(part in ('.', '..') for part in remote.split('/')) or Path(remote).as_posix() != remote:
        raise ValueError()
    production = dict(values)
    production.update({
        'enabled': False, 'origin': 'https://inbes.jp',
        'smtp_port': port, 'autoload': remote + '/vendor/autoload.php',
        'state_file': remote + '/rate-state.json',
        'rate_key': secrets.token_hex(32), 'autoreply_enabled': False,
    })
    test = dict(production)
    test['to_address'] = recipient
    test['state_file'] = remote + '/test-rate-state.json'
    test['rate_key'] = secrets.token_hex(32)
    return {'production-config.php': production, 'test-config.php': test}


def php_bytes(config):
    encoded = base64.b64encode(json.dumps(config, ensure_ascii=False).encode('utf-8'))
    return b"<?php\ndeclare(strict_types=1);\nreturn json_decode(base64_decode('" + encoded + b"', true), true, 512, JSON_THROW_ON_ERROR);\n"


def prepare(operation_path):
    fd = secure_open(operation_path)
    try:
        info = os.fstat(fd)
        if info.st_uid != os.getuid() or info.st_nlink != 1 or info.st_mode & 0o077 or info.st_size > 8192:
            raise ValueError()
        with os.fdopen(fd, 'r', encoding='utf-8') as stream:
            fd = None
            operation = json.load(stream)
    finally:
        if fd is not None:
            os.close(fd)
    if operation.get('approval') != 'approved-private-smtp-settings-2026-10-09':
        raise ValueError()
    filenames = ('production-config.php', 'test-config.php', 'rate-state.json', 'test-rate-state.json')
    output_fd = secure_open(operation['output_directory'], directory=True)
    try:
        for name in filenames:
            try:
                os.stat(name, dir_fd=output_fd, follow_symlinks=False)
            except FileNotFoundError:
                continue
            raise ValueError()
        for source in ('local_operation.py', 'project_reader.py'):
            descriptor = secure_open(READER / source, private=False)
            os.close(descriptor)
        sys.path.insert(0, str(READER))
        runner = importlib.import_module('local_operation')

        def operation_callback(values):
            configs = build_configs(values, operation)
            payloads = {name: php_bytes(config) for name, config in configs.items()}
            payloads.update({'rate-state.json': b'[]\n', 'test-rate-state.json': b'[]\n'})
            for name, content in payloads.items():
                descriptor = os.open(name, os.O_WRONLY | os.O_CREAT | os.O_EXCL | os.O_NOFOLLOW, 0o600, dir_fd=output_fd)
                with os.fdopen(descriptor, 'wb') as stream:
                    info = os.fstat(stream.fileno())
                    if info.st_uid != os.getuid() or info.st_nlink != 1 or info.st_mode & 0o077 or acl_present(stream.fileno()):
                        raise ValueError()
                    stream.write(content)
                    stream.flush()
                    os.fsync(stream.fileno())
                    if acl_present(stream.fileno()):
                        raise ValueError()
            os.fsync(output_fd)
            configs.clear()
            payloads.clear()

        runner.run_local_operation({label: ('prod', '/form-mail', key) for label, key in KEYS.items()}, operation_callback)
    finally:
        os.close(output_fd)


if __name__ == '__main__':
    try:
        if len(sys.argv) != 2:
            raise ValueError()
        prepare(sys.argv[1])
    except BaseException:
        print('settings_prepared=false; no secret output')
        sys.exit(1)
    print('settings_prepared=true; enabled=false; remote_operations=0; no secret output')
