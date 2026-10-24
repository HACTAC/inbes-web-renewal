# Product CSS maintenance

Current legacy product stylesheets and the remaining product entry pages were captured from production before cleanup. The baseline is commit `f4206c7`; raw generated Astro CSS remains a build output. Keep modern Astro styling separate from legacy landing-page resets.

`deployment/product-css-map.json` records current stylesheets, identical-content families, page-specific extracted styles, compatibility aliases and changed entries. Shared CSS uses immutable content-hashed paths under `/assets/css/products/shared/`. Product-specific styles remain at their original locations with a content hash query in current page links when changed. Shared navigation/footer families intentionally differ.

When changing a shared stylesheet, create a new content-hashed asset and update its opt-in page links, compatibility aliases and mapping together. Preserve stylesheet order and media attributes. Do not move a stylesheet containing relative `url()` or `@import` without reviewing/rebasing every dependency. The CMOB105 template opts into shared CSS through `pageConfig.shared_css`; all other callers of the loader keep their original default paths.

Inline style blocks were extracted at their original cascade positions. Relative image URLs in extracted CSS were rebased. Repeated static HBP80 presentation was consolidated into named classes. Runtime carousel/model styles and product-specific one-off inline values remain intact. Coverage is an observation aid, not a deletion list. Only earlier identical simple declaration rules in the same supported conditional context were removed; distinct overrides remain.

Legacy font visibility now also restores on `wf-inactive`. Keep this failure fallback even when normal font loads succeed. Four stray closing braces in two stylesheets were removed together with the following rules discarded by the previous browser parser, preserving existing effective styling. Their original source remains in the baseline commit.

Before publication run:

```sh
node scripts/check-product-css.mjs
npm run build
npm run check:links
python3 scripts/test-nas-release.py
python3 .agents/tools/validate_repository.py --root .agents --knowledge-only
git diff --check
```

Also compare desktop, tablet and mobile layouts and navigation/modal/accordion states, font success/failure behavior, and the CMOB105 viewer. Do not submit contact forms as part of CSS QA. Publication uses a fixed reviewed delta with fresh source hashes, private atomic staging, originals retained outside the website, and a journal. New shared assets must be verified before switching references. On rollback restore original entry/template files then original CSS; retain valid new versioned assets for already-open/cached HTML. Never mirror-delete unrelated site assets or modify contact/runtime secrets for a CSS release.
