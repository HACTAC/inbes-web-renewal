# Product background videos

The release snapshot in `deployment/video-optimization.json` records the four original public assets and four content-hashed replacements. Three product entry pages contain five references; Talkbot has both a parent video src and a child source src. Update both together.

The replacements preserve native dimensions, frame rate, presentation timestamps, frame count, pixel format, H.264 profile/level and colour metadata. Three AAC tracks are copied, with compressed payload, decoded PCM, presentation times and track duration verified; Talkbot remains without audio. The MP4 muxer normalizes only the final AAC packet duration to the pre-existing track end. Video compression is lossy: representative and worst-scoring frames were reviewed alongside full-frame SSIM/PSNR and sampled VMAF, which are verification aids rather than a guarantee of identical pixels.

The MP4 index is placed before media data for progressive playback. Autoplay, muted, playsinline, loop, controls/preload and existing poster markup are preserved. YouTube embeds and the inactive legacy lazy-loading code are separate from this change.

Future re-encoding should use new content-hashed URLs after quality and browser verification. Retain original assets and valid new replacements for rollback and already-open/cached HTML. Rollback restores guarded original page sources first. Never overwrite a binary with different bytes under an adopted content-hashed name.

Before publication verify syntax/build/links, source-only URL differences, audio/frame/colour invariants, browser metadata/playback/seek/loop on desktop and mobile viewports, HTTP MIME/Range/full hashes, independent review and the fixed upload/rollback plan. Scope uploads to the selected four new media files and three entry pages; do not mirror the site or change contact/runtime configuration. The encoding tools are offline operation tools, not website runtime dependencies.
