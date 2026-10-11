# HTTP response security headers

The production root `.htaccess` appends `server/apache/security-headers.conf` to the existing file. Keep all existing redirects, cPanel PHP handler, directory indexing and private runtime denial unchanged. Deploy this Apache fragment separately from the static build; do not place backups in the public directory.

- `X-Content-Type-Options: nosniff` prevents MIME guessing.
- `X-Frame-Options: SAMEORIGIN` and CSP `frame-ancestors 'self'` constrain who can embed these pages. They do not prevent Turnstile frames embedded by these pages.
- `Referrer-Policy: strict-origin-when-cross-origin` retains same-origin referrers, strips paths on external links and sends none on HTTPS-to-HTTP navigation.
- Permissions Policy disables camera, microphone, geolocation, payment and USB APIs, which this site does not use.
- CSP also limits base URLs to same origin and disables plugin objects. Script, style, connection, image and child-frame sources remain unrestricted, preserving current analytics and Turnstile. This is a baseline CSP, not a complete script-injection defense.
- HSTS starts at seven days, applies only to the visited host and does not request preload. Both public hostnames have valid HTTPS and HTTP redirects to HTTPS. HSTS learned by a browser cannot be immediately revoked by restoring a server file; the initial maximum cache lifetime is seven days.

Use the Apache `onsuccess unset` plus `always set` pattern to avoid duplicates between static responses and PHP/FastCGI responses. `mod_headers` absence means the fragment does not activate; live validation must detect missing headers and restore the original.

Validate actual GET responses on the top page, both forms, handler, script and private-denial paths. Require original wrapper SHA/mode0600, no temporary files, preserved static/PHP hashes, handler GET token presence and Turnstile requirement, no-store, and HTTPS redirect behavior. Do not submit a form or send mail for this change.

References: https://httpd.apache.org/docs/2.4/mod/mod_headers.html ; https://developers.cloudflare.com/turnstile/reference/content-security-policy/ ; https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Strict-Transport-Security
