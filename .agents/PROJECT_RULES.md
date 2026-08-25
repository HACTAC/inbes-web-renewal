# INBES Web Renewal Project Rules

## Project Context

- Project name: INBES Webサイトリニューアル
- Repository owner: HACTAC
- Technology and runtime: Astro、Tailwind CSS、静的出力、Node.jsは開発・ビルド時のみ
- Environments: local development、GitHub Pages review、mixhost production
- Human approval owner: TAKIWAKI Daisuke

## Sub-agent Display-name Runtime

- Creation-time name field or tool: Codex sub-agent runtime; verify per task
- Active sub-agent rename operation: runtime-dependent; do not assume availability
- Display-name readback method: inspect the returned sub-agent identity in the task context
- Is `alias_only` a stop condition: yes

If creation-time naming or readback is unavailable, record `alias_only` and do not claim that a visible rename or independent review was verified.

## Task Routing

- Class A / Luna: bounded, mechanical, low-risk work
- Class B / Terra: ordinary implementation, testing, and exploration
- Class C / Sol: complex or ambiguous work
- Class D / Sol: high-impact work; independent review and Human Approval remain required
- Do not lower the required capability tier to preserve quota. If a required Sol-class capability is unavailable, stop and report the task as blocked.

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

## Knowledge Candidate Automation

作業完了時に、Codexは次の条件で再利用可能な知見の有無を自動確認する。

- 複数案件や類似案件へ適用できるパターン、失敗防止策、判断、教訓である
- 事実・推測・仮説を分離できる
- 顧客名、個人情報、秘密値、実運用URL、内部識別子を除外できる
- 再現可能な根拠と適用条件・限界を記録できる

条件を満たす場合は、`.agents/knowledge/`へ`candidate`状態で登録し、作業完了報告に候補IDと概要を記載する。条件を満たさない場合は、候補を作成せず理由だけを報告する。

候補から`approved`以降への状態変更、共通知見や標準への昇格、共通標準Repoへの送信は自動で行わない。独立レビューとHuman Approvalが必要である。

## Standard Exceptions

現在、承認済みの例外はない。例外は`.agents/STANDARD_OVERRIDES.md`だけに記録する。
