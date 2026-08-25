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

## Read-Only Health Checks

- `git status --short`
- `git log -1 --oneline`
- `git diff --check`

実メール、実決済、本番書き込み、migration、外部サービス変更を行うコマンドはここへ記録しない。
