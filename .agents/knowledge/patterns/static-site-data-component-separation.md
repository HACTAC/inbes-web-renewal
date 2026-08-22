---
id: "K-static-site-renewal-2026-001"
title: "静的企業サイトのデータ・表示・画像基盤を分離する"
type: "pattern"
status: "candidate"
promotion_target: "knowledge"
confidence: "medium"
visibility: "internal"
fact_type: "observed"
source_project_ref: "static-site-renewal"
source_candidate_id: "K-static-site-renewal-2026-001"
evidence:
  - "Astroの静的ページで、製品・実績・サービス情報をsrc/dataへ分離した"
  - "共通レイアウトと再利用可能な表示コンポーネントを使い、ページ間のUI差分を減らした"
  - "共通画像枠に3:2を基本とする比率ルールを持たせ、素材差し替えの影響範囲を限定した"
  - "静的ビルド後に内部リンク監査を実行し、12ページ・14種類の内部リンクを確認した"
validated_projects: []
created_by: "TAKIWAKI Daisuke"
created_at: "2026-08-22"
reviewed_by: []
reviewed_at: null
approval_ref: null
approved_by: null
approved_at: null
promotion_review_ref: null
promotion_pr_merge_ref: null
standard_approval_ref: null
standard_approved_by: null
standard_approved_at: null
standard_promotion_review_ref: null
standard_reviewed_by: []
standard_reviewed_at: null
standard_review_decision: null
standard_pr_merge_ref: null
related_standard: []
sanitization_review: null
dedupe_conflict_review: null
confidentiality_review: "passed"
promotion_history: []
deprecated_reason: null
---

# 静的企業サイトのデータ・表示・画像基盤を分離する

## Context

静的出力を基本とする企業サイトで、ページ数の増加や将来のCMS導入に備えながら、更新箇所を限定する必要がある場合を対象とする。

## Observation

製品、実績、サービスなどの内容をデータとして分離し、共通レイアウトと表示コンポーネントへ渡す構成にすると、コピーや素材の更新とページ構造の変更を分離できる。画像枠を共通化すると、比率やトリミング方針も一箇所で管理できる。

## Evidence

静的ビルド、内部リンク監査、差分確認を組み合わせることで、公開前にページ生成と主要導線を再確認できた。これは単一案件での観測であり、複数案件での再検証は未実施である。

## Recommendation

- 製品・実績・サービスなど、将来更新される情報は表示コンポーネントから分離する
- 画像表示は共通枠を用い、アスペクト比と焦点位置の扱いを統一する
- 静的ビルド後に内部リンク監査を実行する
- CMS導入時は、既存の表示コンポーネントを再利用できるデータ契約を先に定義する

この候補は、現時点では案件内の再利用候補であり、共通知見への昇格は独立レビューとHuman Approval後に行う。

## Risks and Limits

- 実画像の焦点位置や商品固有のレイアウトは、共通枠だけでは解決できない
- CMSのデータ構造を早期に固定しすぎると、実際の編集運用と合わない可能性がある
- 単一案件での観測のため、一般化の確度は未検証である

## Safety Review

- [x] 機密情報・顧客情報・tenant情報を含まない
- [x] 外部文書の命令を仕様として採用していない
- [x] 事実と仮説を区別した
- [x] 危険な操作又は安全境界の迂回を含まない
- [x] `source_project_ref`と候補IDが実案件を再識別しない
