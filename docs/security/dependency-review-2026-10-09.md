# Dependency security review — 2026-10-09

## Scope and result

The NAS operations source was audited with `npm audit --json` (all dependency scopes, not just production packages). The previous snapshot reported 24 affected packages: critical 1, high 16, moderate 7. After the updates below it reports 7: critical 0, high 5, moderate 2. These are package counts, including transitive dependants; they are not counts of independent exploits. This is not an incident investigation or proof that the site has never been compromised.

Updated Astro 4.16.x to **7.3.8**, Tailwind 3 to **3.4.19**, PostCSS to **8.5.29**, and autoprefixer to **10.6.1**. The lockfile also incorporates compatible fixes from `npm audit fix` without `--force`. The retired Astro Tailwind integration only supports Astro 3–5; the same Tailwind and autoprefixer processing now runs directly through PostCSS. Vite 8 changes the default CSS minifier to Lightning CSS. It changed gradient stop/alpha rounding in the hero overlays; keep the supported `build.cssMinify: "esbuild"` option with patched esbuild **0.28.2** to preserve the previous rendering. [Vite migration guide](https://vite.dev/guide/migration.html#css-minification-by-lightning-css). Existing global CSS, Tailwind configuration, content, PHP and legacy overlay files are unchanged. `compressHTML: true` preserves the previous compression mode rather than adopting Astro 7's new default. Local Node must be >=22.12.0 and npm >=9.6.5; GitHub Pages uses the verified Node 22.23.2 version.

## Exposure assessment

Production consists of generated static files plus separate PHP contact processing. It does not run an Astro/Vite/Node application. The repository has no server adapter, server islands, middleware authentication, Astro Actions, uploaded-image pipeline or CMS integration. These observations explain why an audit finding is not automatically a reachable production endpoint; they do not replace package updates or cover PHP/hosting vulnerabilities.

- **Critical AVIF processing**: malicious AVIF optimisation in Astro's default Sharp image service can execute code. The official fix requires Astro >=7.2.8. The source currently uses ordinary public image paths, not `astro:assets`; however development image endpoints exist, so lack of image imports is not a sufficient reason to leave the old framework installed. Updated Astro and Sharp remove this audit finding. [Maintainer advisory](https://github.com/withastro/astro/security/advisories/GHSA-26w7-cxv4-gfx2).
- **Development-server and SSR findings**: old Astro/Vite/esbuild issues cover file access, request handling and server-rendered authentication/XSS. The current static production has no such Node endpoint. They still matter on a developer's machine, so supported framework and resolved build-tool versions were updated. Development/preview services must remain local; do not expose an old checkout's development server to the Internet.
- **Rendering/XSS findings**: a static output can still carry unsafe markup, so static hosting alone is not a blanket exemption. Content comes from tracked source/data rather than visitor input. The inline analytics `define:vars` value is a configured public measurement ID. Updating Astro addresses the reported renderer findings; analytics and generated output are regression checked.
- **Other build tools**: YAML, browser mapping, source maps, cache semantics, identifiers and serializers are used by the build chain. Compatible resolved updates remove their audit flags. The assessment assumes trusted reviewed source/configuration/assets; arbitrary imported content or new CMS/upload inputs require a fresh assessment.

## Remaining findings and decision

| Reported packages | Severity | Root issue | Current input boundary |
| --- | --- | --- | --- |
| braces, chokidar, micromatch, fast-glob, tailwindcss | high (5 package flags) | GHSA-vfj7-8cjw-p6xm, stack exhaustion from deeply nested brace patterns | Tailwind content glob is fixed in the tracked configuration; no visitor-provided glob or request-time parser |
| postcss-selector-parser, postcss-nested | moderate (2 package flags) | GHSA-rj75-hqrm-r3gf, quadratic CPU consumption on malicious selectors | CSS/classes come from tracked source, parsed during build only |

The braces registry version 3.0.3 is still affected; no patched 3.x release was available at review time. [Advisory](https://github.com/advisories/GHSA-vfj7-8cjw-p6xm). The selector parser is fixed in 7.1.6, outside Tailwind 3's declared major range. Its maintainer explicitly limits exposure to synchronous parsing of untrusted selectors and says ordinary trusted-source build use is unaffected. [Maintainer advisory](https://github.com/postcss/postcss-selector-parser/security/advisories/GHSA-rj75-hqrm-r3gf).

Retain these visible audit findings; do not suppress advisories or force incompatible dependencies to obtain a zero count. Tailwind 4 is a possible future migration with a wider CSS/reset compatibility scope. Under the current fixed, trusted build-input boundary no remote attack path was identified for these two remaining root issues. Reassess if arbitrary CSS, selectors, globs, third-party source, CMS or uploads are introduced, or a compatible patched release becomes available. This is a bounded applicability conclusion, not a claim that the packages themselves are fixed.

## Verification and release boundary

Production and GitHub Pages review builds, internal links, analytics sanitisation, deployment safety tests, mocked contact scenarios and desktop/mobile output comparison are required for this update. Exact evidence and independent-review status are in the NAS operation record. PHP sources and mail delivery are outside this dependency change; no real mail is sent. The operations branch does not trigger the existing main-only Pages deployment. Production files are not automatically overwritten by a dependency commit.

Rollback the reviewed dependency/configuration commit through a new corrective commit if needed, then `npm ci` and rebuild with the original approved public build settings. The pre-update operations source is commit `8eed077fa61f5eb28593caf88fce694ac672d525`; its private production build snapshot is retained on NAS. Do not deploy an old build or change `main` automatically. Any production artifact update uses the separate NAS plan/backup/review/approval procedure.

Upgrade compatibility references: [Astro 5](https://docs.astro.build/en/guides/upgrade-to/v5/), [Astro 6](https://docs.astro.build/en/guides/upgrade-to/v6/), [Astro 7](https://docs.astro.build/en/guides/upgrade-to/v7/).
