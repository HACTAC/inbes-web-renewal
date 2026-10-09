# Project Commands

エージェントが案件内で使用できる、再現可能で秘密情報を含まないコマンドを記録する。

## Setup

```bash
npm ci
```

## Syntax And Static Checks

```bash
npm run build
npm run check:links
git diff --check
```

## Knowledge Validation

```bash
python3 .agents/tools/validate_repository.py --root .agents --knowledge-only
```

実行には`.agents/requirements-dev.txt`のPyYAMLとjsonschemaが必要である。グローバルへ無断インストールしない。

## Local Development

```bash
npm run dev
npm run preview
```

## Staging Build

Cloudflare Pagesでは次の環境値で静的ビルドする。DNS、Access、Git連携などの外部サービス設定は、このファイルのコマンドだけでは変更しない。

```bash
PUBLIC_SITE_URL=https://dev.inbes.jp PUBLIC_DEPLOY_ENV=staging npm run build
```

GitHub Pages Reviewをローカルで再現する場合は、ビルドとリンク監査の両方へ同じbase pathを渡す。

```bash
PUBLIC_SITE_URL=https://hactac.github.io PUBLIC_BASE_PATH=/inbes-web-renewal/ PUBLIC_DEPLOY_ENV=review npm run build
PUBLIC_BASE_PATH=/inbes-web-renewal/ npm run check:links
```

## Read-Only Health Checks

- `git status --short`
- `git log -1 --oneline`
- `git diff --check`

実メール、実決済、本番書き込み、migration、外部サービス変更を行うコマンドはここへ記録しない。

## Infisical Local Read

既存inbes.jpだけを専用macOS Identityで読み取る。2026-10-08承認済み：Client Secret TTL0/利用上限0、Token TTL/Max TTL43200秒・上限20・Period0。最終発行と非表示入力は本人が行う。秘密を引数、環境変数、Git、サイト生成物、チャットへ入れない。成功後は資格を維持し、故意失効試験・撤去を行わない。

本人の安全な入力完了後、通常の読取り確認を1回行う。秘密値は表示しない。

```bash
/usr/bin/python3 -E -s /Users/tacky/Documents/Codex/2026-10-08/task/inbes-project-reader/coding_entrypoint.py --environment prod --path /ftp --key FTP_HOST
```

実処理には同じフォルダーの`local_operation.py`の`run_local_operation(selectors, operation)`を使う。selectorsはローカル名から(environment, path, key)のtupleへの辞書。1loginで最大18項目を取得し、信頼済みローカルPython処理だけへメモリ内mappingを渡す。返り値は破棄し、Python stdout/stderrを抑制、エラーはgeneric化する。consumer自身も値を保存・保持・ログ出力しない。

このAPIは同一ユーザーのコード隔離sandboxではない。任意shell/CLIへの渡しやexportは設けない。FTP接続・送信・本番公開の具体処理は別の対象範囲と承認に従う。現時点ではそれらを実行していない。

2026-10-08の通常読取りは`read_check=ok`。sandbox内ではlogin前のDNS解決が失敗したため、同じ固定コマンドを承認済みネットワークの許可付きホスト実行で成功確認した。今後も必要なら同じ経路を使い、TLS検証や固定Project境界を弱めない。資格は保持している。結果は同フォルダーの`OPERATING_RESULT.txt`。

詳細：同フォルダーの`OPERATING_HANDOFF.txt`。APIは当該作業フォルダーを残す前提で、Astro/Nodeのビルドへ組み込まない。

## NAS Operations Checks

```bash
python3 scripts/test-nas-release.py
python3 scripts/nas-release.py --help
```

差分の読取り計画・公開境界・復旧は `docs/handoff/nas-operations.md` を参照する。
