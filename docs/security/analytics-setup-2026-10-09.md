---
title: INBES production analytics setup
date: 2026-10-09
status: partial-complete
type: operation-record
related:
  - production-migration-plan-2026-10-09.md
---

# 解析・検索登録の準備

## 確認した事実

- 本人がログインしたChromeで、INBESの既存GA4プロパティとウェブストリーム、およびSearch Consoleのドメインプロパティを確認した。
- 既存のGA4測定IDは`G-5SQ5RBJXCT`。公開タグ用の識別子であり、資格ではない。
- Search Consoleのサイトマップ一覧は空、GA4のSearch Console連携一覧も空である。
- GA4の拡張計測は有効で、フォーム操作だけは無効である。サイト内検索・離脱クリック・スクロール・動画・ダウンロードは有効である。
- 新サイトの公開HTMLにはまだGA4タグがなく、既存のCloudflare beaconが追加されている。
- 本番のrobots.txt、canonical、サイトマップは本番ドメイン向けで、HTMLにnoindexはない。

## 承認された変更

1. 既存ストリームの拡張計測を無効にする。履歴変更による追加page_viewも停止し、初回は基本の閲覧計測から始める。旧商品ページを含む同ストリーム全体の拡張イベントにも影響する。
2. `PUBLIC_GA_MEASUREMENT_ID`を指定した本番ビルドのHTML17件を限定反映する。アセット、PHP、秘密設定、旧商品ページ、公開制御は変更しない。
3. クッキーポリシーにGA4・既存Cloudflare解析・用途・無効化方法を明記する。
4. Search Consoleへ本番の`sitemap.xml`を登録する。確認サイトのサイトマップは送信しない。
5. 既存Search Consoleドメインプロパティと既存GA4ウェブストリームをリンクする。既存のGA4利用者が検索レポートを参照できるようになる。アカウント・ユーザー権限の追加はしない。

## 実装・検証

- 環境値が明示的にproductionかつ実hostnameが本番サイトである場合だけタグを読み込む。環境未指定・確認サイト・ローカルでは計測しない。
- 初回page_locationとpage_referrerからquery/hashを除外する。Signalsと広告パーソナライズをタグで無効にする。
- フォームの値や受付番号を送るイベントは実装しない。
- 本番候補ビルド、内部リンク監査、差分の空白検査、VMによるタグ数・ホスト制限・URL除去・送信項目の試験は成功した。
- 独立レビューの指摘に基づき、拡張計測停止と明示的なproduction条件を採用した。実装不参加の`/root`による最終限定レビューで追加の必須修正なし。拡張計測と履歴変更計測の無効化を保存・読み戻し確認してからタグを反映することが条件である。
- GA4の実受信、Search Consoleのサイトマップ受付は未実施。準備済みを設定完了として扱わない。

確認対象SHA256:

- BaseLayout: `c687a0c05bd8b2116c2afc65eed7b800c42b56c1ac21d047e6feb43873277a9b`
- cookies: `9685ec8029f0d30456cca09a1279ca76c11bf2836a164ecce7438f32868ba847`

## 復旧

公開済みの最終候補に保存したHTMLへ戻せば、今回追加するGA4タグを撤回できる。拡張計測は現在の個別設定を上記記録に沿って戻す。リンクやサイトマップの削除は新たな承認なしに行わない。

## 2026-10-09 実行結果

- 上記5項目について本人の「承認します」を受けて実行した。DNS、PHP、実メール送信、権限追加は行っていない。
- GA4拡張計測を無効化し、再読込み後の未選択状態を確認した。
- HTML17件限定アーカイブを独立レビューした。spawn_agentの実返却identityは`Sartre`、agent_idは`01a11dd6-d02d-7800-a14f-665d6d1fd079`。Mainのtask contextで実名を読み戻しており、手動aliasではない。レビュアー内部にはidentityが提示されなかったため、その不明報告とMain側の実返却根拠を区別する。
- 固定成果物について重大な追加修正不要。生成スクリプトの汎用化時には親symlink検査・非HTMLパス検証・再読取り競合対策が改善候補である。今回の成果物では該当異常なし。
- アーカイブSHA256は`c96b86918595f563c1a035c012c835489b13db73529e50bc82bcb92f999afaf0`。非公開領域へHTTPS転送後、サーバーからダウンロードした実ファイルも同一SHAである。
- 承認済み公開ルートへHTML17件のみ反映。実差分16件、processのリダイレクトHTMLは変更なし。PHP・画像・CSS・旧商品ページは更新対象外である。
- 公開HTTP読戻しは17/17件一致。配信時に追加されたCloudflare beacon scriptと直後の改行だけを除去して照合した。
- ブラウザーでGA4外部タグの読込みと更新されたクッキーポリシーを確認。GA4リアルタイムに新サイトの「開発実績 | INBES株式会社」とpage_viewが表示され、実受信を確認した。
- 既存Search Consoleドメインプロパティと既存GA4ストリームのリンク作成済みを確認した。
- サイトマップ送信受付は成功したが、Search Consoleの取得状態は「サイトマップを読み込めませんでした」。公開URLは通常UA・Googlebot UAともHTTP200、robotsは許可。Googleの実クロール到達性は別途調査中である。
- ロールバック17件とplanはRepo外の`/Users/tacky/Web管理/.inbes-backup-state/analytics-20261009/`に保存している。
- GA4リアルタイムにはTOPの「INBES株式会社」も表示され、基本計測は完了した。以前閲覧した通常URLにはブラウザーの旧HTMLキャッシュが見られたため、検証用queryを付けた取得でも確認した。queryは解析URLから除外する実装である。
- Search ConsoleのTOP公開URLテストは2026-10-09 08:34に「URL は Google に登録できます」となった。Googleの現時点のTOP取得は成功している。
- サイトマップは一度再送した。送信受付は再度成功したが、取得エラー表示は残る。取得成功・検出15ページ・新規インデックス登録は未確認であり、検索登録設定を全件完了とはしない。防御設定やDNSを推測で変更していない。
- build、check:links、test-analytics、git diff --checkは成功。knowledge-only validatorはPyYAML未導入により実行不可であった。依存を追加インストールしていない。
- この記録の運用事実は案件固有であり、共通知見候補へ昇格・送信しない。

## 参照

- [Google公式: ページビューの測定](https://developers.google.com/analytics/devguides/collection/ga4/views?hl=ja)
- [Google公式: 拡張計測](https://support.google.com/analytics/answer/9216061?hl=ja)
- [Google公式: Search ConsoleとAnalyticsの連携](https://support.google.com/analytics/answer/10737381?hl=ja)
