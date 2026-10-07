---
title: Works product image revisions
date: 2026-10-07
status: approved
---

# Works Product Image Revisions

## Scope

- 11 versioned replacement assets, 1200 x 800 WebP (3:2).
- Cooking pot: user-supplied image copied unchanged.
- Other replacements: built-in image_gen, reference-based anonymous product images.
- Existing 18 cases, copy, support icons and category labels unchanged.
- Subsequent user instruction: remove the visible AI-image disclaimer from the introductory section before publication. Product titles, scopes and case descriptions remain unchanged.
- Subsequent user instruction: remove only image-frame borders on works; retain each case container border. This is scoped to .work-visual, not shared MediaFrame.
- Display-only scale: action camera and low-cost recorder 0.7, mirror recorder 0.85, compact recorder 0.6. Original files retained; exact scaling in CSS.
- Dryer: hose shown attached to the top outlet to clarify matching geometry.
- AI images are illustrative, not engineering drawings or proof of exact product specifications. Battery LED layout and other small details may differ from actual products.

## References

- [Mobile battery](https://inbes.jp/cubele/cmob105/)
- High-pressure hose: user-supplied PWH15 leaflet.
- [Translator](https://inbes.jp/products/talkbot/)
- [Dryer](https://inbes.jp/products/md1500/)
- [Rice containers](https://keeppot.com/): 7L and 13L together.
- SD recorder, holder, DIN audio and cooking pot: user-supplied images.
- [Rear-camera recorder](https://inbes.jp/products/idr06r/): geometry reference only; existing portfolio title retained, not a specification claim for IDR-06R.

## Generation Prompts

### semi-solid-mobile-battery

Output: `public/assets/works/semi-solid-mobile-battery-v2.webp`

```text
Use case: product-mockup. Generate ONE photorealistic catalog photo of a logo-free semi-solid mobile power bank closely matching the shape of the device in the supplied reference image. Reference image is for PRODUCT GEOMETRY, COLOR and PORT LAYOUT only, not its background or printed branding. Landscape exactly1536x1024, aspect ratio3:2. Single ultra-thin charcoal graphite gray flat rounded rectangular power bank, proportions114mm long x74mm wide x9.2mm thick; broad nearly flat matte subtly textured front face, gently roundedcorners, thin rounded dark side band. One small black USB-C connector centered along the near SHORT bottom edge. Along the visible RIGHT long edge near the bottom: one small rounded rectangular flush powerbutton with four tiny white LED dots beneath it toward the bottom. No USB-A, no multiple USB connectors. Three-quarter elevated view with long axis diagonally from lowerleft toward upperright, resting naturally on a pure white seamless studiofloor. Front broadface prominent; shortbottomedge and rightlongedge visible. Whole device fully visible and centered, product occupiesabout70% canvaswidth, ample white safe margin all edges. Soft studio light and subtle palegray natural contactshadow. Match charcoalgray color and thin credit-card-like rectangular outline ofreference. Remove ALL logos and words from device: no Cubele, no INBES, no modelname, no specifications, no capacitytext, no symbols or label text. No cables, packaging, hands, scenery, additional devices or illustration overlays. Consistent premium clean studio product photography with other anonymous worksportfolio images. Do not make white, silver, long narrow bar or chunky powerbank.
```

### high-pressure-hose

Output: `public/assets/works/high-pressure-hose-v2.webp`

```text
Use case: product-mockup. Create ONE landscape1536x1024px 3:2 clean photorealistic studio catalog photo of a complete unbranded expandable garden washing hose kit closely matching the PWH15 products in the two supplied leaflet images. Use the product photos specifically in the bottom-left ACCESSORIES panel on page2 for exact contents, and large nozzle images page1/page2 for geometry. Ignore all text logos graphic backgrounds cars water arrows marketing claims and cutaway diagrams. White seamless studiofloor/backdrop, softlight and subtle natural contactshadows, generous safe margins, all items entire and visible. Composition: large black angled cylindrical spray nozzle foreground left, BLACK braided expandable fabric hose neatly coiled to its right/behind with golden brass quick-connect couplings at both ends, accessories laid out orderly in foreground right: ONE transparent cylindrical soap bottle with black inline connector/valve head (same leaflet), THREE small golden brass faucet nipple/adapter pieces and ONE small silver metal hoseclamp, ONE black semi-transparent mesh drawstring storage pouch in the rear. Match the nozzle: long smooth matteblack slightly angled metal body with slim integrated trigger beneath bend, rotating BLACK ribbed cylindrical spraytip with small silver circular inner face and black center, gold brass coupling only at base; NOT gold spraytip, NOT pressurewasher pistol, no pump/motor. Keep hose fabric darkblack and compact coiled. No loose unrelated tools, buckets, extra fittings, clothing, hands or people. All printedlogos and branding removed. Photographic natural materials not an infographic, no text titles/borders/labels/arrows/watermarks. Product arrangement fills72-80% canvas width, no item cropped. Wholekit is the subject, not nozzle-only.
```

### bidirectional-translator

Output: `public/assets/works/bidirectional-translator-v2.webp`

```text
Use case: product-mockup. Create a single 1536x1024 landscape 3:2 photorealistic white seamless catalog photo of one logo-free handheld translator closely matching supplied reference. Preserve tall rounded black rectangular body, width50 height133 thickness14mm, portrait screen occupying upperfront, four square colored tiles in2x2 arrangement blue speech upperleft green camera upperright yellow microphone lowerleft gray gear lowerright; icons only no text. Below screen blank black strip where talkbot logo was: REMOVE logo completely. Belowstrip a wide dark horizontal panel with central rounded-square black button and silveroutline, green dash left and blue dash right. Bottom front has broad round perforated speakergrille as reference. Three-quarter slightly elevated view, upright whole product centered and standing naturally on shortbottomedge, frontvisible and thin rightedgevisible, product fills78% canvasheight with amplewhite margins. Soft premium studiophotography/contactshadow. No logos brandnames letters words watermark cables hands other objects. Match reference geometry not generic smartphone.
```

### multi-dryer

Output: `public/assets/works/multi-dryer-v2.webp`

```text
Use case: precise-object-edit. Correct supplied photo by showing hose ATTACHED to dryer top, so connection compatibility is visually obvious. Preserve exact white slenderdryer body twofrontbuttons bottomvents blackcord and purewhitebackground. Move hose right-end onto topoutlet. Applianceend is a short slim elongatedroundedrectangular cuff fitting flush OVER topbodyoutlet and smalllatch, SAME68x56 crosssection, no gaps and no lateral holes. Corrugated hose emerges vertically from cuff on top then arches left and curvesdown so openoval nozzle faces lowerleft. Dryer remainsupright center-right, hose risesabovebody and extendsleft. Completeassemblyfullywithin landscape1536x1024 3:2 frame, generousmargins; reduce entireassemblysizeonlyenoughtofit. Soft natural studio lighting. No logos text otherproducts. Veryimportant cuff on BODY TOP not bottomside and physicallyattached, not separatehose. Do not invent anextra sidewayshole. Original reference geometry remainsrecognizable.
```

### vacuum-rice-container

Output: `public/assets/works/vacuum-rice-container-v2.webp`

```text
Use case: product-mockup. Generate one landscape1536x1024 aspect3:2 white seamless studio photograph of TWO unbranded automatic vacuum food/rice storage containers closely matching supplied officialproductreferencephotos. Image1small7L and image2large13L establish exact exterior: both same278mm diameter, small199mm totalheight, large299mm totalheight, smooth matte ivorywhite cylindricalbody with gently roundedbottom, broad shallow flatwhite circular vacuumlid slightlyoverhanging body and thin gray/silverseam underlid. Image3 establishes lidcentral recessed circular vacuumvalve and flushfoldinghandle/controlsurface. Remove ALL Hi-Rose and KEEPPOT brandtext completely, no labelorletters. Showsmallfrontleft largebackright sidebyside withoutoccludingeither, on SAME floor, correctequaldiameter small2/3heightlarge, slightlyelevated threequarterview showing tops, lidsclosed. Fill75%canvaswidth withgenerousmargins, completeproducts visible. Soft studiophotography realistic palecontactshadows premium neutralwhite materials. No kitchen props handsfood labelspackagingbackgroundscenes, no squarebox ricebin, these are CYLINDERS asreferences.
```

### sd-recorder

Output: `public/assets/works/sd-recorder-v2.webp`

```text
Use case: precise-object-edit. Edit reference 3:2 productphoto. Preserve camera and recorder physical proportions/relative size asprovided. LEFT white bulletsecuritycamera must be configured for CEILING mounting: circular mountingbase abovebody, supportstem descendsfrombase to pivot atop/back camera, body hangs UNDER it pointedslightlydown forwardright. No baseunderbody. A smallwhitehorizontal ceilingplane mayappear atbase tocommunicate ceilingattachment, otherwise isolatedon whitebackground. Entiremount fullyvisible notcropped. RIGHT recorder retains lowrectangulardimensions, SDslotblack, threeLEDs blackfeet sideventholes, but replace blackcase with silver brushedALUMINUM METAL enclosure topfrontandsides; realisticmetal highlightsonedges. Camera stayswhite withblackroundlensface/sunhood. Cleanstudio light/contactshadow recorderfloor. No logoswords overlays cablesotherproducts. White1536x1024canvas.
```

### mobile-holder

Output: `public/assets/works/mobile-holder-v2.webp`

```text
Use case: product-mockup. ONE1536x1024 landscape3:2 photorealistic white seamlessstudio productphoto showing EXACTLY THREE distinct unbranded car mobilephoneholder designs. Reference image establishes matteblackmaterial and firstdesign geometry. LEFT suctioncup dashboard/windshield articulatedarm clamp cradle, rectangularbackplate sideclamps bottomsupportfeet matchreference. CENTER compact airvent-mounted mechanicalclamp holder with short hooked ventclip visible behind, sideclamps lowerfeet, no suctioncup. RIGHT circular magneticphoneholder darkdisc with subtlegray metalring onadjustable shortballjoint/smallflatadhesivedashboardbase, no clamp. Arrange allthree inrow with balancedspacing noneoverlap eachphysicallycredible and completevisible at similarrealistic scale, lefttaller centerandrightsmaller. Threequarterfrontviews with attachmentstructuresvisible, generousmargins fills80%width. No phonesbrandslogosletterswordswatermarks cablespackaging extraobjects. Softstudiolight subtlefloorcontactshadows crisprealistic plastic rubber metal, no infographics.
```

### display-audio

Output: `public/assets/works/display-audio-v2.webp`

```text
Use case: product-mockup. Single unbranded DIN socket car stereo displayaudio headunit isolatedon white studiofloor landscape1536x1024 aspect3:2. Reference2 defines integrated7inch DOUBLE-DIN design: broadlandscape blacktouchscreenfront, leftverticalcontrolstrip withsmallbuttons and a restrainedsilverknob; substantial silvergalvanizedmetal rectangular rear DINchassis depthvisible inthreequarterfrontLEFTview, perforatedmountingscrewholes and sideventslots. Frontpanel sizedfitstandard2DIN carslot, NOT tabletwithfreestanding suctionmount ordashboardstand. Screen showsgeneric coloredmusic radio navigation phoneicon tiles WITHOUT wordslogosknownappicons, simpledarkblueblackUI. Reference1onlysupportsmetalrearchassisconstruction ignoreGoogleAndroidbrandicons. Completeheadunitcentered occupying70%width, allrearcase edges visible, naturalsubtlecontactshadow crispsoftlight. NOremote hands cars dashboard advertisetextletterswatermarksmodelnames cablesorpackaging. Productphoto notad.
```

### rear-camera-drive-recorder

Output: `public/assets/works/rear-camera-drive-recorder-v2.webp`

```text
Use case: product-mockup. Generate landscape1536x10243:2 catalogphoto of an unbranded front-and-rear dual dashcam set basedonreference IDR06R geometry but slightmodelvariant. Main widehorizontal slatecharcoal rectangularbody modestroundedcorners, centervertical mounting/lensassembly with squarefrontlenssurround circularlens, compactupperadhesivebracket attachedonhinge above, recessedtopports. Smallrearcam besideleft withblackroundedrectangularbody lens front and short tiltableadhesivebracket. Main about3timesrearwidth, bothcompletevisible withspacing, elevatedthreequarterFRONT view, NOmirror or largefloatingdisplay. Slightlysoftenfrontcorners and modernizehousingedge whilekeepingrecognizable shape. No INBES no IDR06R no brandnames labels numbers or circletext aroundlens; anonymousOEMmodel. Purewhite seamlessstudiofloor softnaturallight subtlecontactshadows, centeredset fills70%width amplemargins, nopeoplecars cablespackagingwatermarks.
```

### construction-site-camera

Output: `public/assets/works/construction-site-camera-v2.webp`

```text
Use case: product-mockup. Create ONE1536x1024 landscape3:2 white seamless studio productphoto of an unbranded rugged trailcamera usedforunpoweredconstructionsitemonitoring. NOT dome/PTZ or bulletsecuritycamera. Vertical compact rectangular olivegray/charcoal waterproofbox withsoftroundededges and moldedprotectiveribs, hingedfrontpanel and strong sidelatches, integratedsmallblackcamera lens uppermiddle, broad arraydark infraredLEDwindowsabovelens, a frosted PIR motionsensorwindow belowlens, weatherproof seam and rear strapmountingloops visible alongside, subtle industrialrubber/plastictextures. No camo/forest pattern, nologo letterswords modelnames antenna orsolarpanel. Uprightcentered threequarterfrontview withrightsidevisible fullyvisible fills72%height withgenerouswhitemargins, softstudiolight realisticfloorcontactshadow. Productappearance is trailcamera/wildlifecamera but neutralindustrialfinish nottoy. No scenery trees people cablesbatteriesorpackage.
```

## Release Boundary

Prepared for existing Cloudflare Pages staging only. No DNS, access-policy, form routing or production changes. Review and scoped naming exception are required before release. Rollback: preceding approved snapshot 4c138bb.

## Verification

- Staging build, internal link audit, git diff --check and Knowledge Validator: PASS.
- Independent Reviewer: 01a11398-ba45-7150-9cac-d875f30feb4e, actual nickname Feynman, requested alias hirame (alias_only). No implementation participation. No mandatory implementation or image defects found.
- Disclaimer-removal delta reviewer: 01a113aa-a015-72f3-9fd5-015e3373c4f7, nickname Cicero, alias hirame (alias_only); read-only, no findings. Rebuild and internal link audit passed after removal.
- Image-border delta reviewer: 01a113ab-b99f-7c21-8684-2e5eb5098543, nickname Copernicus, alias hirame (alias_only); read-only, no findings. Shared component and case borders unchanged. Build and link audit passed.
- Browser: all 18 images load at 1200 x 800. 390/768/1024/1440px checked without horizontal overflow; scaled images preserve frame dimensions.
- Cooking pot source and installed file SHA-256 both ff2921e479eaa55fcc8fe3a63e93b7db55123e7527468942350198921ae0cdba. Exact copy verified after review.
- User explicitly requested publication of this revision batch and approved the scoped naming exception on 2026-10-07 with "承認します". Exception INBES-STAGING-20261007-03 applies only to this release; previous exceptions are not reused.
- No reusable Knowledge Candidate: this work records product-specific image revisions, not a new general pattern.
