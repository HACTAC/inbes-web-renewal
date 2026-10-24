# Build dependency update — 2026-10-24

The current static/PHP production site does not run the Node.js build stack.
Nevertheless, npm audit identified five affected packages (two high, three
moderate) in development/build tooling. Use patched versions for that environment;
do not interpret this count as five proven vulnerabilities in deployed HTML/PHP.

Keep Astro7.3.8 and Tailwind3.4.19, with exact overrides:

| Dependency | Before | After |
| --- | --- | --- |
| undici | 8.11.2 | 8.12.2 |
| zod | 4.6.5 | 4.6.6 |
| postcss-selector-parser | 6.1.4 | 7.1.6 |

The selector parser is deliberately outside the6.x range declared by the current
Tailwind/PostCSS nested stack. Upstream v7 changes insertion safety during
iteration. For this site, all140 generated files, including CSS/JS, were compared
before/after using the same production environment and were byte-identical. This
is current-site compatibility evidence, not a guarantee for future plugins/CSS.
Revisit the override when updating Tailwind/Astro; do not blindly force Tailwind4.

Align root package/lock and README Node minimum to22.19.0, already required by
the resolved undici8.x dependency. Runtime/CI uses the tested Node22.23.2.

Checks: clean lock reinstall; npm audit0; production build18pages; internal
links19types/18pages; repository validator; diff check. No production upload is
needed for byte-identical build output. The PHP handler and secret configuration
are outside this dependency change. Previous review remains historical.

References:
- https://github.com/advisories/GHSA-xjj6-f33q-929c
- https://github.com/advisories/GHSA-mrhx-c7xc-m3w5
- https://github.com/advisories/GHSA-rj75-hqrm-r3gf
- https://github.com/postcss/postcss-selector-parser/releases/tag/v7.0.0
