# 00-START-HERE — Unified Entrypoint (Cursor + Ollama)

Date: 2026-02-17

این ریپو **یک** مجموعه مستندات دارد و فقط “حالت اجرا” فرق می‌کند.
حالت اجرا از روی فایل `AI-MODE.yml` تعیین می‌شود.

## کاربر چه می‌گوید؟
فقط نیت:
- «می‌خوام انبار بلزونا رو تغییر بدم: ...»
- «می‌خوام صفحهٔ X رو توسعه بدم: ...»

## AI چه کار می‌کند؟
1) در شروع هر سشن، `AI-MODE.yml` را روی حالت درست ست کن:
   - Cursor/Cloud → `active_profile: cursor`
   - Ollama/Local → `active_profile: local`

2) سپس بر اساس حالت:
   - اگر `cursor` → قوانین `AI-RULES.md` + `04-docops/DOC-OPS.md`
   - اگر `local`  → قوانین `AI-RULES-LOCAL.md` + `04-docops/DOC-OPS-LOCAL.md`

3) Resolver همیشه یکسان است:
   - `02-domains/INDEX.yml` → ماژول
   - `reads/depends_on` → خواندن بازگشتی
   - `touches_code + validation_queries` → Validation با کد
   - سپس تغییر
   - سپس آپدیت Docs (History/ADR/depends_on)
   - سپس Expansion (full یا lite) با Gate

## قانون سازگاری
History/ADR/Memory همیشه در همین پوشه‌ها ثبت می‌شوند؛
بنابراین بین Cursor و Ollama “آخرین وضعیت” مشترک می‌ماند.

---

## وقتی داکیومنت وجود ندارد
اگر ماژول در INDEX پیدا نشد یا داکیومنت ناقص بود:
- Discovery Mode را طبق `docs/ai/04-docops/DISCOVERY.md` اجرا کن
- سپس ماژول doc را بساز و بعد تسک را انجام بده


---

## سطح گسترش (Expansion level)
گسترش پیشرونده فقط از مسیر `docs/ai/04-docops/DOC-OPS.md` اجرا می‌شود.
برای مدل‌های قوی می‌توانید سطح را `deep` انتخاب کنید (با Gate تأیید کاربر).
