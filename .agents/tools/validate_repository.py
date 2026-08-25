#!/usr/bin/env python3
"""Validate release metadata, agent routing, and knowledge governance invariants."""
from __future__ import annotations

import json
import re
import sys
from argparse import ArgumentParser
from pathlib import Path
from urllib.parse import unquote

import yaml
from jsonschema import Draft202012Validator, FormatChecker

DEFAULT_ROOT = Path(__file__).resolve().parents[1]
PROJECT_ID = re.compile(r"^K-(?P<project>.+)-[0-9]{4}-[0-9]{3}$")
CHANGELOG_VERSION = re.compile(
    r"^## \[([0-9]+\.[0-9]+\.[0-9]+(?:-rc\.[0-9]+)?)\]",
    re.MULTILINE,
)
LINK = re.compile(r"(?<!!)\[[^]]*\]\(([^)]+)\)")
CAPABILITY_LEVEL = {"luna": 0, "terra": 1, "sol": 2}


def relative(root: Path, path: Path) -> str:
    return path.relative_to(root).as_posix()


def fail(root: Path, errors: list[str], path: Path, message: str) -> None:
    errors.append(f"{relative(root, path)}: {message}")


def parse_front_matter(root: Path, path: Path, errors: list[str]) -> dict | None:
    text = path.read_text(encoding="utf-8")
    if not text.startswith("---\n"):
        fail(root, errors, path, "knowledge entry must begin with YAML front matter")
        return None
    closing = re.search(r"^---\s*$", text[4:], re.MULTILINE)
    if closing is None:
        fail(root, errors, path, "YAML front matter is not closed")
        return None
    try:
        data = yaml.safe_load(text[4 : 4 + closing.start()])
    except yaml.YAMLError as error:
        fail(root, errors, path, f"invalid YAML front matter: {error}")
        return None
    if not isinstance(data, dict):
        fail(root, errors, path, "YAML front matter must be a mapping")
        return None
    return data


def validate_history(root: Path, path: Path, entry: dict, errors: list[str]) -> None:
    history, status = entry.get("promotion_history"), entry.get("status")
    if not isinstance(history, list) or not isinstance(status, str):
        return
    if not history:
        if status != "candidate":
            fail(root, errors, path, "empty promotion_history is only valid for status candidate")
        return
    if not isinstance(history[0], dict) or history[0].get("from_status") != "candidate":
        fail(root, errors, path, "promotion_history must begin from candidate")
    previous = "candidate"
    for number, transition in enumerate(history, 1):
        if not isinstance(transition, dict):
            continue
        if transition.get("from_status") != previous:
            fail(root, errors, path, f"promotion_history entry {number} is not contiguous")
        previous = transition.get("to_status")
    if previous != status:
        fail(root, errors, path, "promotion_history must end at the current status")


def validate_provenance(root: Path, path: Path, entry: dict, errors: list[str]) -> None:
    source_project = entry.get("source_project_ref")
    for field in ("id", "source_candidate_id"):
        value = entry.get(field)
        match = PROJECT_ID.match(value) if isinstance(value, str) else None
        if match and match.group("project") != source_project:
            fail(root, errors, path, f"{field} project prefix must match source_project_ref")


def validate_common_review(root: Path, path: Path, entry: dict, errors: list[str]) -> None:
    if entry.get("status") not in {"approved", "promoted", "standard-candidate", "standardized"}:
        return
    created_by, reviewers = entry.get("created_by"), entry.get("reviewed_by")
    if not isinstance(created_by, str) or not created_by.strip():
        fail(root, errors, path, "created_by must identify the candidate author")
        return
    if not isinstance(reviewers, list) or not any(
        isinstance(reviewer, str) and reviewer.strip() and reviewer.strip() != created_by.strip()
        for reviewer in reviewers
    ):
        fail(root, errors, path, "reviewed_by must include an identity different from created_by")


def validate_promotion_target(root: Path, path: Path, entry: dict, errors: list[str]) -> None:
    status, target = entry.get("status"), entry.get("promotion_target")
    expected_by_status = {
        "candidate": "knowledge",
        "approved": "knowledge",
        "promoted": "knowledge",
        "rejected": "knowledge",
        "standard-candidate": "standard",
        "standardized": "standard",
    }
    expected = expected_by_status.get(status)
    if status == "deprecated":
        history = entry.get("promotion_history")
        final_transition = history[-1] if isinstance(history, list) and history else None
        source = final_transition.get("from_status") if isinstance(final_transition, dict) else None
        expected = {
            "promoted": "knowledge",
            "standard-candidate": "standard",
            "standardized": "standard",
        }.get(source)
        if expected is None:
            fail(root, errors, path, "deprecated must end with a transition from promoted, standard-candidate, or standardized")
            return
    if expected is not None and target != expected:
        fail(root, errors, path, f"promotion_target must be {expected!r} for status {status!r}")


def validate_standard_review(root: Path, path: Path, entry: dict, errors: list[str]) -> None:
    if entry.get("status") not in {"standard-candidate", "standardized"}:
        return
    if entry.get("standard_approval_ref") == entry.get("approval_ref"):
        fail(root, errors, path, "standard_approval_ref must differ from approval_ref")
    if entry.get("standard_promotion_review_ref") == entry.get("promotion_review_ref"):
        fail(root, errors, path, "standard_promotion_review_ref must differ from promotion_review_ref")
    reviewers, common = entry.get("standard_reviewed_by"), entry.get("reviewed_by", [])
    if not isinstance(reviewers, list) or not all(isinstance(item, str) and item.strip() for item in reviewers):
        fail(root, errors, path, "standard_reviewed_by must list standard reviewer identities")
    elif isinstance(common, list) and not set(reviewers).difference(common):
        fail(root, errors, path, "standard_reviewed_by must include an identity absent from reviewed_by")


def validate_knowledge(root: Path, errors: list[str]) -> None:
    schema_path = root / "schemas" / "knowledge.schema.json"
    try:
        schema = json.loads(schema_path.read_text(encoding="utf-8"))
        Draft202012Validator.check_schema(schema)
    except Exception as error:
        fail(root, errors, schema_path, f"cannot load a valid JSON Schema: {error}")
        return
    validator = Draft202012Validator(schema, format_checker=FormatChecker())
    knowledge = root / "knowledge"
    for path in sorted(knowledge.rglob("*.md")) if knowledge.exists() else []:
        if path.name.lower() == "readme.md":
            continue
        entry = parse_front_matter(root, path, errors)
        if entry is None:
            continue
        for error in sorted(validator.iter_errors(entry), key=lambda item: list(item.path)):
            field = ".".join(map(str, error.absolute_path)) or "front matter"
            fail(root, errors, path, f"schema validation failed at {field}: {error.message}")
        validate_provenance(root, path, entry, errors)
        validate_history(root, path, entry, errors)
        validate_common_review(root, path, entry, errors)
        validate_promotion_target(root, path, entry, errors)
        validate_standard_review(root, path, entry, errors)


def validate_agent_names(root: Path, errors: list[str]) -> None:
    schema_path = root / "schemas" / "agent-names.schema.json"
    registry_path = root / "config" / "agent-names.yml"
    if not registry_path.exists():
        registry_path = root / "AGENT_NAMES.yml"
    try:
        schema = json.loads(schema_path.read_text(encoding="utf-8"))
        Draft202012Validator.check_schema(schema)
    except Exception as error:
        fail(root, errors, schema_path, f"cannot load a valid JSON Schema: {error}")
        return
    try:
        registry = yaml.safe_load(registry_path.read_text(encoding="utf-8"))
    except (OSError, yaml.YAMLError) as error:
        fail(root, errors, registry_path, f"cannot load agent name registry: {error}")
        return
    validator = Draft202012Validator(schema)
    for error in sorted(validator.iter_errors(registry), key=lambda item: list(item.path)):
        field = ".".join(map(str, error.absolute_path)) or "registry"
        fail(root, errors, registry_path, f"schema validation failed at {field}: {error.message}")


def resolve_profile_reference(
    reference: str,
    profiles: dict,
    aliases: dict,
) -> tuple[str | None, list[str] | None]:
    """Resolve compatibility aliases to a concrete profile and report alias cycles."""
    current = reference
    path: list[str] = []
    while current in aliases:
        if current in path:
            cycle_start = path.index(current)
            return None, path[cycle_start:] + [current]
        path.append(current)
        current = aliases[current]
    if current not in profiles:
        return None, None
    return current, None


def validate_agent_model_profiles(root: Path, errors: list[str]) -> None:
    schema_path = root / "schemas" / "agent-model-profiles.schema.json"
    registry_path = root / "config" / "agent-model-profiles.yml"
    if not registry_path.exists():
        registry_path = root / "AGENT_MODEL_PROFILES.yml"
    names_path = root / "config" / "agent-names.yml"
    if not names_path.exists():
        names_path = root / "AGENT_NAMES.yml"
    try:
        schema = json.loads(schema_path.read_text(encoding="utf-8"))
        Draft202012Validator.check_schema(schema)
    except Exception as error:
        fail(root, errors, schema_path, f"cannot load a valid JSON Schema: {error}")
        return
    try:
        registry = yaml.safe_load(registry_path.read_text(encoding="utf-8"))
    except (OSError, yaml.YAMLError) as error:
        fail(root, errors, registry_path, f"cannot load agent model profiles: {error}")
        return
    validator = Draft202012Validator(schema)
    schema_errors = sorted(validator.iter_errors(registry), key=lambda item: list(item.path))
    for error in schema_errors:
        field = ".".join(map(str, error.absolute_path)) or "registry"
        fail(root, errors, registry_path, f"schema validation failed at {field}: {error.message}")
    if schema_errors or not isinstance(registry, dict):
        return
    try:
        names_registry = yaml.safe_load(names_path.read_text(encoding="utf-8"))
    except (OSError, yaml.YAMLError) as error:
        fail(root, errors, names_path, f"cannot load agent name registry: {error}")
        return

    names = names_registry.get("agent_names", []) if isinstance(names_registry, dict) else []
    profiles = registry["model_profiles"]
    aliases = registry.get("compatibility_aliases", {})

    for alias_name in aliases:
        if alias_name in profiles:
            fail(
                root,
                errors,
                registry_path,
                f"compatibility_aliases.{alias_name} must not shadow a model profile",
            )
            continue
        resolved, cycle = resolve_profile_reference(alias_name, profiles, aliases)
        if cycle:
            fail(
                root,
                errors,
                registry_path,
                "compatibility alias cycle detected: " + " -> ".join(cycle),
            )
        elif resolved is None:
            fail(
                root,
                errors,
                registry_path,
                f"compatibility_aliases.{alias_name} references unknown profile {aliases[alias_name]!r}",
            )

    for profile_name, profile in profiles.items():
        preferred_name = profile["preferred_agent_name"]
        fallback = profile["fallback_profile"]
        if preferred_name not in names:
            fail(
                root,
                errors,
                registry_path,
                f"model_profiles.{profile_name}.preferred_agent_name must be registered",
            )
        if fallback is None:
            continue
        resolved_fallback, alias_cycle = resolve_profile_reference(fallback, profiles, aliases)
        if alias_cycle:
            fail(
                root,
                errors,
                registry_path,
                f"model_profiles.{profile_name}.fallback_profile resolves through alias cycle: "
                + " -> ".join(alias_cycle),
            )
            continue
        if resolved_fallback is None:
            fail(
                root,
                errors,
                registry_path,
                f"model_profiles.{profile_name}.fallback_profile references unknown profile {fallback!r}",
            )
            continue
        if resolved_fallback == profile_name:
            fail(
                root,
                errors,
                registry_path,
                f"model_profiles.{profile_name}.fallback_profile must not resolve to itself",
            )
            continue
        source_tier = profile["capability_tier"]
        target_tier = profiles[resolved_fallback]["capability_tier"]
        if CAPABILITY_LEVEL[target_tier] < CAPABILITY_LEVEL[source_tier]:
            fail(
                root,
                errors,
                registry_path,
                f"model_profiles.{profile_name}.fallback_profile must not reduce capability "
                f"from {source_tier!r} to {target_tier!r}",
            )

    reported_cycles: set[frozenset[str]] = set()
    for profile_name in profiles:
        path: list[str] = []
        current: str | None = profile_name
        while current is not None and current in profiles:
            if current in path:
                cycle = frozenset(path[path.index(current) :])
                if cycle not in reported_cycles:
                    fail(
                        root,
                        errors,
                        registry_path,
                        "fallback_profile cycle detected: "
                        + " -> ".join(path[path.index(current) :] + [current]),
                    )
                    reported_cycles.add(cycle)
                break
            path.append(current)
            fallback = profiles[current]["fallback_profile"]
            if fallback is None:
                break
            resolved, alias_cycle = resolve_profile_reference(fallback, profiles, aliases)
            if alias_cycle or resolved is None:
                break
            current = resolved


NAMING_CONTRACT_START = "<!-- agent-display-name-contract:v1 -->"
NAMING_CONTRACT_END = "<!-- /agent-display-name-contract:v1 -->"
EXPECTED_NAMING_CONTRACT = """Every sub-agent must receive a registered romanized aquatic-creature name as `task_name` and human-readable identity. Use a lowercase ASCII base name from `.agents/AGENT_NAMES.yml`, such as `hirame` or `medaka`. An explicit request uses the registered name; otherwise Main reserves an available registered name before creation. Invalid and unregistered names are rejected by default. Projects must not edit the adopted registry; project additions belong in project-specific rules.

For every spawn, pass the reserved name at creation, retain the returned `agent_id`, and read back the observed display name. If a generated non-aquatic default appears, use the supported task or sub-agent rename operation with that `agent_id`, then read back the same agent again. Renaming the chat or parent thread title does not count. Record `verified`, `renamed_and_verified`, or `alias_only`; do not claim visible-name success before readback."""


def validate_agent_naming_contract(root: Path, errors: list[str]) -> None:
    """Require the complete versioned naming contract in distributed snapshots."""
    if (root / "AGENT_NAMING.md").exists():
        contract_paths = (root / "templates" / "standard-snapshot.md",)
    elif (root / "STANDARD.md").exists():
        contract_paths = (root / "STANDARD.md",)
    else:
        return

    for path in contract_paths:
        if not path.exists():
            errors.append(f"{path}: mandatory naming contract file is missing")
            continue
        content = path.read_text(encoding="utf-8")
        if (
            content.count(NAMING_CONTRACT_START) != 1
            or content.count(NAMING_CONTRACT_END) != 1
        ):
            errors.append(
                f"{path}: versioned naming contract markers must occur exactly once"
            )
            continue
        start = content.find(NAMING_CONTRACT_START)
        end = content.find(NAMING_CONTRACT_END)
        if end <= start:
            errors.append(f"{path}: versioned naming contract markers are out of order")
            continue
        start += len(NAMING_CONTRACT_START)
        observed = content[start:end].strip()
        if observed != EXPECTED_NAMING_CONTRACT:
            errors.append(f"{path}: versioned naming contract differs from the canonical lifecycle")


def validate_links(root: Path, errors: list[str]) -> None:
    for path in sorted(root.rglob("*.md")):
        if ".git" in path.parts:
            continue
        for target in LINK.findall(path.read_text(encoding="utf-8")):
            target = target.strip().split(maxsplit=1)[0].strip("<>")
            if not target or target.startswith(("#", "/", "//")) or ":" in target:
                continue
            destination = target.split("#", 1)[0].split("?", 1)[0]
            if destination and not (path.parent / unquote(destination)).exists():
                fail(root, errors, path, f"relative link target does not exist: {target}")


def validate_version(root: Path, errors: list[str]) -> None:
    version = (root / "VERSION").read_text(encoding="utf-8").strip()
    changelog = root / "CHANGELOG.md"
    match = CHANGELOG_VERSION.search(changelog.read_text(encoding="utf-8"))
    if not match:
        fail(root, errors, changelog, "does not contain a release version heading")
    elif match.group(1) != version:
        fail(root, errors, changelog, f"latest version {match.group(1)!r} does not match VERSION {version!r}")


def validate_whitespace(root: Path, errors: list[str]) -> None:
    for path in sorted(root.rglob("*")):
        if not path.is_file() or ".git" in path.parts:
            continue
        try:
            content = path.read_text(encoding="utf-8")
        except UnicodeDecodeError:
            continue
        if content and not content.endswith("\n"):
            fail(root, errors, path, "must end with a newline")
        for number, line in enumerate(content.splitlines(), 1):
            if line.rstrip(" \t") != line:
                fail(root, errors, path, f"trailing whitespace on line {number}")


def parse_args() -> tuple[Path, bool]:
    parser = ArgumentParser(description=__doc__)
    parser.add_argument(
        "--root",
        type=Path,
        default=DEFAULT_ROOT,
        help="repository or adopted .agents directory to validate (default: common repository root)",
    )
    parser.add_argument(
        "--knowledge-only",
        action="store_true",
        help="skip common-repository VERSION and CHANGELOG validation",
    )
    args = parser.parse_args()
    return args.root.resolve(), args.knowledge_only


def main() -> int:
    root, knowledge_only = parse_args()
    errors: list[str] = []
    validate_agent_names(root, errors)
    validate_agent_naming_contract(root, errors)
    validate_agent_model_profiles(root, errors)
    validate_knowledge(root, errors)
    validate_links(root, errors)
    if not knowledge_only:
        validate_version(root, errors)
    validate_whitespace(root, errors)
    if errors:
        print("Repository validation failed:", file=sys.stderr)
        print(*[f"- {error}" for error in errors], sep="\n", file=sys.stderr)
        return 1
    print("Repository validation passed.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
