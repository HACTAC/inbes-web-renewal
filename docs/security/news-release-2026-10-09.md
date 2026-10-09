---
title: News production release
date: 2026-10-09
status: approved-pending-release
type: operation-record
related:
  - analytics-setup-2026-10-09.md
---

# お知らせ本番反映

## 対象と承認

- 本人が本番反映、仮原稿削除、説明文の横幅修正を明示指示した。
- 表示名のみ今回限りの例外を承認。独立Reviewerの実ID・表示名と根拠は[例外記録](../../.agents/STANDARD_OVERRIDES.md)を参照。
- 採用標準は1.1.0、v1.1.0、commit `4118994c2bfffde61c839c6e08c8323fadcfafd0`。
- TOP、お知らせ一覧、リニューアル記事、増資記事、サイトマップの5ファイルを反映する。仮原稿2記事HTMLはゴミ箱へ退避し、永久削除しない。
- 旧サイトで期間2024-10-09以降の掲載を確認できた告知は増資案内1件。原稿は2025年10月吉日であり、増資実施日29日を掲載日とみなさない。
- ほかの旧サポート告知は掲載日不明または期間外で移植しない。既存の旧HTMLとPDFは保持する。

## 成果物・検証

- 本番ビルド、リンク監査、解析タグ試験、差分空白検査は成功。
- summaryのmax幅指定を外し、本文と同じcontainer-copy幅に揃えた。ローカルの実DOMで幅960px一致、横overflowなしを確認した。
- newsデータSHA256: `1edaf8557d2acb34c8b39b021a608b9f2151a79ced6a6c6c9fdf665996e15030`。
- newsテンプレートSHA256: `89e7d7f572e06af16162490ce5ba7be6f24c21b3790a5cd620c1c76f0d6900f2`。
- ZIP SHA256: `ad303ba707744766cdca9e5f9fb87659384f9b064d9a4756e0f2ce12dd649fa9`。
- 独立レビューで原稿の正確性、ZIP5件・削除2件・rollback6件、対象外135ファイル不変を確認。必須修正なし。
- 元PDFは公開HTTP200を確認した。
- 資格・フォーム・メール・DNS・画像・CSSは変更対象外である。

## 復旧

- `/Users/tacky/Web管理/.inbes-backup-state/news-20261009-v2/rollback/`に旧6ファイルを保存。plan.jsonは操作対象と旧新SHAを記録する。
- 差し戻しは旧6ファイルを戻し、新規news/capital-increase-2025/index.htmlを撤去する。永久削除は行わない。
- 初版候補news-20261009は幅調整前のため採用しない。

## 公開結果

未実行。

## Knowledge

今回の原稿と移植件数は案件固有の事実であり、共通知見へ昇格しない。
