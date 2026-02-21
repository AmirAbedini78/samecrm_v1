.PHONY: ai-local ai-cursor show-mode

ai-local:
	@./scripts/set-ai-mode.sh local
	@echo "Local profile ready. Use: OLLAMA-BOOTSTRAP.md then only state intent."

ai-cursor:
	@./scripts/set-ai-mode.sh cursor
	@echo "Cursor profile ready. Use Cursor chat and only state intent."

show-mode:
	@cat AI-MODE.yml
# --- SmartDocs Guard CLI (v14) ---
SMARTDOCS=python3 tools/smartdocs/smartdocs.py

task-start:
	@$(SMARTDOCS) start --module="$(module)" --name="$(name)" --notes="$(notes)"

task-gate-yes:
	@$(SMARTDOCS) set-gate --answer=yes

task-gate-no:
	@$(SMARTDOCS) set-gate --answer=no

task-close:
	@$(SMARTDOCS) close

task-status:
	@$(SMARTDOCS) status