# Dependency Vulnerability Inventory

- Date: 2026-07-22
- Repository revision: `ff4eb1ee5b48da1608c06e89239273cb61cf3e70`
- Scope: `package-lock.json`
- Mode: 洗い出しのみ。依存関係の更新やコード修正は行っていない。

## Summary

`npm audit` では21件のアドバイザリが、4つの影響パッケージにまとめられている。

| Severity | Count |
| --- | ---: |
| High | 5 |
| Moderate | 12 |
| Low | 4 |
| Total | 21 |

| Package | Current version | Relation | Advisory count |
| --- | --- | --- | ---: |
| `astro` | `4.16.19` | 直接依存 | 16 |
| `vite` | `5.4.21` | Astroからの間接依存 | 3 |
| `esbuild` | `0.21.5` | Astro / Viteからの間接依存 | 1 |
| `sharp` | `0.33.5` | Astroからの間接依存 | 1 |

GitHub Dependabot REST APIが現在返すOpen Alertは1件（`#17 / GHSA-jrpj-wcv7-9fh9`）、Malware Alertは0件である。Push時の21件表示は `npm audit` が返す21アドバイザリと重大度別件数が一致する。GitHub Alertの個別登録数とは現時点で差があるため、同一指標として扱わない。

## Current Product Surface

現行のAstro構成は静的出力である。

- SSR adapterなし
- Middlewareなし
- Server Islandsなし
- Cloudflare adapterなし
- Astro Image / Pictureの利用なし
- View Transitionsなし
- `define:vars`なし
- 本番はmixhostから生成済みHTML / CSS / JavaScriptを配信する想定
- Node.js / Astro / Viteはビルド時と開発時にのみ使用する

このため、現在の公開サイトにNode.jsランタイム由来の脆弱性がそのまま配信される構成ではない。一方、開発サーバーをLANへ公開する場合と、将来の画像処理パイプラインは別の露出面として扱う。

Repository rootに `SECURITY.md` はない。開発サーバーの公開範囲や、CMS由来アセットを信頼するかといった運用境界は未定義である。

## Advisory Inventory

`not_actionable` は「現行コードと想定本番構成で対象機能または実行面がない」ことを示す。`needs_review` は運用条件によって露出可能性が変わるものである。

| No. | Severity | Package / Advisory | Current relation | Preliminary verdict |
| ---: | --- | --- | --- | --- |
| 1 | Moderate | `astro` [GHSA-5ff5-9fcw-vg88](https://github.com/advisories/GHSA-5ff5-9fcw-vg88) | Host headerを扱うSSRランタイムがない | `not_actionable` |
| 2 | Moderate | `astro` [GHSA-hr2q-hp5q-x767](https://github.com/advisories/GHSA-hr2q-hp5q-x767) | Middlewareとヘッダー判定を使っていない | `not_actionable` |
| 3 | High | `astro` [GHSA-wrwg-2hg8-v723](https://github.com/advisories/GHSA-wrwg-2hg8-v723) | Server Islandsを使っていない | `not_actionable` |
| 4 | Low | `astro` [GHSA-x3h8-62x9-952g](https://github.com/advisories/GHSA-x3h8-62x9-952g) | 開発サーバーを `--host` でLAN公開する場合は別途確認が必要 | `needs_review` |
| 5 | Moderate | `astro` [GHSA-fvmw-cj7j-j39q](https://github.com/advisories/GHSA-fvmw-cj7j-j39q) | Cloudflare adapterと `/_image` を使っていない | `not_actionable` |
| 6 | Moderate | `astro` [GHSA-ggxq-hp9w-j794](https://github.com/advisories/GHSA-ggxq-hp9w-j794) | Middleware認証を使っていない | `not_actionable` |
| 7 | Moderate | `astro` [GHSA-whqg-ppgf-wp8c](https://github.com/advisories/GHSA-whqg-ppgf-wp8c) | Middleware認証を使っていない | `not_actionable` |
| 8 | Low | `astro` [GHSA-g735-7g2w-hh3f](https://github.com/advisories/GHSA-g735-7g2w-hh3f) | Remote image allowlistを使っていない | `not_actionable` |
| 9 | Moderate | `astro` [GHSA-j687-52p2-xcff](https://github.com/advisories/GHSA-j687-52p2-xcff) | `define:vars`を使っていない | `not_actionable` |
| 10 | Low | `astro` [GHSA-xr5h-phrj-8vxv](https://github.com/advisories/GHSA-xr5h-phrj-8vxv) | Server Islandsを使っていない | `not_actionable` |
| 11 | High | `astro` [GHSA-8hv8-536x-4wqp](https://github.com/advisories/GHSA-8hv8-536x-4wqp) | 外部入力から動的なslot名を生成していない | `not_actionable` |
| 12 | High | `astro` [GHSA-2pvr-wf23-7pc7](https://github.com/advisories/GHSA-2pvr-wf23-7pc7) | Host headerを受けるAstro本番サーバーがない | `not_actionable` |
| 13 | Moderate | `astro` [GHSA-jrpj-wcv7-9fh9](https://github.com/advisories/GHSA-jrpj-wcv7-9fh9) | Astro markupで動的な属性名のspreadを使っていない。GitHub Alert `#17` | `not_actionable` |
| 14 | Moderate | `astro` [GHSA-4g3v-8h47-v7g6](https://github.com/advisories/GHSA-4g3v-8h47-v7g6) | View Transitionsを使っていない | `not_actionable` |
| 15 | Moderate | `astro` [GHSA-f48w-9m4c-m7f5](https://github.com/advisories/GHSA-f48w-9m4c-m7f5) | 動的なspread属性名を使っていない | `not_actionable` |
| 16 | Low | `astro` [GHSA-7pw4-f3q4-r2p2](https://github.com/advisories/GHSA-7pw4-f3q4-r2p2) | Hydrated Islandsの `transition:*` を使っていない | `not_actionable` |
| 17 | Moderate | `esbuild` [GHSA-67mh-4wv8-2f99](https://github.com/advisories/GHSA-67mh-4wv8-2f99) | esbuildの開発サーバーを直接起動していない | `not_actionable` |
| 18 | High | `sharp` [GHSA-f88m-g3jw-g9cj](https://github.com/advisories/GHSA-f88m-g3jw-g9cj) | Astro Image / Pictureを使っておらず、現在は画像処理パスがない | `not_actionable` |
| 19 | Moderate | `vite` [GHSA-4w7w-66w2-5vf9](https://github.com/advisories/GHSA-4w7w-66w2-5vf9) | Vite開発サーバーをLAN公開する場合は別途確認が必要 | `needs_review` |
| 20 | Moderate | `vite` [GHSA-v6wh-96g9-6wx3](https://github.com/advisories/GHSA-v6wh-96g9-6wx3) | Windows UNC path固有。現在のMac開発 / Linux CIに該当しない | `not_actionable` |
| 21 | High | `vite` [GHSA-fx2h-pf6j-xcff](https://github.com/advisories/GHSA-fx2h-pf6j-xcff) | Windows alternate path固有。現在のMac開発 / Linux CIに該当しない | `not_actionable` |

## Preliminary Queue

### Needs Review

1. Astro開発サーバーのArbitrary Local File Read。実際に `--host` で公開する際の到達可能性を確認する。
2. Vite開発サーバーのoptimized deps source map path traversal。LAN公開時の到達可能性を確認する。

### Future Review Trigger

- Astro Image / Picture、またはmicroCMS由来画像をSharpで処理する構成を導入するとき
- SSR adapter、Middleware、Server Islands、Cloudflare adapterを導入するとき
- Windows環境を公式な開発環境に加えるとき
- 開発サーバーをLANやインターネットへ公開するとき

## Proof Gaps

- Repository rootの `SECURITY.md` がない
- 開発サーバーを共有する際のネットワーク信頼境界が文書化されていない
- GitHubのPushメッセージと21件、Dependabot REST APIのOpen Alert 1件の表示差について、GitHub側の内部集計タイミングは確定できない
