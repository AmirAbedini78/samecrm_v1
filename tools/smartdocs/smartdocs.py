#!/usr/bin/env python3
"""
SmartDocs Guard CLI v15
- Works on Windows/macOS/Linux
- Supports alias/fuzzy resolve via docs/ai/02-domains/INDEX.yml
- Allows starting without module-id; binds automatically on close if possible

Recommended usage (human-friendly via task script):
  task start "انبار بلزونا" "swap datepickers"
  task gate no
  task close
"""

from __future__ import annotations

import argparse
import datetime as _dt
import json
import os
import re
import sys
from typing import Any, Dict, List, Tuple, Optional

UTC = _dt.timezone.utc

REPO_ROOT_MARKERS = ["AI-MODE.yml", ".cursorrules", "docs"]
STATE_REL = os.path.join("docs", "ai", "04-docops", "task_state.json")
INDEX_REL = os.path.join("docs", "ai", "02-domains", "INDEX.yml")

UNBOUND = "__unbound__"

def now_iso() -> str:
    return _dt.datetime.now(tz=UTC).isoformat()

def find_repo_root(start: str) -> str:
    cur = os.path.abspath(start)
    while True:
        if all(os.path.exists(os.path.join(cur, m)) for m in REPO_ROOT_MARKERS):
            return cur
        parent = os.path.dirname(cur)
        if parent == cur:
            raise SystemExit("ERROR: Repo root not found. Run from repo root.")
        cur = parent

def load_json(path: str) -> Dict[str, Any]:
    if not os.path.exists(path):
        raise SystemExit(f"ERROR: Missing state file: {path}")
    with open(path, "r", encoding="utf-8") as f:
        return json.load(f)

def save_json(path: str, data: Dict[str, Any]) -> None:
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "w", encoding="utf-8") as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

def file_mtime(path: str) -> float:
    try:
        return os.path.getmtime(path)
    except FileNotFoundError:
        return 0.0

def parse_started_ts(state: Dict[str, Any]) -> float:
    started = state.get("started_at")
    if not started:
        return 0.0
    try:
        dt = _dt.datetime.fromisoformat(started.replace("Z", "+00:00"))
        return dt.timestamp()
    except Exception:
        return 0.0

def ensure_min_docops_files(repo_root: str) -> None:
    docops_dir = os.path.join(repo_root, "docs", "ai", "04-docops")
    os.makedirs(docops_dir, exist_ok=True)
    for rel, default in [
        ("COVERAGE.md", "# Coverage\n\n"),
        ("metrics.md", "# Metrics\n\n"),
        ("backlog.md", "# Backlog\n\n"),
    ]:
        p = os.path.join(docops_dir, rel)
        if not os.path.exists(p):
            with open(p, "w", encoding="utf-8") as f:
                f.write(default)

def require_exists(path: str, hint: str) -> None:
    if not os.path.exists(path):
        raise SystemExit(f"ERROR: Required file missing: {path}\nHint: {hint}")

def module_doc_path(repo_root: str, module_id: str) -> str:
    return os.path.join(repo_root, "docs", "ai", "02-domains", f"{module_id}.md")

def load_index_map(repo_root: str) -> Dict[str, str]:
    """
    INDEX.yml format (simple):
      modules:
        "alias": "02-domains/some-module.md"
    """
    index_path = os.path.join(repo_root, INDEX_REL)
    if not os.path.exists(index_path):
        return {}
    mapping: Dict[str, str] = {}
    with open(index_path, "r", encoding="utf-8") as f:
        for line in f:
            line = line.strip()
            if not line or line.startswith("#") or line == "modules:":
                continue
            # key: value
            m = re.match(r'^("?)(.+?)\1:\s*("?)(.+?)\3\s*$', line)
            if not m:
                continue
            key = m.group(2).strip()
            val = m.group(4).strip()
            mapping[key] = val
    return mapping

def normalize(s: str) -> str:
    return re.sub(r"\s+", " ", s.strip().lower())

def best_alias_match(index_map: Dict[str, str], phrase: str) -> List[Tuple[str, str, int]]:
    """
    Returns list of (alias, path, score) sorted by score desc.
    Simple scoring:
      exact = 100
      contains = 70
      token overlap = 10..60
    """
    p = normalize(phrase)
    if not p:
        return []
    ptoks = set(p.split(" "))
    out: List[Tuple[str, str, int]] = []
    for alias, path in index_map.items():
        a = normalize(alias)
        if a == p:
            out.append((alias, path, 100))
            continue
        if p in a or a in p:
            out.append((alias, path, 70))
            continue
        atoks = set(a.split(" "))
        inter = len(ptoks & atoks)
        union = len(ptoks | atoks) or 1
        score = int(60 * (inter / union))
        if score >= 15:
            out.append((alias, path, score))
    out.sort(key=lambda x: x[2], reverse=True)
    return out

def module_id_from_path(path: str) -> str:
    # 02-domains/inventory-belzona.md -> inventory-belzona
    base = os.path.basename(path)
    return os.path.splitext(base)[0]

def resolve_module(repo_root: str, module_or_alias: Optional[str]) -> Tuple[str, str, List[str]]:
    """
    Returns (module_id, method, candidates)
    method: exact|alias|fuzzy|direct|unbound
    """
    if not module_or_alias:
        return (UNBOUND, "unbound", [])

    raw = module_or_alias.strip()

    # If looks like module-id and doc exists
    direct_doc = module_doc_path(repo_root, raw)
    if os.path.exists(direct_doc):
        return (raw, "direct", [raw])

    index_map = load_index_map(repo_root)
    # exact key
    if raw in index_map:
        mid = module_id_from_path(index_map[raw])
        return (mid, "alias", [mid])

    # normalized exact
    for k, v in index_map.items():
        if normalize(k) == normalize(raw):
            mid = module_id_from_path(v)
            return (mid, "alias", [mid])

    matches = best_alias_match(index_map, raw)
    if matches:
        # if top score clearly better than second, accept
        top = matches[0]
        second = matches[1] if len(matches) > 1 else None
        if top[2] >= 70 and (not second or top[2] - second[2] >= 15):
            mid = module_id_from_path(top[1])
            return (mid, "fuzzy", [mid])
        # ambiguous -> return candidates
        cands = [module_id_from_path(m[1]) for m in matches[:5]]
        return (UNBOUND, "ambiguous", cands)

    # allow new module-id proposal (kebab-case)
    proposed = re.sub(r"[^a-zA-Z0-9\-]+", "-", normalize(raw)).strip("-")
    if proposed:
        return (proposed, "proposed", [proposed])

    return (UNBOUND, "unbound", [])

def discover_bind_on_close(repo_root: str, state: Dict[str, Any]) -> Tuple[bool, str, List[str]]:
    """
    If module_id is unbound, try to infer it from files changed after started_at.
    Strategy:
      1) If INDEX.yml modified after start, see if target_phrase exists as key.
      2) Find domain docs (*.md) modified after start; if only one -> bind.
      3) If multiple, rank by similarity to target_phrase/task_name.
    """
    started_ts = parse_started_ts(state)
    target = state.get("target_phrase") or ""
    task_name = state.get("task_name") or ""
    phrase = target or task_name

    index_map = load_index_map(repo_root)
    # (1) exact or fuzzy match from updated index
    mid, method, cands = resolve_module(repo_root, phrase)
    if mid != UNBOUND and method in ("alias", "fuzzy", "direct"):
        return True, mid, [mid]

    # (2) changed domain docs
    domains_dir = os.path.join(repo_root, "docs", "ai", "02-domains")
    changed: List[str] = []
    if os.path.isdir(domains_dir):
        for fn in os.listdir(domains_dir):
            if not fn.endswith(".md"):
                continue
            full = os.path.join(domains_dir, fn)
            if started_ts > 0 and file_mtime(full) > started_ts:
                changed.append(fn)

    if len(changed) == 1:
        return True, os.path.splitext(changed[0])[0], [os.path.splitext(changed[0])[0]]

    if changed:
        # rank by token overlap with phrase
        p = normalize(phrase)
        pt = set(p.split())
        scored=[]
        for fn in changed:
            mid2 = os.path.splitext(fn)[0]
            mt = set(normalize(mid2).split("-"))
            inter = len(pt & mt)
            score = inter
            scored.append((score, mid2))
        scored.sort(reverse=True)
        top_score = scored[0][0]
        best=[m for s,m in scored if s==top_score and s>0]
        if len(best)==1:
            return True, best[0], best
        return False, "ambiguous", [m for _,m in scored[:5]]

    return False, "unbound", []

def check_close(repo_root: str, state: Dict[str, Any]) -> Tuple[bool, List[str]]:
    errors: List[str] = []
    started_ts = parse_started_ts(state)

    module_id = state.get("module_id") or UNBOUND
    if module_id == UNBOUND:
        ok, mid, cands = discover_bind_on_close(repo_root, state)
        if not ok:
            errors.append("Module is unbound. Could not infer module-id for this task.")
            if cands:
                errors.append(f"Candidates: {', '.join(cands)}")
                errors.append("Fix: rerun start with --module, or ensure the module doc is created/updated, then retry close.")
            return False, errors
        # bind
        state["module_id"] = mid
        state.setdefault("resolution", {})
        state["resolution"].update({"method": "auto-bind-on-close", "candidates": cands})
        # persist binding before checks
        save_json(os.path.join(repo_root, STATE_REL), state)
        module_id = mid

    ensure_min_docops_files(repo_root)

    module_doc = module_doc_path(repo_root, module_id)
    coverage = os.path.join(repo_root, "docs", "ai", "04-docops", "COVERAGE.md")
    metrics = os.path.join(repo_root, "docs", "ai", "04-docops", "metrics.md")
    expansion_mem = os.path.join(repo_root, "docs", "ai", "04-docops", "expansion", f"{module_id}.json")
    backlog = os.path.join(repo_root, "docs", "ai", "04-docops", "backlog.md")

    require_exists(module_doc, f"Create/update module doc at {module_doc}")
    require_exists(coverage, "SmartDocs requires coverage log.")
    require_exists(metrics, "SmartDocs requires metrics log.")
    require_exists(backlog, "SmartDocs requires backlog file.")

    if started_ts > 0:
        if file_mtime(module_doc) <= started_ts:
            errors.append(f"Module doc not updated after start: {module_doc}")
        if file_mtime(metrics) <= started_ts:
            errors.append("metrics.md not updated after start.")
        if file_mtime(coverage) <= started_ts:
            errors.append("COVERAGE.md not updated after start.")
        if (not os.path.exists(expansion_mem)) or file_mtime(expansion_mem) <= started_ts:
            errors.append("Expansion memory not updated after start (module json).")

        gate = (state.get("gate_answer") or "").lower()
        if gate == "no" and file_mtime(backlog) <= started_ts:
            errors.append("Gate=NO but backlog.md not updated after start.")
        if gate not in ("yes", "no"):
            errors.append("Gate answer not recorded. Run: task gate yes|no")
    else:
        if not os.path.exists(expansion_mem):
            errors.append("Expansion memory file missing.")

    return (len(errors) == 0), errors

def cmd_start(args: argparse.Namespace) -> None:
    repo_root = find_repo_root(os.getcwd())
    state_path = os.path.join(repo_root, STATE_REL)
    state = load_json(state_path)

    # resolve module from free-form target/module
    module_input = args.module
    target_phrase = args.target or args.module or ""
    mid, method, cands = resolve_module(repo_root, module_input)

    if method == "ambiguous":
        print("ERROR: Ambiguous module reference. Provide a clearer module/alias.", file=sys.stderr)
        print("Candidates:", ", ".join(cands), file=sys.stderr)
        sys.exit(3)

    task_id = f"task_{_dt.datetime.now(tz=UTC).strftime('%Y%m%d_%H%M%S')}"
    state.update({
        "schema_version": 1,
        "status": "open",
        "task_id": task_id,
        "task_name": args.name,
        "target_phrase": target_phrase,
        "module_id": mid if mid else UNBOUND,
        "started_at": now_iso(),
        "closed_at": None,
        "gate_answer": None,
        "resolution": {"method": method, "candidates": cands},
        "expected_artifacts": {
            "history_updated": True,
            "metrics_updated": True,
            "coverage_updated": True,
            "expansion_memory_updated": True,
            "backlog_updated_if_gate_no": True
        },
        "notes": args.notes or ""
    })
    save_json(state_path, state)
    print(f"OK: Task opened: {task_id}")
    print(f" - target: {target_phrase}")
    print(f" - module_id: {state['module_id']} (resolution: {method})")
    if cands and method in ("fuzzy", "alias"):
        print(f" - matched from aliases: {', '.join(cands)}")

def cmd_gate(args: argparse.Namespace) -> None:
    repo_root = find_repo_root(os.getcwd())
    state_path = os.path.join(repo_root, STATE_REL)
    state = load_json(state_path)
    if state.get("status") != "open":
        raise SystemExit("ERROR: No open task. Run task start first.")
    ans = args.answer.lower()
    if ans not in ("yes", "no"):
        raise SystemExit("ERROR: gate answer must be yes|no")
    state["gate_answer"] = ans
    save_json(state_path, state)
    print(f"OK: Gate answer recorded: {ans}")

def cmd_close(args: argparse.Namespace) -> None:
    repo_root = find_repo_root(os.getcwd())
    state_path = os.path.join(repo_root, STATE_REL)
    state = load_json(state_path)
    if state.get("status") != "open":
        raise SystemExit("ERROR: No open task. Run task start first.")

    ok, errors = check_close(repo_root, state)
    if not ok:
        print("FAIL: Task cannot be closed. Missing required artifacts:", file=sys.stderr)
        for e in errors:
            print(f" - {e}", file=sys.stderr)
        sys.exit(2)

    state["status"] = "closed"
    state["closed_at"] = now_iso()
    save_json(state_path, state)
    print("OK: Task closed. Ready for the next task.")

def cmd_status(args: argparse.Namespace) -> None:
    repo_root = find_repo_root(os.getcwd())
    state_path = os.path.join(repo_root, STATE_REL)
    state = load_json(state_path)
    print(json.dumps(state, ensure_ascii=False, indent=2))

def main() -> None:
    p = argparse.ArgumentParser(prog="smartdocs")
    sub = p.add_subparsers(dest="cmd", required=True)

    ps = sub.add_parser("start", help="Open a new task (module optional)")
    ps.add_argument("--module", default="", help="module-id or alias (optional)")
    ps.add_argument("--target", default="", help="free-form target phrase (optional)")
    ps.add_argument("--name", required=True, help="task name")
    ps.add_argument("--notes", default="", help="optional notes")
    ps.set_defaults(func=cmd_start)

    pg = sub.add_parser("gate", help="Record gate answer yes/no")
    pg.add_argument("--answer", required=True, help="yes|no")
    pg.set_defaults(func=cmd_gate)

    pc = sub.add_parser("close", help="Verify artifacts and close the task")
    pc.set_defaults(func=cmd_close)

    pst = sub.add_parser("status", help="Show task state")
    pst.set_defaults(func=cmd_status)

    args = p.parse_args()
    args.func(args)

if __name__ == "__main__":
    main()
