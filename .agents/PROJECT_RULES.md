# INBES Web Renewal Project Rules

## Project Context

- Project name: INBES Webサイトリニューアル
- Repository owner: HACTAC
- Technology and runtime: Astro、Tailwind CSS、静的出力、Node.jsは開発・ビルド時のみ
- Environments: local development、GitHub Pages review、mixhost production
- Human approval owner: TAKIWAKI Daisuke

## Non-Negotiable Safety Rules

- Authentication and authorization: 本案件の静的サイトには認証機能を持たせない。将来追加する場合は別途設計・承認する。
- Data isolation and privacy: 顧客情報、フォーム内容、実績掲載可否、メールアドレスをリポジトリへ記録しない。公開許可のないクライアント情報を推測して掲載しない。
- Secret handling: APIキー、SMTP認証情報、FTP認証情報、個人情報、実運用の送信先をソースコード・Knowledge・標準ファイルへ保存しない。
- Destructive operation limits: 既存変更、未追跡ファイル、公開データを承認なく削除・復元・上書きしない。

## Approval-Gated Operations

- Production deployment: mixhostへの本番反映は実行直前にTAKIWAKI Daisukeの明示承認を得る。
- Database migration or destructive data operation: 現在はDB・migrationを使用しない。将来導入時は別途承認する。
- Real email or notification: 実メール送信、フォームの本番送信先接続、自動返信の有効化は実行直前に承認を得る。
- Real payment or billing: 対象外。
- External service configuration: DNS、メール、解析、CMS、ホスティング設定の変更は実行直前に承認を得る。

## Project Behavior and Operations

- Core business rules: トップは自社製品と商品化支援の2入口。自社製品側と商品化支援側のナビゲーションを分ける。
- Content boundary: 製品・実績・サービスは静的データと表示コンポーネントを分離し、将来microCMSへ置き換え可能にする。
- External integrations: 初期公開ではCMSを使用しない。フォーム送信はAstro UIとmixhost PHPを分離する。
- Deployment and rollback: GitHub Pagesは確認用、mixhostが本番予定。公開前はビルド、リンク監査、差分確認を行い、問題時は直前の承認済みリリースへ戻す。
- Required test commands: `npm run build`、`npm run check:links`、`git diff --check`。標準ファイル検証には`python .agents/tools/validate_repository.py --root .agents --knowledge-only`を使用する。

## Design and Accessibility

- Brand OSの参照元はHACTACのINBES Brand OS。Web用の確定値は`design-system/`で管理する。
- 本文18px、line-height 1.6、写真3:2を基本とし、余白と構造を優先する。
- 見出し階層、画像alt、キーボード操作、フォーカス表示、レスポンシブ表示を公開前に確認する。

## Standard Exceptions

現在、承認済みの例外はない。例外は`.agents/STANDARD_OVERRIDES.md`だけに記録する。
