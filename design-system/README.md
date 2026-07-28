# INBES Web Design System

このディレクトリは、INBES Webサイトのデザイン判断を共有・検証するための独立した作業領域である。

Brand OSをWebの具体的な値、部品、パターンへ変換し、ブラウザ上で確認する。承認済みの内容はAstro実装へ移し、当ページを設計判断の確認面として維持する。

## Preview

- Current version: `v0.4.0`
- Source: `design-system/site/`
- Public preview: `https://hactac.github.io/inbes-web-renewal/design-system/`
- Local preview: `python3 -m http.server 4173 --directory design-system/site`
- Icon sprite: `npm run build:design-icons`

`main`へのpush時に、Astroの確認サイトとともにGitHub Pagesへ公開する。

## Source Of Truth

- Brand OS: `HACTAC/INBES-BrandOS`
- Last synchronized decision: `D-0007-web-foundation-approval`
- Local reference: `references/INBES-BrandOS/`
- Handoff: `references/INBES-BrandOS/prompts/design-system-handoff.md`
- Moodboard: `site/assets/inbes-moodboard-typography-v1.png`
- Primary logo: `site/assets/brand/inbes-logo-horizontal.svg`
- Positive symbol: `site/assets/brand/inbes-symbol-positive.svg`
- Negative symbol: `site/assets/brand/inbes-symbol-negative.svg`
- Adobe Fonts kit: `kkr3cfh`

ローカルのBrand OS参照データはGit管理対象外であり、本ディレクトリにはWebレビューに必要な成果物だけを置く。

## Design Status

デザインシステム内の値は、次の3段階で扱う。

| Status | Meaning |
| --- | --- |
| `Confirmed` | Brand OSまたは決定記録で確定している |
| `Proposed` | Web実装に必要な提案値。実ページで検証する |
| `Review` | 元データ、権利、配信条件などの確認が必要 |

暫定値を確定事項として扱わない。変更時は画面内のステータス、当README、必要に応じてBrand OSの決定記録を同時に更新する。

## Relationship With Brand OS

Brand OSとWeb Design Systemは一つの文書やリポジトリへまとめず、次の責務で連携する。

1. Brand OS: 思想、言語、視覚原則、全媒体に影響するブランド判断の正本
2. Web Design System: Brand OSをWeb用の値、部品、状態、レスポンシブ規則へ変換する層
3. Astro実装: 承認済みのDesign Systemをページとコンポーネントに適用する層

全媒体に影響する変更はBrand OSのDecision Logから更新する。Web固有の値や振る舞いは当ディレクトリで検証し、承認後にAstro実装へ反映する。

## Implementation Status

`v0.4.0`のFoundationと共通UIは、2026-07-29にAstro全ページへ適用した。

- Tailwind theme: `tailwind.config.mjs`
- Base styles: `src/styles/global.css`
- Header / Mobile Navigation / Footer: `src/layouts/BaseLayout.astro`
- Form / states: `src/components/ContactForm.astro`
- Icon: `src/components/Icon.astro` と `public/assets/icons/iconoir.svg`
- Logo / Symbol: `public/assets/brand/`

今後、Design Systemの確定値を変更する場合は、Brand OS、当ページ、Tailwind theme、Astroコンポーネントを同じ変更単位で更新する。

## Included

- ロゴ、シンボル、Brand premise、ムードボード
- 色、書体、余白、グリッド、角丸、写真比率
- Button、Link、Navigation、Form、Tag、Accordion、Overlay、Card
- Hero、Localization Process、Case Study、CTA
- 商品化支援／自社製品の共通Header、右ドロワー型Mobile Navigation、Footer
- ファイル添付を含むFormとSending、Success、Warning、Error状態
- ページ別適用表
- Desktop、Tablet、Mobileの差分
- アクセシビリティ確認項目
- Brand OSの未確定事項

## Visual Principle

情報を飾るのではなく、理解しやすく整える。

白、黒、グレー、余白、グリッドを基本とする。ロゴ赤とWeb UI用の赤を分け、サイトではロゴより少し濃い赤を限定的に使用する。写真は製品、工程、利用場面の実在感を示す証拠として扱う。

現行の基準:

- 本文: `18px / line-height 1.6`
- 見出し: `H1 40・52・64px / H2 32・36・40px / H3 22・24・26px`
- Container: `1200px max`
- Breakpoints: `768px / 1024px / 1280px`
- Grid: `4 / 8 / 12 columns`
- 角R: `4px / 8px / 12px`
- 写真比率: `3:2`を基本とする
- アイコン: `Iconoir Regular / 24px grid / 1.5px stroke / 20・24・32・48px`
- Logo Red: `#E60012`（支給ロゴデータから確定）
- Site Primary Red: `#C8101E`
- Deep Red: `#990D17`
- Pale Red: `#F7E8EA`
- Success: `#1F684B / #EAF4EF`
- Warning: `#805400 / #FFF4D8`
- Error: `#A51D2A / #FBEAEC`
- Header CTA: 「お問い合わせ」はRed solid（`#C8101E`）

避ける表現:

- 赤を広い面で使う
- 未来感だけをつくるグラデーションや発光
- ガジェット感、強い営業感、擬似的な高級感
- 情報を分断する過剰なカード化
- 意味を持たない装飾やアニメーション

IconoirはPhosphorと比較し、単一のRegularスタイルで線幅と形状を固定しやすく、現在の静かで精密なWeb表現に適しているため採用した。使用アイコンだけをローカルSVGスプライトへ生成し、ライブラリ全体は配信しない。Solidや他ライブラリは混在させず、必要な図柄がない場合は同じ24pxグリッドと1.5px線幅で追加する。

横組みロゴをWebサイトの基本署名とし、ポジ／ネガのシンボルはファビコンや表示面積が限られる場所に使用する。ロゴデータの変形、色変更、文字組みの再構成は行わない。

Webでの横組みロゴは幅104pxを最小、120–160pxを推奨とする。アイソレーションはシンボル高の1/4以上を確保する。シンボル単体は24px以上とし、社名の識別が必要な場所では横組みロゴを優先する。

写真は3:2を基本とし、製品カードと事例一覧ではモバイルでも比率を維持する。Heroのみ4:5を許容し、被写体位置を画像ごとに指定する。中央トリミングで製品、手元、顔が欠ける場合は、モバイル用画像を別途用意する。

商品化支援側と自社製品側のHeaderは同一構造とし、ロゴ横の相互リンク、ローカルナビ、右端のCTAだけを切り替える。モバイルはロゴと現在モードを残し、右から開くMenu Drawerへナビゲーションを収納する。

## Review Before Production

- 単色表示が必要になった場合の正式データ
- 本番写真素材の使用許諾と被写体ごとのトリミング
- Adobe Fontsの本番公開ドメイン設定
- 主要ブラウザ・実機でのレスポンシブ確認

Foundationと共通UIはAstroへ適用済みである。実画像を入れた段階で、被写体ごとのトリミング、文字量、CTAの視認性をページ単位で再確認する。
