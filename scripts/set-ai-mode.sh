#!/usr/bin/env bash
set -euo pipefail

MODE="${1:-}"
if [[ "$MODE" != "cursor" && "$MODE" != "local" ]]; then
  echo "Usage: $0 {cursor|local}"
  exit 1
fi

FILE="AI-MODE.yml"
if [[ ! -f "$FILE" ]]; then
  echo "Error: $FILE not found. Run from repo root."
  exit 1
fi

NOW="$(date -u +"%Y-%m-%dT%H:%M:%SZ")"

# Replace keys (keep simple YAML, no external deps)
# active_profile
sed -i.bak -E "s/^active_profile:.*$/active_profile: ${MODE}/" "$FILE" || true
# last_set_by
WHO="cursor"
if [[ "$MODE" == "local" ]]; then WHO="ollama"; fi
sed -i.bak -E "s/^last_set_by:.*$/last_set_by: "${WHO}"/" "$FILE" || true
sed -i.bak -E "s/^last_set_at:.*$/last_set_at: "${NOW}"/" "$FILE" || true

rm -f "${FILE}.bak" 2>/dev/null || true
echo "AI mode set to: ${MODE} (last_set_by=${WHO}, last_set_at=${NOW})"
