# PATHS — استاندارد مسیرها (Standard Layout)

برای جلوگیری از بهم ریختن مسیرها، این ساختار را ثابت نگه دارید:

- Root (ریشه پروژه):
  - `.cursorrules`
  - `AI-MODE.yml`
  - `Makefile`
  - `scripts/`
  - `MODE-SWITCH.md`
  - `OLLAMA-BOOTSTRAP.md`
  - `OLLAMA-SYSTEM-PROMPT.txt` (اختیاری)

- Documentation (همهٔ مستندات و حافظه):
  - `docs/ai/AI-RULES.md`
  - `docs/ai/AI-RULES-LOCAL.md`
  - `docs/ai/00-START-HERE.md`
  - `docs/ai/00-START-HERE-LOCAL.md`
  - `docs/ai/00-GOVERNANCE.md`
  - `docs/ai/01-PROJECT-MAP.md`
  - `docs/ai/02-domains/`
  - `docs/ai/03-adr/`
  - `docs/ai/04-docops/`

قاعده:
- Cursor فقط `.cursorrules` را از root می‌خواند.
- Ollama/Local: فایل شروع را از `docs/ai/00-START-HERE-LOCAL.md` می‌گیرد.

- `docs/ai/04-docops/DISCOVERY.md`
- `docs/ai/04-docops/COVERAGE.md`
