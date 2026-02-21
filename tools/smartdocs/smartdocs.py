#!/usr/bin/env python3
"""
SmartDocs Guard CLI

Commands:
- start: opens a task and records expectations
- set-gate: records YES/NO
- close: verifies required artifacts were updated after started_at, then closes task
- status: prints current state

Goal: prevent LLM shortcutting by adding a mechanical gate outside the model.
"""

from __future__ import annotations

import argparse
import datetime as _dt
import json
import os
import sys
from typing import Any, Dict, List, Tuple

REPO_ROOT_MARKERS = ["AI-MODE.yml", ".cursorrules", "docs"]
STATE_REL = os.path.join("docs", "ai", "04-docops", "task_state.json")
UTC = _dt.timezone.utc

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

def check_close(repo_root: str, state: Dict[str, Any]) -> Tuple[bool, List[str]]:
    errors: List[str] = []
    started_ts = parse_started_ts(state)
    module_id = state.get("module_id")
    if not module_id:
        errors.append("state.module_id is missing (run: make task-start module=...)")
        return False, errors

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
    else:
        if not os.path.exists(expansion_mem):
            errors.append("Expansion memory file missing.")

    return (len(errors) == 0), errors

def cmd_start(args: argparse.Namespace) -> None:
    repo_root = find_repo_root(os.getcwd())
    state_path = os.path.join(repo_root, STATE_REL)
    state = load_json(state_path)

    task_id = f"task_{_dt.datetime.now(tz=UTC).strftime('%Y%m%d_%H%M%S')}"
    state.update({
        "status": "open",
        "task_id": task_id,
        "task_name": args.name,
        "module_id": args.module,
        "started_at": now_iso(),
        "closed_at": None,
        "gate_answer": None,
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
    print(f" - module_id: {args.module}")
    print(f" - name: {args.name}")

def cmd_set_gate(args: argparse.Namespace) -> None:
    repo_root = find_repo_root(os.getcwd())
    state_path = os.path.join(repo_root, STATE_REL)
    state = load_json(state_path)
    if state.get("status") != "open":
        raise SystemExit("ERROR: No open task. Run task-start first.")
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
        raise SystemExit("ERROR: No open task. Run task-start first.")

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

    ps = sub.add_parser("start", help="Open a new task")
    ps.add_argument("--module", required=True, help="module-id (e.g., inventory-belzona)")
    ps.add_argument("--name", required=True, help="task name")
    ps.add_argument("--notes", default="", help="optional notes")
    ps.set_defaults(func=cmd_start)

    pg = sub.add_parser("set-gate", help="Record gate answer yes/no")
    pg.add_argument("--answer", required=True, help="yes|no")
    pg.set_defaults(func=cmd_set_gate)

    pc = sub.add_parser("close", help="Verify artifacts and close the task")
    pc.set_defaults(func=cmd_close)

    pst = sub.add_parser("status", help="Show task state")
    pst.set_defaults(func=cmd_status)

    args = p.parse_args()
    args.func(args)

if __name__ == "__main__":
    main()
