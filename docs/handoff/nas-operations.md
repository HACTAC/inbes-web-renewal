# NAS運用とGitHub統合

本番の新サイト正本はAstroソース。GitHubの運用ブランチには開発引き継ぎの本番フォーム/解析/お知らせ対応と、増資お知らせの2025.10.01表示を含む。`legacy-public/` は運用中に修正した旧ページ・背景画像・増資PDFの限定10ファイル。その他の旧サイト原本はNASで保持し、本番の既存ファイルをそのまま残す。

## 管理する対象

- `src/` / `public/`: 新サイトソース。production設定でビルドする。
- `legacy-public/`: 修正済み旧ページ6テキスト、背景JPEG3、増資PDF1。
- `deployment/legacy-files.json`: 明示した旧ファイルだけを候補に含める。
- `deployment/removed-paths.json`: 整理後の有効除外1135件の参照版。復旧した背景3件は除外から解除済み。
- NASの `/mnt/sites/.agents/inbes.jp/public-removed-paths.json`: 除外の最新正本。実運用では必ずこちらを渡す。
- `server/contact/`: 引き継ぎPHPソース・サンプル。通常の静的更新対象には含めない。公開設定は別に管理し、ディレクトリごと送らない。

旧公開原本はSHA一致を維持するため整形しない。`.gitattributes` は旧原本の既存空白を検査対象から外し、PDFをバイナリとして扱う。アプリと更新処理は通常の差分検査対象。

Gitはソースと限定した公開資産だけを管理する。NASの全旧データ、バックアップ、秘密、生成物、削除証拠はGitへ入れない。NAS旧 `inbes.jp/index.html` は別の旧データであり、新サイトへの上書き元にしない。

## 更新手順

1. 作業ブランチでソースを編集し、production設定でビルドする。`PUBLIC_SITE_URL=https://inbes.jp`、`PUBLIC_BASE_PATH=/`、`PUBLIC_DEPLOY_ENV=production`、`PUBLIC_GA_MEASUREMENT_ID` は確認済みの公開計測IDを使う。秘密のFTP/SMTP情報はビルドへ渡さない。
2. `npm run check:links`、`python3 scripts/test-nas-release.py`、`git diff --check`、適用されるValidatorとPC/mobile表示確認を行う。
3. 下記の既定読取り処理で、最新FTPSとの違いだけを固定する。新しい、未使用の作業フォルダーを指定する。

```bash
python3 scripts/nas-release.py \
  --state /mnt/sites/.agents/work/inbes-release-UNIQUE \
  --adapter /mnt/sites/.agents/tools/inbes_readonly_audit.py \
  --excluded /mnt/sites/.agents/inbes.jp/public-removed-paths.json
```

これはアップロードしない。最新サーバーデータとの全候補照合、変更前データの退避、修正候補保存、`plan.json` の作成だけを行う。credentialsは登録済みINBES専用NASアダプター内で取得し、ログやGitに書かない。別サイトのアダプターは拒否する。

4. `plan.json` の対象・旧/新SHA・退避・差分と表示を独立Reviewerが確認する。review JSONは `verdict: PASS` と、その固定 `plan.json` バイト列のSHA256である `plan_sha256` を記録する。本人の対象範囲を指定した公開承認も記録する。変更なしなら公開不要。
5. 公開対象が確定したときだけ、同じstate/excludedを使い `apply --review REVIEW_JSON --approval APPROVAL_RECORD` を指定する。過去の別作業の承認を新しい公開へ流用しない。
6. FTPS読み戻し後、HTTPS・PC/mobile・変更した利用導線を確認する。CloudflareのCSS/画像キャッシュはFTP上書きだけでは即時反映しない場合がある。必要なら参照HTMLへバージョンを付けた限定変更をレビューする。
7. 結果・承認・退避・未確認事項をNASのsite.md/history.mdへ記録し、GitHubのソースを同じ状態へ揃える。Mac同期は別途確認する。

更新処理は全件の旧SHA確認後、各ファイルの直前にも再確認し、アップロード後に全データのSHAを読み戻す。payload・退避・除外・実行ツールが変われば停止する。履歴は一時ファイルからの置換で保存する。既存journalがあるstateでの安易な再実行は拒否する。

## 復旧

既存ファイルの更新なら、同じstateに `rollback --review REVIEW_JSON --approval ROLLBACK_APPROVAL` を指定する。公開済みSHAと退避SHAを照合し、元ファイルを戻して読み戻す。他の担当者の変更や途中までしか書かれていないファイルは自動で上書きせず、証拠を保持して調査する。ソースも戻すときは独自の変更を確認してから、そのリリースに対応するGit変更を戻す。

新規ファイルの公開後の撤去は別の復旧計画・承認で扱う。この処理は削除コマンドを持たない。親フォルダーが存在しない新規パスは計画時に停止するため、追加先の準備を別途確認する。

## GitHubと公開の境界

専用ブランチへのpushとPR作成はソース統合であり、mixhost本番への自動公開はしない。既存GitHub Pages workflowはmain変更時の確認サイト公開用のまま。今回、そのworkflowや外部接続設定を変更していない。mainへのmerge・本番公開は、その対象が確定した別の操作として扱う。
