---
title: Works shared hero product correction
date: 2026-10-07
status: approved
---

# Works Shared Hero Product Correction

## Scope

- Correct distorted central portable power station in works-v3.png with built-in image_gen.
- Keep scene, pedestals, surrounding products, packaging and airy composition.
- Use portable-power-station-v1.webp as reference for credible chassis, handle and aligned operation-panel ports.
- New versioned shared asset works-v4.webp; previous image retained.
- Two consumers: works hero and services development-works CTA. No other application behavior changes.
- All 18 works support-area headings changed from 対応範囲 to 対応領域; icon labels and data unchanged.
- Services image dimensions updated to actual 1670 x 942.

## Prompt

Edit only the central power station. Use reference product geometry: broad flat front panel, aligned LCD/USB/DC and two AC sockets, symmetric handle, consistent perspective and clean seams. Preserve surroundings, lighting, product pedestal placement and left headline space; no logos or labels.

## Verification

- Build, internal link audit and diff check passed. Local desktop and 390px mobile hero load correctly without horizontal overflow; services CTA loads the same asset.
- Independent read-only reviewer: 01a113b6-38b6-7d21-bb50-f59623b4c9f7, nickname Beauvoir, alias hirame. No mandatory findings; geometry and two-consumer integration checked.
- Screenshot: /tmp/inbes-works-hero-v4-local.png.
- User approved scoped confirmation-site publication with "公開承認します" after the hero/exception confirmation request. Exception05 applies to this release only; previous exception04 is not reused.
- No reusable Knowledge Candidate: product-specific image correction only.
- Final read-only reviewer: 01a113ba-2d41-7e23-9751-430712ada748, nickname Pasteur, alias hirame; no blockers. Includes support-heading rename and services actual image dimensions.
- Final build, links, Knowledge Validator and diff checks passed. Built output contains all 18 対応領域 headings and both shared asset references.
