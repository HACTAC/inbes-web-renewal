import importlib.util
import hashlib
import json
from pathlib import Path
import tempfile
import unittest
from unittest.mock import patch

spec = importlib.util.spec_from_file_location('release', Path(__file__).with_name('nas-release.py'))
release = importlib.util.module_from_spec(spec)
spec.loader.exec_module(release)


class FTP:
    def __init__(self, data):
        self.data = dict(data)
        self.writes = []
        self.denied = False

    def mlsd(self, parent):
        if self.denied:
            raise PermissionError('listing denied')
        return [(Path(p).name, {'type': 'file', 'size': str(len(b))}) for p, b in self.data.items() if str(Path(p).parent) == parent]

    def retrbinary(self, command, callback, **kwargs):
        callback(self.data[command[5:]])

    def storbinary(self, command, stream):
        path = command[5:]
        self.data[path] = stream.read()
        self.writes.append(path)


class ReleaseTests(unittest.TestCase):
    def setUp(self):
        self.tmp = tempfile.TemporaryDirectory()
        self.addCleanup(self.tmp.cleanup)
        self.root = Path(self.tmp.name)
        self.repo = self.root / 'repo'
        (self.repo / 'dist').mkdir(parents=True)
        (self.repo / 'deployment').mkdir()
        (self.repo / 'deployment/legacy-files.json').write_text('{"files":[]}')
        self.new = b'<html><link rel="canonical" href="https://inbes.jp/"></html>'
        (self.repo / 'dist/index.html').write_bytes(self.new)
        self.excluded = self.root / 'excluded.json'
        self.excluded.write_text('{"files":[".DS_Store","_notes/dwsync.xml","unused.js"]}')
        self.state = self.root / 'state'
        self.state.mkdir()
        self.ftp = FTP({'index.html': b'old'})
        self.private_patch = patch.object(release, 'PRIVATE_ROOT', self.root)
        self.private_patch.start()
        self.addCleanup(self.private_patch.stop)
        self.repo_patch = patch.object(release, 'REPO', self.repo)
        self.repo_patch.start()
        self.addCleanup(self.repo_patch.stop)
        self.exclusions_patch = patch.object(release, 'EXCLUSIONS', self.excluded)
        self.exclusions_patch.start()
        self.addCleanup(self.exclusions_patch.stop)
        self.git_patch = patch.object(release.subprocess, 'check_output', return_value=b'commit\n')
        self.git_patch.start()
        self.addCleanup(self.git_patch.stop)

    def plan(self):
        p = release.plan_release(self.ftp, self.repo / 'dist', self.excluded, self.state)
        self.review = self.root / 'review.json'
        self.review.write_text(json.dumps({'verdict': 'PASS', 'plan_sha256': release.digest((self.state / 'plan.json').read_bytes())}))
        return p

    def apply(self):
        release.apply_release(self.ftp, self.excluded, self.state, self.review, 'approved test fixture')

    def test_plan_is_read_only_and_apply_reads_back_fixed_delta(self):
        self.plan()
        self.assertEqual(self.ftp.writes, [])
        self.assertEqual((self.state / 'original/index.html').read_bytes(), b'old')
        self.apply()
        self.assertEqual(self.ftp.writes, ['index.html'])
        self.assertEqual(self.ftp.data['index.html'], self.new)
        self.assertEqual(json.loads((self.state / 'journal.json').read_text())['events'][0]['state'], 'verified')

    def test_rollback_restores_original_and_refuses_other_edit(self):
        self.plan()
        self.apply()
        self.ftp.data['index.html'] = b'other editor'
        with self.assertRaises(ValueError):
            release.rollback_release(self.ftp, self.state, self.review, 'rollback fixture')
        self.ftp.data['index.html'] = self.new
        release.rollback_release(self.ftp, self.state, self.review, 'rollback fixture')
        self.assertEqual(self.ftp.data['index.html'], b'old')

    def test_new_file_rollback_never_deletes(self):
        self.ftp.data = {}
        self.plan()
        self.apply()
        with self.assertRaises(ValueError):
            release.rollback_release(self.ftp, self.state, self.review, 'rollback fixture')
        self.assertEqual(self.ftp.data['index.html'], self.new)

    def test_unchanged_build_has_no_delta(self):
        self.ftp.data['index.html'] = self.new
        self.assertEqual(self.plan()['files'], [])
        self.assertEqual(self.ftp.writes, [])

    def test_concurrent_change_stops_before_any_write(self):
        self.plan()
        self.ftp.data['index.html'] = b'other editor'
        with self.assertRaises(ValueError):
            self.apply()
        self.assertEqual(self.ftp.writes, [])

    def test_modified_payload_backup_plan_and_exclusions_are_rejected(self):
        self.plan()
        original = (self.state / 'payload/index.html').read_bytes()
        (self.state / 'payload/index.html').write_bytes(b'tampered')
        with self.assertRaises(ValueError):
            self.apply()
        (self.state / 'payload/index.html').write_bytes(original)
        (self.state / 'original/index.html').write_bytes(b'tampered')
        with self.assertRaises(ValueError):
            self.apply()
        (self.state / 'original/index.html').write_bytes(b'old')
        self.excluded.write_text('{"files":[]}')
        with self.assertRaises(ValueError):
            self.apply()
        self.excluded.write_text('{"files":[".DS_Store","_notes/dwsync.xml","unused.js"]}')
        (self.state / 'plan.json').write_text('{}')
        with self.assertRaises(ValueError):
            self.apply()
        self.assertEqual(self.ftp.writes, [])

    def test_other_exclusion_file_and_symlink_remote_parent_rejected(self):
        other = self.root / 'other-excluded.json'
        other.write_bytes(self.excluded.read_bytes())
        with self.assertRaises(ValueError):
            release.exclusion_paths(other)
        class LinkedFTP(FTP):
            def mlsd(self, parent):
                return [('assets', {'type': 'OS.unix=slink'})]
        with self.assertRaises(ValueError):
            release.remote(LinkedFTP({}), 'assets/image.jpg')

    def test_listing_failure_is_not_absence(self):
        self.ftp.denied = True
        with self.assertRaises(PermissionError):
            self.plan()
        self.assertEqual(self.ftp.writes, [])

    def test_excluded_build_file_is_rejected(self):
        (self.repo / 'dist/unused.js').write_bytes(b'old unused')
        with self.assertRaises(ValueError):
            self.plan()

    def test_unsafe_build_paths_and_review_build_are_rejected(self):
        for value in ['../outside', '/absolute', 'a/../b', 'a//b', '.agents/file', '.env']:
            with self.assertRaises(ValueError):
                release.safe_path(value)
        with self.assertRaises(ValueError):
            release.candidates(self.repo, set())
        (self.repo / 'dist/index.html').write_text('<link href="http://localhost:4321/">')
        with self.assertRaises(ValueError):
            self.plan()

    def test_production_redirect_noindex_is_allowed(self):
        (self.repo / 'dist/index.html').write_text('<meta name="robots" content="noindex"><link rel="canonical" href="https://inbes.jp/services/#flow">')
        self.assertEqual(len(release.candidates(self.repo / 'dist', set())), 1)
        (self.repo / 'dist/index.html').write_text('<meta name="robots" content="noindex, nofollow, noarchive">')
        with self.assertRaises(ValueError):
            self.plan()

    def test_symlink_payload_and_public_state_are_rejected(self):
        (self.repo / 'dist/link').symlink_to(self.excluded)
        with self.assertRaises(ValueError):
            self.plan()
        with self.assertRaises(ValueError):
            release.private_state(self.repo / 'state', create=True)


if __name__ == '__main__':
    unittest.main()
