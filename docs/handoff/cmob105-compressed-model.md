# CMOB105 compressed model adoption — 2026-10-24

Owner approved publishing the visually accepted local prototype. Publish the
fixed new model URL first, then atomically switch only the existing product PHP:

- New asset: `/assets/models/cmob105-meshopt-20261024.glb`
- Bytes: 5,344,408; SHA256 `37a6ccb115c24b3aee2cdbf5da265f9d67128b4947721409cf1df9ea056a9281`
- Old model remains `/assets/models/demodata_cuble.glb`, 11,020,600 bytes, for
  cached old HTML and rollback. Never overwrite/delete it in this release.

Change the unsupported `ModelViewerElement.meshoptDecoder = decoder` assignment
to `ModelViewerElement.meshoptDecoderLocation = "/js/meshopt_decoder.js"` before
creating the viewer, then use the new asset URL. The existing viewer and decoder
files are retained and checked against their prototype hashes. All other camera,
lighting, size, scrolling, auto-rotation and AR settings remain unchanged.

Prototype independent review verified the same549meshes/nodes,9materials,
vertex attributes, oriented triangles and PNG pixels. All3fixed comparison
screenshots were identical. This is raw file51.5% reduction, not a field timing
or FPS guarantee. Actual iOS/Android native AR remains untested.

Release only the new model and the two-line legacy PHP edit. Keep private NAS
backups and fixed hashes. Confirm new target absence and original page/asset,
viewer/decoder/root/modern-contact source consistency. Stage privately, verify
SHA/mode, publish the new asset, verify its public hash, then switch the page.
Use GET-only browser checks on the actual page for successful model loading,
correct new URL, rotation/orbit interaction and desktop/mobile viewport.
Do not submit a contact form or change unrelated assets/settings.

Rollback restores the original product page first and confirms the old source
URL. Retain both valid model files: already-open or cached new HTML may still
request the new URL. Any later orphan-asset cleanup is separate, after cache use
is assessed. Preserve intervening changed files and all journals/pending ownership
records; never overwrite them on a failed re-run. No broad purge/directory deletion.
