---
title: Standard Overrides
updated: 2026-10-07
---

# Standard Overrides

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
