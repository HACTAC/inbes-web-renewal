# 問い合わせの追加対策（実装準備）

## 現在の状態

送信結果の記録とTurnstile連携を作業ブランチに実装した。本番未反映。2026-10-10、本人の具体的承認に基づきmaguroが公式MCPでINBES Managed widgetを作成し、GETで同名候補1件を確認したとの引渡しを受領。対象はHACTAC Account、許可ドメインは`inbes.jp`・`www.inbes.jp`。本人から既存Infisicalへの2キー保存完了と独立metadata-only確認成功の連絡を受領。その後の本人進行指示に基づくメモリ内取得・形式確認は完了したが、実チャレンジの妥当性検証は未実施であり、秘密転送・非公開runtime設定・本番公開は未実施。NASのCloudflare接続は本人が手動有効化し、公式MCP GET成功との連絡を受領。Turnstileのみを対象とし、Zone追加・DNS/NS/WAF変更や管理APIトークンは今回不要。自動監視・通知は未設定。

公開フォームへのGETは200、PHPバージョンの公開ヘッダーはない。当初のFTP権限では公開ルート外のPHPMailerを取得できなかった。その後の本人によるFTP Home Directory変更後、私有設定とvendor等8ファイルを値非表示で非公開NAS領域へ退避し、PHPMailer.phpのVERSION定数7.1.1を確認した。これは実際にロードされた版の証明とは区別する。本番PHP/PHPMailerの実稼働バージョンは未確認であり、ローカルCLIのPHP 8.2.34を本番のバージョンとして扱わない。

リポジトリのPHPMailer指定は7.1.1。2026-10-10取得の公式最新リリースも7.1.1で、Packagistの公開アドバイザリにこの版の該当範囲はなかった。これは本番のインストール版の証明や包括的安全保証ではない。PHP 8.2のセキュリティサポートは2026-12-31までなので、サーバー管理画面/管理者から実稼働版を取得して更新時期を判断する。

- https://github.com/PHPMailer/PHPMailer/releases/tag/v7.1.1
- https://github.com/PHPMailer/PHPMailer/security/advisories
- https://packagist.org/api/security-advisories/?packages[]=phpmailer/phpmailer
- https://www.php.net/supported-versions.php

## 送信結果の記録

`EventLog.php`が固定のイベント種別・処理段階・UTC日時・受付番号だけをJSONLに記録する。本文、会社名、氏名、メール、IP、添付名、トークン、秘密鍵、SMTP応答、例外メッセージは記録しない。

私有設定の`event_file`で指定した、公開ルート外の0700ディレクトリ内に事前作成した0600通常ファイルを使う。未設定なら無効。初期化時は公開範囲・パス・権限等の不正を拒否する。ハードリンクの検査はEventLog書込み時に行い、書込みを拒否するため、GET成功だけをログ書込み可能の証明と扱わない。書込みは排他ロック、最大1MiB。上限では古い完全な行から削り、新しい記録を保持する。シンボリックリンク・ハードリンク・公開権限は拒否する。運用記録であり永続アーカイブではない。設定後の書込み失敗で受付済みの問い合わせを失敗扱いにしない。

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

1. 作成済みINBES Managed widgetを使い、重複widget・Project・資格を作成しない。本人がCloudflare Dashboardで既存widgetのsecretを取得し、Website Management / prod / `/sites/inbes-jp`の`TURNSTILE_SITE_KEY`・`TURNSTILE_SECRET_KEY`へ本人入力する。保存完了連絡後の本人「進めて」に基づくメモリ内取得と形式確認は完了。既存非公開設定領域へのアクセス未確保のため、秘密の保存・runtime転送・有効化は未実施。actionはブラウザー側contact。widgetの許可ドメインにwwwが含まれていても、PHPは現在のorigin `https://inbes.jp`とのhostname完全一致を維持し、www送信許可へ自動拡張しない。本番公開は別途承認を得る。
2. 本番PHPの必要拡張（既存fileinfo/mbstring/openssl/zip）とHTTPS外部通信を確認。非公開環境で`php server/contact/check-runtime.php /absolute/private/vendor/autoload.php`を実行できればバージョンと拡張のみ取得できる。このCLIファイルを公開しない。
3. 非公開のログファイル作成、現在の私有設定のバックアップ、`event_file`とTurnstile設定を準備。SMTP値をログやGitに出さない。
4. PHP公開対象は`send.php`・既存`FormRules.php`・新`EventLog.php`・新`Turnstile.php`・新`PrivateStorage.php`を個別管理する。サンプル、CLI、テスト、composer、非公開設定をディレクトリごと公開しない。
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

## maguro.localからの引渡し受領・取得経路の最小追加

2026-10-10の本人メッセージと非秘密資料`/mnt/sites/.agents/inbes.jp/turnstile-preparation-maguro-20261010.txt`を確認。同一保存先優先、手動ダッシュボード管理を第一案とする。先に新規プロジェクト、Identity、管理APIトークンを作らない。

- 保存先：Website Management / `9c44515d-195c-42bd-8d67-46eedf30f896` / prod / `/sites/inbes-jp`。
- 追加予定名：`TURNSTILE_SITE_KEY`（公開）と`TURNSTILE_SECRET_KEY`（非公開）。保存行為は本人操作または行為直前の明示承認。
- `scripts/turnstile-settings.py`は専用の既存`inbes_infisical.py`の固定scope・専用Identity・HTTPS/redirect拒否を使う、独立した2項目consumer。既存FTP6項目の関数やJSONのkeys指定は変えない。
- importでは読取り・認証しない。CLIも保存機能も持たない。取得/転送の明示承認後に限り`consume_settings(consumer, approved=True)`を呼ぶ。準備当初は呼んでいなかったが、その後の本人「進めて」に基づき呼び出し、専用Identityによる2キー取得と公開sitekey一致・秘密の形式確認に成功した。実チャレンジの妥当性とruntime bindingは未検証。
- imports/reference展開なし、sharedのこの2キーだけを個別要求。scope不一致、404/403、空/非文字列/隠された値は停止。別Identity、旧Project、MacBook資格、Personal/Business bundleへのfallbackなし。
- 公開consumerは`PUBLIC_TURNSTILE_SITE_KEY`だけを受ける。PHP consumerは`turnstile_secret`だけを非公開runtimeへ渡す。`turnstile_enabled`は自動設定しない。runtime設定の転送と有効化は別の明示承認が必要で、Infisical保存だけではサーバーに届かない。
- モックだけの5テストで、未承認時のreader未ロード、固定2キー/scope、値分離、失敗時停止と値非表示を確認。当初のオフライン準備段階は実Infisical認証/値取得0。その後の承認済み取得結果は末尾の現況を参照し、秘密の転送/保存は未実施。

Cloudflareログイン、ウィジェット/資格発行・保存、秘密転送、本番公開、実メール、新規アカウント、DNS/NS/WAFの変更は今回未実行。API管理は必要性が生じた時だけ対象AccountのTurnstile EditとAccount IDを検討し、発行/保存の直前に本人承認を得る。


## 2026-10-10 本人の進行指示後の準備

本人の「進めて」を、登録済み2キーの取得と非公開設定の準備に対する指示として受領。本番公開の承認とは扱わない。専用consumerで値をメモリ内取得し、公開sitekeyが作成済みINBES widgetと一致、秘密キーが許容形式で公式ダミーでないことを確認。秘密キーの表示・保存・本番転送なし。実際のチャレンジでの秘密キー妥当性は未検証。

既存公開解析IDを保持したローカルbuild、内部リンク、更新処理12テストが合格。本番比較はread-onlyで計画・退避を作成し、独立レビューを依頼。PHP/private設定を静的更新計画に含めたとは扱わない。公開ルート外のFTPSアクセスは既に拒否されており再probeせず、サーバー管理者による既存非公開設定領域へのアクセスと本番runtime確認が必要。秘密を公開ディレクトリへ迂回配置しない。

NASの非秘密記録：`/mnt/sites/.agents/inbes.jp/turnstile-authorized-retrieval-20261010.json`、`turnstile-private-runtime-preparation-20261010.json`。


## サイト内管理フォルダーへの移行準備

本人方針は、制作時からサイト内に管理対象をまとめ、FTP接続先をサイトルートへ固定する構成。既存のサイト外配置は、移行検証が完了するまで維持する。

移行先は実際のdocument root直下の固定`.inbes-private`のみ。PHP側は任意の公開領域を許可する設定を追加せず、固定パス・権限・リンク検査と正しいアクセス遮断ファイルを検査する。ファイルが存在することだけではWebサーバーの遮断動作の証明にならないため、次の準備と本番適用を分ける。

1. 現在の非公開設定とvendor/stateの取得・退避方法を確定する。取得資格やSMTP/Turnstile値をGit・ログ・差分へ出さない。既存設定のenabled/origin/SMTP/rate/autoreplyを維持し、設定ファイルだけ移してautoload/state参照が旧配置のままになる状態を避ける。
2. 公開変更の対象が確定した承認後に、非秘密の遮断ファイルと検証用ファイルだけを移行先へ設置する。検証ファイルは`policy-check.txt`、`policy-check.php`、`policy-check.json`、`policy-check.bak`、`probe-nested/policy-check.txt`。内容は非秘密の固定文字列とし、PHP検証ファイルも固定文字列の出力だけにする。依存ライブラリの下位`.htaccess`で遮断が解除される構成を許可しない。
3. 検証用ファイルが実在することをFTPで確認した後、`scripts/check-private-http.py`でGET/HEAD/POST/OPTIONSがすべて403になることを確認する。404、redirect、500、network failureは不合格。http checkerはログや設定ファイルを読み取らず、固定の検証用パスだけを要求する。今回の3テストはオフラインであり、本番HTTP遮断は未確認。
4. 遮断を確認できた後にだけ、別途準備・承認した非公開設定/vendor/state/logを配置する。0700/0600等の権限を確認し、PHPMailerの配置も同一管理フォルダーにまとめる。実行版PHP/必要拡張・外部HTTPS通信・ログ書込みも確認する。
5. 新しいPrivateStorage helper、send.php、settings-path.phpの固定参照、Turnstile画面、private設定を整合させて切り替える。PHP公開allowlistには新helperを追加し、遮断テンプレート/テスト/CLIをcontactへ一括転送しない。
6. 新private参照と旧settings-path/private設定を退避し、問題時にhandler・静的画面・参照先を揃えて復旧する。元の外側フォルダーは動作確認前に削除しない。撤去は別の対象確定した操作として扱う。

現在FTP Home Directoryは本人操作で`.inbes-contact`へ変更済み、読み取り確認成功。通常の公開rootアダプターによる適用はこの状態で行わない。移行先への設置前に本人が`public_html/inbes.jp`へ戻し、FTPの論理パスと登録済みFTP_REMOTE_ROOTの対応を確認する。FTP資格は再発行しない。秘密を公開可能な作業フォルダーへ一時配置しない。

独立レビューで、FTPのHome DirectoryとWeb/PHPのdocument rootは別設定と確認。cPanelのドメイン一覧で公開先を確認し、実PHPのdocument rootとの対応を確定してから移行する。FTPを`.inbes-contact`へ変更したことを公開先変更と扱わない。HTTP checkerの403はcanonical URLの結果であり、origin側でdenyが適用される設定と同一実体の公開経路も確認する。監査snapshotはtext枝と既存build一致枝の双方で`.inbes-private`を除外し、古いinventoryを使う場合にも秘密取得を避ける。既存静的planは更新ツールSHA変更により適用不可となるため、FTPサイトルート復帰後に新しいstateで再作成する。

最終ソース準備レビューは独立hirame PASS、公開readyとは区別。本番private backupは`/mnt/sites/.agents/backups/inbes-private-before-contained-20261010`（8ファイル/279270bytes、dir0700/file0600）に保持。取得時点の退避でありatomic snapshotではない。mutable rate stateは切替時に最新値を確認する。値は会話・ログ・Gitへ出していない。本番書込みは0。
