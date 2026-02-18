# 00-START-HERE-LOCAL — نقطه شروع برای Ollama / Local LLM

این فایل «Entrypoint» است.  
برای استفاده با Ollama (یا هر کلاینت لوکال)، محتوای این فایل را به عنوان **System/Preprompt** به مدل بده.

تاریخ: 2026-02-17

---

## هدف
کاربر فقط «نیت» را می‌گوید (مثل: «می‌خوام انبار بلزونا رو تغییر بدم: ...»).  
مدل باید خودش:
- از روی داکیومنت‌ها ماژول را پیدا کند
- وابستگی‌ها را resolve کند
- کد را برای Validation بررسی کند
- تغییر را micro-task کند
- و در پایان Docs/History/ADR/Memory را آپدیت کند

---

## دستور اجرای اجباری (برای مدل)
1) ابتدا فایل `AI-RULES-LOCAL.md` را بخوان و دقیق اجرا کن.
2) سپس طبق Resolver از `02-domains/INDEX.yml` ماژول را پیدا کن.
3) Work Queue را در `04-docops/work-queue.md` بساز و مرحله‌ای اجرا کن.
4) قبل از هر تغییر در کد، Code Validation انجام بده (touches_code + validation_queries).
5) بعد از هر تغییر، History/ADR/depends_on را آپدیت کن.
6) اگر کاربر گفت این بخش فعلاً تمام/متوقف است، Expansion Lite را طبق `04-docops/DOC-OPS-LOCAL.md` اجرا کن (با Gate تأیید کاربر).

---

## قالب پاسخ‌دهی (کم‌مصرف)
- خلاصهٔ کوتاه
- Work Queue (IDs)
- Patch پیشنهادی (diff یا اشاره دقیق)
- Checklist تست (smoke_checks)
- Doc patches (History/ADR) بعد از تأیید

---

## پیام کاربر (نمونه)
- «می‌خوام انبار بلزونا رو تغییر بدم: ...»
- «می‌خوام صفحهٔ X رو توسعه بدم: ...»
