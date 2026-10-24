# Retired legacy contact route

The obsolete `contact.php` form points to `mailform/mail.php`, separately from the
current hardened `/contact/send.php`. The owner requested deletion on 2026-10-24.
The old public page still receives search clicks. Preserve those entry links by
returning a permanent redirect to `https://inbes.jp/contact/`, without forwarding
query parameters. Return410 Gone for the obsolete old mail handler; do not test
with real POST/mail. Current inquiry routes and private runtime stay unchanged.

Deploy the exact reviewed `deployment/legacy-contact-retirement.htaccess` block
before existing root rules, preserving all original root bytes, including private
access denial and security headers. This snippet is not a replacement root file.
First verify the root prefix using GET; then remove only backed-up exact-SHA
`contact.php` and `mailform/mail.php`. Keep all other mailform assets/directories.
Add the two paths to both the versioned and current NAS deployment exclusions.

The NAS release folder contains private original source backups (never publish,
commit, print recipients or copy any embedded configuration into documentation),
fixed before/after root hashes, reviewed executor, approval and readback journal.
Stop on changed/unknown content. Rollback restores own absent original files
first and original root last, with exact hashes and no intervening overwrites.

Expected GET: old contact301 to current contact, old mail410, current contact and
both inquiry forms200, private storage403, missing URLs404. Test GET only. Former
legacy sender acceptance is not asserted; no POST was performed to establish it.

This change does not remove other old company/OEM pages, modify modern sender
logic, alter SMTP credentials, change Turnstile/WAF or affect product copy/media.
