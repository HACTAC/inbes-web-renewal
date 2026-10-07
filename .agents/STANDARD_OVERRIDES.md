---
title: Standard Overrides
updated: 2026-10-07
---

# Standard Overrides

## INBES-STAGING-20261007-07

- Status: `expired`（公開完了）
- 対象: 直立コーン版の工事現場向け屋外カメラ、TOPと製品一覧の画面清掃、会社概要HEROの筐体修正を確認用サイトへ公開する1回のみ。
- 例外: Reviewer表示名の指定APIがないため`alias_only`を許可する。独立レビューは省略しない。
- Reviewer: `01a115cf-a0f7-7b02-907b-3f6754f6371f`、返却表示名`Kepler`、希望alias`hirame`、実装不参加read-only。
- Human Approval: TAKIWAKI Daisuke、2026-10-07。「これで公開してください」、表示名例外の確認へ「今回の例外を承認して公開する」。同じ公開準備中に会社概要画像の追加修正を依頼。
- 期限: 今回の確認用公開完了まで。他の公開へ再利用しない。
- 安全境界: mixhost本番、DNS、認証、アクセス権、フォーム送信先は変更しない。
- 検証: ビルド、リンク、Validator、差分、独立レビュー、画像読み込みとPC・モバイル表示。
- ロールバック: commit `305395e`、deployment `973e7504`。
- 完了: 2026-10-07 19:08 JST。commit `1e69a55`、deployment `623f0497`。固定URLの4画像読み込み・ファイルハッシュ一致・noindex・実表示を確認。

## INBES-STAGING-20261007-06

- Status: `expired`（公開完了）
- 対象: 超小型ドライブレコーダーを貼付け固定部一体型・2インチ背面液晶を想定した本体形状の画像へ差し替え、確認用サイトへ公開する1回のみ。
- 例外: 表示名を指定できないためReviewerの`alias_only`を許可する。独立レビューは省略しない。
- Reviewer: `01a113be-1738-7de2-b429-4a67108ab85d`、表示名`McClintock`、希望alias`hirame`、実装不参加read-only。
- Human Approval: TAKIWAKI Daisuke、2026-10-07。「公開してください」に続き、表示名例外の確認へ「例外承認もします。」と明示承認。
- 期限: 今回の確認用公開完了まで。他の公開へ再利用しない。
- 検証: ビルド、リンク、差分、Validator、独立レビュー、公開先の画像ロード・表示サイズ・モバイル表示。
- 安全境界: 本番、DNS、アクセス権、認証、フォーム送信先は変更しない。
- ロールバック: commit `71cb774`、deployment `c1f1813c`。
- 完了: 2026-10-07 09:25 JST。commit `305395e`、deployment `973e7504`。固定URLの画像ロード1200x800、0.6表示、noindex、デスクトップ/モバイル表示を確認。

## INBES-STAGING-20261007-05

- Status: `expired`（公開完了）
- 対象: 開発実績HEROと商品化支援CTAの共通画像修正、および18事例の見出しを「対応領域」へ変更し、確認用サイトへ公開する1回のみ。
- 例外: 表示名指定APIがないためReviewerの`alias_only`を許可する。独立レビューは省略しない。
- Reviewer: HEROを`01a113b6-38b6-7d21-bb50-f59623b4c9f7`、表示名`Beauvoir`、希望alias`hirame`がread-onlyで確認、修正必須指摘なし。最終追加差分レビューを別途実施。
- 最終Reviewer: `01a113ba-2d41-7e23-9751-430712ada748`、表示名`Pasteur`、希望alias`hirame`。見出し変更と画像実寸を含む最終差分をread-only確認、ブロッカーなし。
- Human Approval: TAKIWAKI Daisuke、2026-10-07。HERO修正の確認用公開と今回の表示名例外を確認した後、「公開承認します」と明示承認。
- 期限: 今回の確認用公開完了まで。他の公開へ再利用しない。
- 安全境界: 本番、DNS、認証、アクセス権、フォーム送信先は変更しない。
- 検証: ビルド、リンク監査、Validator、差分、共通画像の読み込み、18見出し、デスクトップとモバイル表示。
- ロールバック: commit `bb71b5d`、deployment `2bddc07d`。
- 完了: 2026-10-07 09:21 JST。commit `71cb774`、deployment `c1f1813c`。固定URLで共通修正画像2か所の読み込み、対応領域18見出し、noindex、デスクトップ/390pxモバイル表示を確認。

## INBES-STAGING-20261007-04

- Status: `expired`（公開完了）
- 対象: リアカメラ付きレコーダーの名称・画像、真空米びつ白背景、ディスプレイオーディオ・振動マシン85%・ホルダー95%表示を確認用サイトへ公開する1回のみ。
- 例外: レビュー担当の表示名を指定できないため`alias_only`を許可する。独立レビューは省略しない。本番公開承認ではない。
- Reviewer: `01a113b1-30f8-7d30-9e59-e131ee5e48af`、表示名`Gibbs`、希望alias`hirame`、実装不参加read-only。
- Human Approval: TAKIWAKI Daisuke、2026-10-07。「これで公開してください。例外を承認します。」と明示承認。
- 期限: 今回の確認用公開完了まで。他の公開へ再利用しない。
- 検証: ビルド、リンク監査、Validator、差分、画像読み込み・3:2・表示縮小率・スマートフォン表示。
- 安全境界: 本番、DNS、アクセス権、認証、フォーム送信先は変更しない。
- ロールバック: commit `58b71b8`、deployment `7aa993ae`。
- 完了: 2026-10-07 09:12 JST、commit `bb71b5d`、deployment `2bddc07d`。固定URLで全18画像・v3ファイルのSHA-256一致・85%/95%表示・名称・noindex・モバイル横はみ出しなしを確認。

## INBES-STAGING-20261007-03

- Status: `expired`（今回の公開完了により失効）
- 対象: 開発実績の今回の画像改訂（11画像差し替え、4画像の表示縮小）を既存Cloudflare Pages Stagingへ公開する1回のみ。
- 例外: Reviewer表示名を指定できないため、`alias_only`の独立レビューを許可する。独立レビューは省略しない。
- Reviewer: `01a11398-ba45-7150-9cac-d875f30feb4e`、実際の表示名`Feynman`、希望alias`hirame`。実装不参加のread-onlyレビューで修正必須の不具合なし。
- 追加差分Reviewer: `01a113aa-a015-72f3-9fd5-015e3373c4f7`、表示名`Cicero`、希望alias`hirame`。公開前に依頼された注記削除をread-onlyで確認し、指摘なし。
- 枠線差分Reviewer: `01a113ab-b99f-7c21-8684-2e5eb5098543`、表示名`Copernicus`、希望alias`hirame`。同一公開作業中の画像枠線削除をread-onlyで確認し、指摘なし。
- CTA差分Reviewer: `01a113ad-eeed-7030-b722-d71590729971`、表示名`Singer`、希望alias`hirame`。同一公開作業中の商品化支援CTA共通化をread-onlyで確認し、指摘なし。
- Human Approval: TAKIWAKI Daisuke、2026-10-07。「今回限りの表示名ルール例外が承認待ち」と報告した後、「承認します」と明示承認。
- 期限: 今回のStaging公開完了まで。他の公開へ再利用しない。
- 検証: ビルド、リンク監査、Validator、差分確認、18画像の読み込みと3:2、390/768/1024/1440pxの表示、支給画像のハッシュ一致。
- 安全境界: 本番、DNS、認証、アクセス権、フォーム送信先の変更には適用しない。
- ロールバック: 直前の承認済みcommit `4c138bb`の生成物へ戻す。
- 完了: 2026-10-07 09:07 JST。画像改訂と公開作業中に依頼された注記削除・画像枠線削除・CTA統一をcommit `58b71b8`として公開。固定URLの18画像、画像枠線0px・事例枠線1px、noindex、CTA、モバイル表示を確認。

## INBES-STAGING-20261007-02

- Status: `expired`（今回の公開完了により失効）
- 対象: 開発実績18機種の生成画像を既存Cloudflare Pages Stagingへ公開する1回のみ。
- 例外: Reviewerの表示名を指定できないため、`alias_only`での独立レビューを許可する。レビューそのものは省略しない。
- 理由: 作成APIに表示名指定・変更の操作がなく、返却された表示名は`Zeno`。希望名`hirame`はaliasのみ。
- Reviewer: `01a111d3-87a4-7e70-910d-a7f4fce66c5d`。実装に参加せずread-onlyで18画像と統合を確認。修正必須の指摘なし。
- Human Approval: TAKIWAKI Daisuke、2026-10-07 08:15 JST。今回限りの例外承認を求めた質問に対し「公開承認します」と明示承認。
- 期限: 今回のStaging公開完了まで。他の公開へ再利用しない。
- 検証: ビルド、リンク監査、Validator、差分確認、18画像の読み込みと3:2、390/768/1024/1440pxで横はみ出しなし、AIイメージ注記。
- 安全境界: mixhost本番、DNS、アクセス権、認証、フォーム送信先の変更には適用しない。
- ロールバック: Stagingを直前の承認済みcommit `fa3d6f9`の生成物へ戻す。
- 完了: 2026-10-07 08:18 JST。commit `4c138bb`を公開し、固定URLで18画像の読み込み、3:2、モバイル表示、noindexを確認。

## INBES-STAGING-20261007-01

- Status: `expired`（今回の公開完了により失効）
- 対象: 開発実績18事例のCloudflare Pages Staging公開1回のみ。
- 例外: Reviewerの表示名を指定できないため、`alias_only`での独立レビューを許可する。独立レビューそのものを省略しない。
- 理由: Runtimeが`task_name=hirame`を表示名へ反映せず、返却された表示名は`Kuhn`。表示名変更の確認手段がない。
- Reviewer: `01a111b8-3884-7123-86bb-02b97c3d4ff2`。実装には参加せず、read-onlyで確認。
- Human Approval: TAKIWAKI Daisuke、2026-10-07 00:02 JST。「今回のみ例外を認めて公開する」と明示承認。
- 期限: 今回のStaging公開完了まで。他のリリースへ再利用しない。
- 完了: 2026-10-07 00:05 JST。commit `67af747`をStagingへ反映し、公開ページを確認。
- 検証: ビルド、リンク監査、差分確認、18事例・93アイコン・非公開情報の除外、390/768/1024/1440pxの表示確認。
- 安全境界: 本番反映、認証、アクセス権、フォーム送信、DNS等の設定には適用しない。
- ロールバック: Stagingを直前の承認済みcommit `55370df`の生成物へ戻す。

例外が必要になった場合は、共通標準の安全ルールを弱めない範囲で、対象、理由、期限、承認者、検証、ロールバックを記録し、Human Approval後に`active`とする。
