# Search Console read-only NAS reader

This administrative client is never deployed to the website. It reads only
`sc-domain:inbes.jp` using the Google `webmasters.readonly` scope. The POST search
analytics endpoint is a read query, not a configuration change. No other
properties, sitemap submissions, Google Cloud changes or automatic grants.

Owner-created Google Cloud project: `inbes-512501`, number `883879471726`.
The earlier Google Analytics account number is not a Cloud project or GA4 property ID.
Owner completes consent using their own web OAuth client in Google's OAuth
Playground (redirect URI `https://developers.google.com/oauthplayground`, offline,
own credentials enabled). Only request `https://www.googleapis.com/auth/webmasters.readonly`.
External Testing authorizations/refresh tokens expire after seven days. Durable
publishing/audience changes remain an owner operation, not part of this reader.

Owner manually saves `GSC_CLIENT_ID`, `GSC_CLIENT_SECRET`, `GSC_REFRESH_TOKEN` in
existing Website Management / prod /sites/inbes-jp. Never paste values into chat,
screenshots, shell arguments, environment variables or Git. The reader reuses the
existing INBES dedicated Infisical identity; it fetches only these three names,
validates the project/path metadata and exact owner-created Google client ID.
No new identity/bootstrap or website-runtime credential placement.

Install requirements in an isolated Python environment. Current NAS runtime:
`/mnt/sites/.agents/work/inbes-gsc-runtime-20261023/bin/python`.

```sh
python scripts/test-search-console.py
python scripts/search-console.py report
```

Live command requires owner-reported registration completion and task permission.
Reports are local owner-only JSON under `/mnt/sites/.agents/inbes.jp/search-console`.
Credentials/access tokens remain in memory; TLS verification is mandatory,
redirects/proxy environment ignored, fixed method/endpoint allowlist enforced.
No automatic retry, token persistence, reauthorization, logging of raw errors.
Scope response may be omitted by Google (same as requested per RFC6749); this is
recorded separately from explicit returned scopes. Broader returned scope stops.
Same-UID processes/root can access in-memory credentials; this does not provide
an isolation boundary from administrators of this NAS account.

Reports use final Search Console web data and Pacific Time dates, from renewal
2026-10-09 through the latest returned date. Previous equal-length period is
queried separately. Last returned date need not be the global reporting cutoff.
Totals are requested independently rather than averaging CTR/position. Detail
rows are capped at 1000; anonymized queries can be omitted. Domain data includes
subdomains, not all site visits or AI citations; before/after is not causal proof.

Rollback: stop invoking the reader. No public-site changes to undo. Keep private
reports as evidence. Revoking Google consent is an owner operation, not automatic.

## Public disclosure pages (separate release)

The administrative reader stays private. Two public information pages describe
its actual function/data handling, without a login form, credentials, tracking or
browser-side API access. They are excluded from the sitemap and have noindex.

- App homepage: `https://inbes.jp/site-analytics/`
- App privacy: `https://inbes.jp/site-analytics/privacy/`

Google branding must use this app homepage with its matching privacy link, after
both pages have actually been published and read back. Current corporate-homepage
branding alone does not describe the tool. Existing general privacy text stays
unchanged. These new public disclosures need explicit two-file release approval;
local preparation is not publication or a Google verification guarantee.

Build with `PUBLIC_SITE_URL=https://inbes.jp PUBLIC_DEPLOY_ENV=production` and
release only `site-analytics/index.html` and `site-analytics/privacy/index.html`.
Default same-environment build comparison confirmed all pre-existing 138 files
remain byte-identical; new pages need no extra assets or existing-page edits.
Preflight requires the new directory to be absent; stop on collision. Rollback
removes only newly added same-hash pages and empty newly created directories,
preserving any intervening changes. Do not reupload all dist or credentials.

Public disclosures intentionally state that local search reports currently have
no automatic deletion deadline, queries/URLs may contain names, analysis uses
OpenAI, and revocation is separate from deletion of saved data. Do not replace
these facts with unimplemented retention/training promises. Scope/data-sharing
changes require updated disclosures and applicable consent.

References:
- https://developers.google.com/webmaster-tools/v1/how-tos/authorizing
- https://developers.google.com/webmaster-tools/v1/searchanalytics/query
- https://developers.google.com/oauthplayground/
- https://support.google.com/cloud/answer/15549945
