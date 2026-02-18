.PHONY: ai-local ai-cursor show-mode

ai-local:
	@./scripts/set-ai-mode.sh local
	@echo "Local profile ready. Use: OLLAMA-BOOTSTRAP.md then only state intent."

ai-cursor:
	@./scripts/set-ai-mode.sh cursor
	@echo "Cursor profile ready. Use Cursor chat and only state intent."

show-mode:
	@cat AI-MODE.yml
