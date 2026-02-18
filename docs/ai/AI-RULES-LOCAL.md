# AI-RULES-LOCAL — قوانین اجرایی برای مدل‌های لوکال کوچک (Ollama / 8B–20B)

این فایل برای مدل‌هایی نوشته شده که context/توان استدلال محدودتر دارند.
هدف: کاربر فقط «نیت» را بگوید و AI با **ریزکارها (micro-tasks)** و **بودجهٔ فایل/کانتکست** جلو برود
و در پایان History/ADR/Memory را آپدیت کند.

تاریخ آخرین به‌روزرسانی: 2026-02-17

---

## 0) اصل بقا (Survival Principles)
- **کم بخوان، درست بخوان**: هر پاس، حداکثر چند فایل محدود.
- **Patch-first**: خروجی‌ها به شکل patch/diff کوتاه باشد، نه متن طولانی.
- **Verify-then-write**: قبل از ادعا، از کد/فایل شاهد بیاور.
- **No big jumps**: تغییرات بزرگ را به چند مرحلهٔ کوچک تقسیم کن.

---

## 1) ورودی کاربر (User Input)
کاربر فقط این را می‌گوید:
- «می‌خوام انبار بلزونا رو تغییر بدم: ...»
- «می‌خوام صفحهٔ X رو توسعه بدم: ...»

AI حق ندارد از کاربر بخواهد Scope یا فایل نام ببرد، مگر وقتی نام ماژول/صفحه مبهم باشد.

---

## 2) Resolver (همیشه کم‌هزینه)
1) `02-domains/INDEX.yml` را بخوان.
2) ماژول را resolve کن → مسیر فایل ماژول.
3) فقط Frontmatter ماژول را بخوان (نه کل فایل).
4) `reads` و `depends_on` را جمع کن، اما در هر پاس **حداکثر 3 سند** را باز کن.
5) اگر سندهای بیشتری لازم شد، در پاس بعدی ادامه بده.

---

## 3) بودجهٔ هر پاس (Pass Budget)
برای جلوگیری از کم آوردن مدل‌های کوچک:

- Max Docs per pass: **3**
- Max Code files per pass: **4**
- Max output: **کوتاه + bullet + patch**

قانون: اگر برای تصمیم‌گیری به فایل‌های بیشتری نیاز شد، **یک “WORK-QUEUE item”** بساز و پاس بعدی ادامه بده.

---

## 4) WORK-QUEUE (ریزکارها)
فایل: `04-docops/work-queue.md`

AI باید بعد از resolver یک صف بسازد:
- W1: Read docs (limit)
- W2: Validate code (limit)
- W3: Implement change (small patch)
- W4: Smoke check checklist
- W5: Update docs (History/ADR/depends_on)
- W6: Progressive Expansion (lite)

هر Work item باید:
- هدف
- ورودی‌ها (فایل‌ها)
- خروجی (patch یا checklist)
- معیار Done

---

## 5) Code Validation (اجباری برای مدل کوچک)
قبل از تغییر:
1) فقط فایل‌های `touches_code` (حداکثر 4) را باز کن.
2) با `validation_queries` پاسخ کوتاه و شاهددار بده.
3) اگر mismatch دیدی: “Mismatch Report” کوتاه + پیشنهاد patch کوچک.

---

## 6) Implementation Strategy (ریز و قابل برگشت)
- هر بار فقط **یک تغییر کوچک** اعمال کن.
- بعدش یک smoke checklist کوتاه بده.
- سپس به مرحله بعد برو.

---

## 7) Documentation Update (اجباری)
پس از هر تغییر:
- History entry به ماژول
- ADR اگر قرارداد/تصمیم معماری تغییر کرد
- Update `depends_on` اگر وابستگی جدید کشف شد
- Update memory (پایین)

---

## 8) Progressive Expansion — Lite Mode
فایل: `04-docops/DOC-OPS-LOCAL.md`

هدف: بعد از اتمام تغییر، AI فقط **یکی** از این‌ها را انتخاب کند و تکمیل کند:
- Key Files Map (مختصر)
- Data/State Notes
- Edge Cases (2 مورد)
- Testing Notes (چک‌لیست کوتاه)

سپس 3 سؤال صحت‌سنجی بپرسد و با تأیید کاربر Patch را به داکیومنت اضافه کند.

---

## 9) Expansion Memory (حافظهٔ پیشرفت)
برای هر ماژول یک فایل JSON داریم:
`04-docops/expansion/<module-id>.json`

AI باید بعد از هر Expansion lite این‌ها را آپدیت کند:
- last_updated
- reviewed_areas
- open_questions
- next_expansion_targets
- coverage (تقریبی)

---

## 10) Language Rule (دو‌زبانهٔ کنترل‌شده)
- identifiers و اصطلاحات فنی **انگلیسی**
- توضیحات **فارسی**
طبق `00-GOVERNANCE.md`

---

## Discovery (Local-lite)
اگر ماژول در INDEX پیدا نشد:
- Discovery را محدود انجام بده: فقط route/controller/view اصلی را پیدا کن (حداکثر 4 فایل)
- یک ماژول doc حداقلی بساز (frontmatter + touches_code + smoke_checks + history)
- سپس تسک را micro-task کن
(جزئیات کامل Discovery در Cursor انجام می‌شود.)
