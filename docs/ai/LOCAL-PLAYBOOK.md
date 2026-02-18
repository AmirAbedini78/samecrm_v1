# LOCAL-PLAYBOOK — استفادهٔ بسیار کوتاه (برای Ollama / مدل‌های لوکال)

تاریخ: 2026-02-17

## پرامپت کاربر (خیلی کوتاه)
فقط یکی از این‌ها:

- «می‌خوام انبار بلزونا رو تغییر بدم: ...»
- «می‌خوام صفحهٔ X رو توسعه بدم: ...»

## انتظار از AI (بدون اینکه کاربر بگه)
AI باید طبق `AI-RULES-LOCAL.md` عمل کند:
- resolve از روی INDEX
- ساخت WORK-QUEUE
- validate با کد (touches_code + validation_queries)
- تغییرات ریز (micro-patches)
- update docs (History/ADR/depends_on)
- expansion lite با Gate (تأیید کاربر)

## نکتهٔ مهم
برای مدل‌های 8B:
- هر پاس حداکثر چند فایل
- خروجی‌ها کوتاه و patch محور
