# INBES Web Design System

このディレクトリは、INBES Webサイトのデザイン判断を共有・検証するための独立した作業領域である。

Astroサイトへ適用する前に、Brand OSをWebの具体的な値、部品、パターンへ変換し、ブラウザ上で確認する。現段階では既存ワイヤーフレームの見た目へ自動適用しない。

## Preview

- Source: `design-system/site/`
- Public preview: `https://hactac.github.io/inbes-web-renewal/design-system/`
- Local preview: `python3 -m http.server 4173 --directory design-system/site`

`main`へのpush時に、Astroの確認サイトとともにGitHub Pagesへ公開する。

## Source Of Truth

- Brand OS: `HACTAC/INBES-BrandOS`
- Local reference: `references/INBES-BrandOS/`
- Handoff: `references/INBES-BrandOS/prompts/design-system-handoff.md`
- Moodboard: `site/assets/inbes-moodboard-typography-v1.png`
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

## Included

- Brand premiseとムードボード
- 色、書体、余白、グリッド、角丸、写真比率
- Button、Link、Navigation、Form、Tag、Accordion、Overlay、Card
- Hero、Localization Process、Case Study、CTA
- ページ別適用表
- Desktop、Tablet、Mobileの差分
- アクセシビリティ確認項目
- Brand OSの未確定事項

## Visual Principle

情報を飾るのではなく、理解しやすく整える。

白、黒、グレー、余白、グリッドを基本とする。赤はロゴ、現在地、短い強調線へ限定する。写真は製品、工程、利用場面の実在感を示す証拠として扱う。

避ける表現:

- 赤を広い面で使う
- 未来感だけをつくるグラデーションや発光
- ガジェット感、強い営業感、擬似的な高級感
- 情報を分断する過剰なカード化
- 意味を持たない装飾やアニメーション

## Review Before Application

- Logo Redの正式なRGB / HEX
- 正式ロゴとアイソレーション
- Wine RedをUIへ採用するか
- 写真素材の使用許諾と標準トリミング
- Success、Warning、Errorの状態色
- Adobe Fontsの本番公開ドメイン設定
- Container幅とbreakpointsの代表ページ検証

承認後は、FoundationをTailwind themeへ、ComponentsをAstroコンポーネントへ段階的に移す。最初の適用対象は商品化支援ページとする。
