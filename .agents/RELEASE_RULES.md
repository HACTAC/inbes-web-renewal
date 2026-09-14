# Release Rules

## Environments

- Development: ローカルAstro開発環境。実装とブラウザQAに使用する。
- Review: GitHub Pagesの現行確認環境。Cloudflare Pages Stagingの稼働開始までは維持する。
- Staging: `dev.inbes.jp`を使用するCloudflare Pages環境。現時点では導入予定であり、DNS・Pages・Accessの設定完了と検証後に稼働中へ変更する。
- Production: mixhost上の静的ファイルとPHPフォーム送信処理。

## Approval Boundary

- Human approver: TAKIWAKI Daisuke
- Approval-gated operations: 本番デプロイ、実メール送信、フォーム送信先の接続、外部サービス設定変更、削除を伴う操作

## Staging Rules

- Initial provisioning: Cloudflare Pagesプロジェクト、`dev.inbes.jp`、Cloudflare Access、Git連携の新設・変更は、実行直前にHuman Approvalを得る。
- Access: 外部確認者は許可したメールアドレスまたはドメインへ限定する。公開URLだけを知る不特定利用者へ開放しない。
- Indexing: 全ページへ`noindex, nofollow`を付与し、検索エンジン向けsitemap送信を行わない。
- Forms: 本番PHP送信先、実メール、自動返信へ接続しない。フォームUI確認と本番送信検証を分離する。
- Source: 承認されたブランチまたはcommitだけをデプロイし、対象commitと確認依頼日を記録する。
- Promotion: Stagingの表示確認をProduction承認の代替にしない。本番反映は別途Human Approvalを得る。
- Rollback: 直前の正常なPages deploymentへ戻し、主要ページ、内部リンク、Access境界を再確認する。

## Pre-Release

- Required tests: `npm run build`、`npm run check:links`、`git diff --check`、標準導入時はValidator
- Staging checks: `dev.inbes.jp`のDNS、HTTPS、Access、`noindex`、フォーム非送信、対象commitを確認する。
- Backup: mixhost側の既存公開ファイルとPHP送信処理を、運用手順に従って退避する
- Deployment scope: 承認された生成物、静的アセット、PHPフォーム処理だけを対象とする
- Rollback procedure: 直前の承認済み公開ファイルへ戻し、フォーム送信経路と主要ページを確認する

## Post-Release

- Health checks: トップ、製品一覧、商品化支援、実績、会社概要、両フォーム、外部リンク、電話リンク
- User journeys: 自社製品入口、商品化支援入口、製品サポート、商品化相談
- Monitoring: フォーム到達、SMTP、自動返信、サーバーエラーを確認する
- Release record: commit、承認者、反映日時、対象環境、検証結果、ロールバック可否を記録する
- Staging record: URL、commit、Access対象、確認期間、停止または継続の判断を記録する。
