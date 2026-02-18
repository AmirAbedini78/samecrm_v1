# MODE-SWITCH — استفاده یکپارچه (Cursor + Ollama) با یک تاریخچه

Date: 2026-02-17

این ریپو **یک** مجموعه مستندات دارد. تنها تفاوت: “پروفایل اجرا”.

## 1) سوییچ کردن پروفایل (بدون اشتباه)

### macOS/Linux (bash)
- Cursor:
  - `make ai-cursor`
- Ollama:
  - `make ai-local`

یا مستقیم:
- `./scripts/set-ai-mode.sh cursor`
- `./scripts/set-ai-mode.sh local`

### Windows (PowerShell)
- `powershell -ExecutionPolicy Bypass -File .\scripts\set-ai-mode.ps1 -Mode cursor`
- `powershell -ExecutionPolicy Bypass -File .\scripts\set-ai-mode.ps1 -Mode local`

## 2) بعدش چی؟
تو فقط «نیت» می‌گی:
- «می‌خوام انبار بلزونا رو تغییر بدم: ...»

AI طبق `AI-MODE.yml` تصمیم می‌گیرد:
- cursor → `docs/ai/AI-RULES.md` + `04-docops/DOC-OPS.md`
- local  → `docs/ai/AI-RULES-LOCAL.md` + `04-docops/DOC-OPS-LOCAL.md`

## 3) اصل طلایی جلوگیری از دوشاخه شدن
هیچ فایل تاریخچه/ADR/metrics/memory جدا نداریم. همه مشترک‌اند.
فقط پروفایل اجرا عوض می‌شود.
