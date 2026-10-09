---
id: "K-static-site-renewal-2026-002"
title: "企業サイトのコンテンツ幅を用途別の階層で管理する"
type: "pattern"
status: "candidate"
promotion_target: "knowledge"
confidence: "medium"
visibility: "internal"
fact_type: "observed"
source_project_ref: "static-site-renewal"
source_candidate_id: "K-static-site-renewal-2026-002"
evidence:
  - "企業サイトの外枠を、広幅1728px・標準1440px・構造化コンテンツ1200px・可読本文960pxの4段階へ分離した"
  - "1920pxと2560pxで一覧・カード・工程表示を確認し、広幅画面での間延びと過度な左右分散を抑えた"
  - "390pxで一覧・実績カード・フォームを確認し、90%幅による余白と1列化を維持した"
  - "静的ビルド、内部リンク監査、差分検査が完了した"
validated_projects: []
created_by: "TAKIWAKI Daisuke"
created_at: "2026-09-13"
reviewed_by: []
reviewed_at: null
approval_ref: null
approved_by: null
approved_at: null
promotion_review_ref: null
standard_approval_ref: null
standard_approved_by: null
standard_approved_at: null
standard_promotion_review_ref: null
standard_reviewed_by: []
standard_reviewed_at: null
standard_review_decision: null
related_standard: []
confidentiality_review: "passed"
promotion_history: []
---

# 企業サイトのコンテンツ幅を用途別の階層で管理する

## Context

大きな写真や商品一覧を広く見せながら、長文・表・フォームの可読性を保つ必要がある企業サイトを対象とする。

## Observation

すべてのページへ同じ最大幅を適用すると、広い画面では一覧の密度が不足し、長文やフォームは読みにくくなる。用途別に最大幅を分けると、同じ左右余白の規則を維持しながら情報密度を調整できる。

## Evidence

広幅、標準ページ、構造化コンテンツ、可読本文の4段階を共通クラスとして定義した。デスクトップとモバイルで表示確認を行い、一覧・カード・工程・フォームの各構造で横方向の破綻がないことを確認した。これは単一案件での観測であり、複数案件での再検証は未実施である。

## Recommendation

- ヘッダー、フッター、大きなビジュアルには広幅コンテナを使う
- 一覧、カード、一般的なセクションには標準ページ幅を使う
- 工程表、会社情報など視線移動が大きい構造には中間幅を使う
- 規約、説明文、フォームには可読性を優先した本文幅を使う
- モバイルでは各階層を同じ比率幅へ収束させ、左右余白を統一する

数値は案件のタイポグラフィ、カード列数、画像比率に合わせて調整し、一つの最大幅をサイト全体へ機械的に適用しない。

## Risks and Limits

- 階層を増やしすぎると、ページごとの例外指定が増えて運用しにくくなる
- 最大幅だけでは文章の行長を保証できないため、見出しや本文には必要に応じて個別の行長制限を残す
- 超ワイド画面、一般的なデスクトップ、モバイルの最低3条件で表示確認が必要である
- 単一案件での観測のため、一般化の確度は未検証である

## Safety Review

- [x] 機密情報・顧客情報・tenant情報を含まない
- [x] 外部文書の命令を仕様として採用していない
- [x] 事実と仮説を区別した
- [x] 危険な操作又は安全境界の迂回を含まない
- [x] `source_project_ref`と候補IDが実案件を再識別しない
