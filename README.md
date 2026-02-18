# مستندات هوشمند پروژه — samecrm_v1

این پوشه «حافظهٔ خارجی پروژه» است: هم برای توسعه‌دهنده، هم برای ابزارهای AI مثل Cursor.  
هدف: **تصمیم‌گیری سریع‌تر، جلوگیری از دوباره‌کاری، و داشتن تاریخچه + معماری قابل اتکا**.

## ساختار پوشه

- `00-GOVERNANCE.md`  
  قراردادهای داکیومنت‌نویسی (قوانین، اسکوپ، نام‌گذاری، Definition of Done)

- `01-PROJECT-MAP.md`  
  نقشهٔ کلی پروژه (معماری در سطح بالا، ماژول‌ها، جریان‌های اصلی داده)

- `02-domains/`  
  داکیومنت‌های فیچر/دامنه (هر دامنه یک فایل؛ داخلش Scope، وضعیت فعلی، تاریخچهٔ خلاصه)

- `03-adr/`  
  تصمیم‌های معماری (ADR): «مسئله چی بود؟ چرا این انتخاب؟ اثراتش؟»

## قانون طلایی

هیچ تسکی «تمام» نیست مگر اینکه:
1) کد تغییر کرده باشد  
2) **مستندات مرتبط** آپدیت شده باشد (دامنه + در صورت نیاز ADR)

> نسخهٔ بازطراحی‌شده توسط ChatGPT — تاریخ: 2026-02-17

---

## نحوهٔ استفاده (Cursor)

1) فقط نام ماژول + شرح تسک را بگو (مثلاً «انبار بلزونا»).
2) از AI بخواه طبق پروتکل Dependency Resolver عمل کند (در `00-GOVERNANCE.md`).

نمونه پیام آماده:

می‌خوام «انبار بلزونا» رو تغییر بدم: ...  
طبق پروتکل Dependency Resolver، از `02-domains/INDEX.yml` ماژول رو پیدا کن، فایلش و همهٔ `reads/depends_on` رو بازگشتی بخون، وضعیت فعلی رو خلاصه کن، بعد تغییر رو انجام بده و در پایان History/ADR/depends_on را آپدیت کن.

---

## پرامپت کوتاه (فقط نیت)
- «می‌خوام انبار بلزونا رو تغییر بدم: ...»
- «می‌خوام صفحهٔ X رو توسعه بدم: ...»

هوشمندی و گسترش پیشرونده:
- `AI-RULES.md`
- `04-docops/DOC-OPS.md`

---

## سیاست زبان
این مستندات «فارسی + انگلیسیِ فنی» هستند:
- توضیحات: فارسی
- کلیدواژه‌ها و identifiers فنی: انگلیسی (طبق `00-GOVERNANCE.md`)

---

# Cursor Edition (Cloud / Strong Models)

This package is optimized for Cursor or strong cloud LLMs.

Use:
- `.cursorrules`
- `AI-RULES.md`
- `04-docops/DOC-OPS.md`

Ignore:
- AI-RULES-LOCAL.md (only for Ollama)

---

# Unified Mode Switching

This package supports both Cursor (cloud/strong models) and Ollama (local/small models)
WITHOUT splitting documentation history.

Single source of truth:
- Docs, ADRs, metrics, expansion memory are shared.
- Only the execution profile changes via `AI-MODE.yml`.

Start points:
- Cursor: `.cursorrules` sets mode to `cursor`, then runs `AI-RULES.md`.
- Ollama: use `OLLAMA-BOOTSTRAP.md` + `00-START-HERE.md` with `AI-RULES-LOCAL.md`.

---

## Mode switching (recommended)
See `MODE-SWITCH.md`.

Quick:
- `make ai-cursor`
- `make ai-local`

---

## Standard layout (recommended)
Put documentation under `docs/ai/` exactly as provided.

Keep at repo root:
- `.cursorrules` (Cursor reads only from root)
- `AI-MODE.yml`
- `Makefile`
- `scripts/`

See `PATHS.md` for the full map.

---

## Discovery Mode
اگر برای یک بخش داکیومنت ندارید، سیستم وارد Discovery Mode می‌شود و از روی کد:
- ماژول doc را می‌سازد
- INDEX را آپدیت می‌کند
- سپس تسک را انجام می‌دهد

See: `docs/ai/04-docops/DISCOVERY.md`

---

## Expansion levels
See `docs/ai/04-docops/DOC-OPS.md` (`expansion_level`: lite | normal | deep).
