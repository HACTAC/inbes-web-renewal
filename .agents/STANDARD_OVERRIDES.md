---
title: Standard Overrides
updated: 2026-10-07
---

# Standard Overrides

## INBES-STAGING-20261007-01

- Status: `active`
- 対象: 開発実績18事例のCloudflare Pages Staging公開1回のみ。
- 例外: Reviewerの表示名を指定できないため、`alias_only`での独立レビューを許可する。独立レビューそのものを省略しない。
- 理由: Runtimeが`task_name=hirame`を表示名へ反映せず、返却された表示名は`Kuhn`。表示名変更の確認手段がない。
- Reviewer: `01a111b8-3884-7123-86bb-02b97c3d4ff2`。実装には参加せず、read-onlyで確認。
- Human Approval: TAKIWAKI Daisuke、2026-10-07 00:02 JST。「今回のみ例外を認めて公開する」と明示承認。
- 期限: 今回のStaging公開完了まで。他のリリースへ再利用しない。
- 検証: ビルド、リンク監査、差分確認、18事例・93アイコン・非公開情報の除外、390/768/1024/1440pxの表示確認。
- 安全境界: 本番反映、認証、アクセス権、フォーム送信、DNS等の設定には適用しない。
- ロールバック: Stagingを直前の承認済みcommit `55370df`の生成物へ戻す。

例外が必要になった場合は、共通標準の安全ルールを弱めない範囲で、対象、理由、期限、承認者、検証、ロールバックを記録し、Human Approval後に`active`とする。
