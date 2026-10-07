---
title: Compact dashcam integrated mount image
date: 2026-10-07
status: approved
---

# Compact Dashcam Integrated Mount Image

## Scope

- Replace compact-drive-recorder-v1 image with versioned v2, 1200 x 800 WebP.
- Integrated windshield adhesive plate with short hinge instead of previous mounting loop.
- Landscape body proportions assume space for approximately 2-inch rear LCD; image is a design illustration, not a verified specification drawing. Screen is not on the front.
- Preserve imageScale 0.6, case title/copy/category/support icons and image-frame dimensions.
- Source geometry references: compact-drive-recorder-v1.webp and left camera in rear-camera-drive-recorder-v3.webp. Old files retained.

## Generation Prompt

Edit the compact camera into a credible compact dashcam with short integrated hinged windshield adhesive plate like the left reference camera. Replace mounting loop. Landscape body approximately 65 x 45 x 30 mm, assumed 2-inch rear LCD; modest front lens, side vents and port. Single product, elevated front three-quarter view, matte charcoal, white backdrop, subtle shadow, no logos or labels. 3:2.

## Approval And Verification

- User approved image with "OK", requested publication, then explicitly approved naming exception with "例外承認もします。" on 2026-10-07.
- Scoped exception06 applies to this confirmation-site publication only; production publication not authorized.
- Reviewer: 01a113be-1738-7de2-b429-4a67108ab85d, nickname McClintock, alias hirame; read-only review found no mandatory image/code defects. Reviewer environment lacked built pages and PyYAML; its link/Validator attempts were not accepted as verification evidence.
- Main verification: final staging build passed (17 pages), internal links passed (18 link types / 17 pages), Knowledge Validator passed using project validation venv, diff check passed.
- Browser: new asset loads at 1200 x 800, exact 0.6 transform retained, image border 0px / case border 1px, desktop and 390px mobile have no overflow.
- Rollback: source 71cb774e0db046096c4303165c1a1092c0a6cd7c, deployment c1f1813c.
- No DNS, permissions, authentication or form routing changes. No reusable Knowledge Candidate.
