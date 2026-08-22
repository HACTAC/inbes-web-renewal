# Release Rules

## Environments

- Development: ローカルAstro開発環境
- Staging: 現時点では設定なし
- Review: GitHub Pagesの確認環境
- Production: mixhost上の静的ファイルとPHPフォーム送信処理

## Approval Boundary

- Human approver: TAKIWAKI Daisuke
- Approval-gated operations: 本番デプロイ、実メール送信、フォーム送信先の接続、外部サービス設定変更、削除を伴う操作

## Pre-Release

- Required tests: `npm run build`、`npm run check:links`、`git diff --check`、標準導入時はValidator
- Backup: mixhost側の既存公開ファイルとPHP送信処理を、運用手順に従って退避する
- Deployment scope: 承認された生成物、静的アセット、PHPフォーム処理だけを対象とする
- Rollback procedure: 直前の承認済み公開ファイルへ戻し、フォーム送信経路と主要ページを確認する

## Post-Release

- Health checks: トップ、製品一覧、商品化支援、実績、会社概要、両フォーム、外部リンク、電話リンク
- User journeys: 自社製品入口、商品化支援入口、製品サポート、商品化相談
- Monitoring: フォーム到達、SMTP、自動返信、サーバーエラーを確認する
- Release record: commit、承認者、反映日時、対象環境、検証結果、ロールバック可否を記録する
