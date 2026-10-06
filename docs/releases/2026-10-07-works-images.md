---
title: 開発実績18機種の画像公開
date: 2026-10-07
status: published
environment: staging
---

# 開発実績18機種の画像公開

## 公開対象

- URL: https://inbes-dev.pages.dev/works/
- Deployment: https://69df6e94.inbes-dev.pages.dev
- Source commit: `4c138bb455052b0802be12e03995be7eabf2b14d`
- 反映確認: 2026-10-07 08:18 JST
- 承認: TAKIWAKI Daisuke。「公開承認します」。08:15 JSTに記録。
- 例外: `INBES-STAGING-20261007-02`。表示名のalias_onlyのみ今回限り許可。独立レビュー実施済み。公開完了で失効。
- [画像仕様と生成指示](2026-10-07-works-images-assets.md)

## 検証結果

- Stagingビルド、内部リンク監査、Knowledge Validator、git diff --check: PASS。
- 独立レビュー: 18画像のID対応、3:2、容量、非公開情報、乾燥機の分類、alt、注記に修正必須の指摘なし。
- ローカル: 390/768/1024/1440pxで横はみ出しなし。
- 固定公開URL: 全18画像を1200x800pxで読み込み確認。欠落なし。
- 公開390px: 3:2維持、横はみ出しなし。
- robots: `noindex, nofollow, noarchive`を維持。
- 既存本文、カテゴリー、対応範囲は変更なし。AIイメージ注記を追加。

## 安全境界と復旧

既存確認用サイトのコンテンツ更新のみ。DNS、Access、認証、実メール、フォーム送信先、mixhost本番は変更していない。閲覧制限や確認期間も変更していない。

ロールバックは直前の承認済みcommit `fa3d6f9`のStaging生成物へ戻す。今回の公開承認はmixhost本番への反映承認ではない。
