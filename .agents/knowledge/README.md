# Knowledge Base

このディレクトリは、案件から得た匿名化済みの知見候補を保存します。ここにある内容は自動的に共通標準になりません。初版は`internal`の知見だけを扱います。

## Categories

- `patterns/`: 複数案件で使える可能性のある設計・実装パターン
- `pitfalls/`: 再発防止すべき失敗、落とし穴、誤用
- `lessons/`: 失敗、回帰、運用から得た教訓
- `decisions/`: 汎用化可能な判断とトレードオフ

各エントリは`templates/knowledge-entry.md`のfront matterに従い、`schemas/knowledge.schema.json`で検証可能な形にします。候補IDは`K-{anonymous-project-code}-{YYYY}-{NNN}`です。案件コードを含めない`K-YYYY-NNN`は複数案件で衝突するため使用しません。

登録と昇格には`SECURITY.md`、`GOVERNANCE.md`、`workflows/knowledge-promotion.md`を適用します。独立Reviewerの根拠と人間承認なしに、候補を共通知見又は標準へ昇格してはいけません。
