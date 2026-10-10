# 問い合わせの追加対策（実装準備）

## 現在の状態

送信結果の記録とTurnstile連携を作業ブランチに実装した。本番未反映。Cloudflareアカウントの権限・本番キーの準備待ち。自動監視・通知は未設定。

公開フォームへのGETは200、PHPバージョンの公開ヘッダーはない。登録済みFTP権限では公開ルート外のPHPMailerを取得できなかった。本番PHP/PHPMailerの実稼働バージョンは未確認であり、ローカルCLIのPHP 8.2.34を本番のバージョンとして扱わない。

リポジトリのPHPMailer指定は7.1.1。2026-10-10取得の公式最新リリースも7.1.1で、Packagistの公開アドバイザリにこの版の該当範囲はなかった。これは本番のインストール版の証明や包括的安全保証ではない。PHP 8.2のセキュリティサポートは2026-12-31までなので、サーバー管理画面/管理者から実稼働版を取得して更新時期を判断する。

- https://github.com/PHPMailer/PHPMailer/releases/tag/v7.1.1
- https://github.com/PHPMailer/PHPMailer/security/advisories
- https://packagist.org/api/security-advisories/?packages[]=phpmailer/phpmailer
- https://www.php.net/supported-versions.php

## 送信結果の記録

`EventLog.php`が固定のイベント種別・処理段階・UTC日時・受付番号だけをJSONLに記録する。本文、会社名、氏名、メール、IP、添付名、トークン、秘密鍵、SMTP応答、例外メッセージは記録しない。

私有設定の`event_file`で指定した、公開ルート外の0700ディレクトリ内に事前作成した0600通常ファイルを使う。未設定なら無効。設定したパスが不正ならフォーム初期化で停止する。書込みは排他ロック、最大1MiB。上限では古い完全な行から削り、新しい記録を保持する。シンボリックリンク・ハードリンク・公開権限は拒否する。運用記録であり永続アーカイブではない。設定後の書込み失敗で受付済みの問い合わせを失敗扱いにしない。

- `delivery_started`：管理者メール送信開始。SMTP完了/配達の証明ではない。
- `accepted`：SMTP送信成功。受信箱への配達保証ではない。
- `reply_sent` / `reply_failed`：自動返信のSMTP結果。返信失敗でも受付成功は維持する。
- `service_failed`：固定の段階名だけで障害位置を記録する。
- `verification_rejected` / `verification_unavailable` / `rate_limited`：送信前の判定結果。

管理者が非公開環境で`php server/contact/summarize-events.php /absolute/private/events.jsonl`を実行すると、保持範囲の件数と送信開始後に成功記録がない件数を確認できる。不確定な送信を自動再送しない。ログの削除/上限/書込み失敗もあるため、成功記録なしだけで未配達と断定しない。このコマンド自体は監視を予約しない。

## Turnstile

ブラウザーはビルド変数`PUBLIC_TURNSTILE_SITE_KEY`がある本番フォームだけでCloudflare公式スクリプトを読み込む。stagingでは実送信もウィジェットも無効。キーは公開情報であり、秘密鍵はこの変数に渡さない。

非公開PHP設定に`turnstile_enabled: true`と`turnstile_secret`を設定する。未設定は互換のため無効。公開ハンドラーは公式のダミー秘密鍵3種を設定不備として拒否する。GETの`turnstileRequired`と画面のキー設定が揃わなければ、新画面は送信を有効にしない。UI表示だけで防御したと扱わない。

PHPは既存の入力・ファイル・送信回数検査後にSiteverifyを一度だけ呼び出す。検証成功、設定originのhostname完全一致、action=`contact`を要求する。固定HTTPS先、TLS証明書検証、リダイレクト禁止、8秒タイムアウト、16KiB応答制限。トークンは最大2048文字。通信/不正応答は閉じて503、判定拒否は403、どちらもメールは送らない。プロバイダー生のエラーや秘密を返さない。

期限切れ・スクリプト障害は送信を停止。確定した拒否からの再送はCSRF/Turnstile双方を更新。受付結果が不確定な場合は従来どおり再送を止めて電話確認を案内する。

- https://developers.cloudflare.com/turnstile/get-started/server-side-validation/

## 本番反映

1. Cloudflareに`inbes.jp`用Managedウィジェットを作成（actionはブラウザー側contact、pre-clearance不要）。本番キー取得までテスト用キーで公開しない。
2. 本番PHPの必要拡張（既存fileinfo/mbstring/openssl/zip）とHTTPS外部通信を確認。非公開環境で`php server/contact/check-runtime.php /absolute/private/vendor/autoload.php`を実行できればバージョンと拡張のみ取得できる。このCLIファイルを公開しない。
3. 非公開のログファイル作成、現在の私有設定のバックアップ、`event_file`とTurnstile設定を準備。SMTP値をログやGitに出さない。
4. PHP公開対象は`send.php`・既存`FormRules.php`・新`EventLog.php`・新`Turnstile.php`を個別管理する。サンプル、CLI、テスト、composer、非公開設定をディレクトリごと公開しない。
5. 本番sitekeyでbuildし、関連する静的差分を確認する。旧buildからの依存更新差分は別途把握し、混在を黙認しない。
6. 私有設定のTurnstile有効化と静的画面を順に適用し、切替中は設定不一致なら送信が停止する。防御を迂回させて切替しない。
7. 本番のGET、PC/mobileの検証画面、秘密・設定・ログのHTTP非公開性を確認。実メールは模擬テストと区別し、必要な場合は本人とテスト宛先を確認する。
8. rollbackもPHP/静的画面/非公開設定の整合を保つ。公開除外・NAS原本・他担当の変更を上書きしない。

## 残る確認

- 本番PHPとPHPMailerの実稼働バージョン。
- 問い合わせ受信メール側のウイルス検査の有効性。現行の種類・サイズ・構造検査をAVとして扱わず、必要な添付形式は維持する。
- Cloudflare proxy導入時の実IP復元。現状の制限はREMOTE_ADDR基準。任意のCF-Connecting-IP/X-Forwarded-Forを無条件に信用しない。サーバーの信頼済みproxy設定と直接アクセス経路を確認してから変更する。
- 自動監視の実行先・通知先・ログ書込み失敗検知。現状は手動点検のみ。

## 検証結果

- PHP機能テスト27項目：ログの許可フィールド、権限、symlink/hardlink拒否、上限保持、障害集計、判定失敗/用途・ホスト不一致/通信障害。
- PC/mobile画面の模擬テスト：Turnstileなし10シナリオ、ありの商品化相談24・製品サポート24シナリオ。SMTP送信なし。画面のボット判定はスタブであり、本番人間判定の成功確認とは別。
- Cloudflare公式のテスト秘密鍵3種で、実HTTPS Siteverifyの成功・失敗・使用済み応答を確認。PHP transportはこのコンテナで検証済み。本番サーバーからの外部通信は別途確認。
- build（キーなし/あり）、内部リンク16ページ17種類、PHP構文検査、diff空白検査に合格。
- 本番フルハンドラーの実メールは送信していない。ローカルPHPはmbstring/zipがなく、既存FormRulesの拡張依存の実行テストは今回も未実施。

公式テスト手順：https://developers.cloudflare.com/turnstile/troubleshooting/testing/
