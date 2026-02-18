# DOC-OPS — چرخهٔ «گسترش پیشروندهٔ داکیومنت» (Documentation Ops)

این سند می‌گوید بعد از انجام هر تغییر/توسعه در کد و آپدیت اولیهٔ داکیومنت، AI باید یک مرحلهٔ اضافه انجام دهد:
**کالبدشکافی و تکمیل پیشروندهٔ دانش همان ماژول**.

هدف: وسط پروژه هم بتوانید به‌تدریج «معماری و جزئیات» را کامل کنید، بدون اینکه مجبور باشید یک‌جا کل پروژه را مستند کنید.

تاریخ آخرین به‌روزرسانی: 2026-02-17

---

---

## 0) تنظیم سطح گسترش (expansion_level)

در هر تسک، قبل از اجرای Expansion Loop سطح را تعیین کن:

- `lite`: فقط Key Files Map + 1-2 Edge cases + checklist تست
- `normal`: خروجی‌های استاندارد Expansion
- `deep`: برای مدل‌های قوی — علاوه بر normal:
  - یک hop اضافی از reference ها (imports/includes/services) را بررسی کن
  - قراردادهای پنهان (implicit contracts) و وابستگی‌های مخفی را استخراج کن
  - پیشنهاد ADR اگر لازم است

پیش‌فرض پیشنهادی:
- Cursor/Cloud: `deep`
- Ollama/Local: `lite`

قانون:
- Expansion فقط از مسیر همین DOC-OPS انجام می‌شود (جلوگیری از دوبارکاری).

## 1) چرخهٔ اصلی (Main Cycle)

برای هر تسک:
1) Resolve + Read docs (طبق `AI-RULES.md`)
2) Validate with code (Mismatch Report)
3) Apply change
4) Smoke checks
5) Update docs (History/ADR/depends_on)

**سپس وارد چرخهٔ گسترش می‌شویم (Expansion Loop).**

---

## 2) چرخهٔ گسترش (Expansion Loop)

### Trigger
بعد از اتمام تغییرات و آپدیت اولیهٔ داکیومنت، AI باید بپرسد:
- «تغییرات این بخش فعلاً *تمام شده* یا *برای مدتی متوقف می‌شود*؟»

**اگر کاربر گفت “فعلاً تمام/متوقف” → Expansion Loop اجرا می‌شود.**  
اگر کاربر گفت “ادامه دارد” → Expansion Loop فعلاً اجرا نشود (برای جلوگیری از مزاحمت).

### Expansion Loop Steps
برای همان ماژول:

1) **Load Expansion Memory**
   - فایل حافظهٔ ماژول را بخوان: `04-docops/expansion/<module-id>.json`
   - بفهم دفعهٔ قبل تا کجا پیش رفته (coverage، سوال‌های باز، نواحی بررسی‌شده)

2) **Targeted Code Exploration (شعاع کنترل‌شده)**
   - از `touches_code` شروع کن.
   - سپس فقط 1 حلقهٔ ارتباطی گسترش بده:
     - فایل‌های import شده / کلاس‌های استفاده‌شده / view partialها / routeها / policyها
   - هدف: «نقشهٔ دقیق‌تر» بساز، نه خواندن کل پروژه.

3) **Generate Knowledge Candidates**
   خروجی پیشنهادی AI باید در قالب “کاندید” باشد (نه حقیقت قطعی):
   - Architecture Notes (نقش‌ها، جریان‌ها، مرزها)
   - Key Files Map (فایل‌ها و وظیفه‌شان)
   - Data & State (چه داده‌ای کجا ذخیره می‌شود)
   - Edge Cases / Failure Modes
   - Testing Suggestions

4) **Ask for Verification (Gate)**
   AI باید 3 تا 7 سؤال تست‌محور بپرسد تا کاربر صحت را تأیید کند.

5) **Apply Documentation Patch**
   فقط اگر کاربر تأیید کرد (یا اصلاح کرد)، AI حق دارد:
   - بخش‌های پیشنهادی را به فایل ماژول اضافه کند
   - و `expansion/<module-id>.json` را آپدیت کند (حافظهٔ پیشرفت)

6) **Self-check**
   AI باید در `04-docops/metrics.md` یک entry کوتاه اضافه کند:
   - این بار چه چیزهایی اضافه شد؟
   - mismatch داشتیم؟
   - سؤال‌های باز باقی ماند؟

---

## 3) کنترل مزاحمت (Noise Control)
- Expansion Loop هر بار حداکثر چند فایل محدود را بررسی کند.
- اگر سؤال‌ها زیاد شد، باید آن‌ها را به backlog ببرد: `04-docops/backlog.md`

---

## 4) خروجی‌های استاندارد Expansion
- Patch پیشنهادی برای داکیومنت
- Verification Checklist
- Update to Expansion Memory (JSON)

---

## 5) نکتهٔ زبان
در Patchهای پیشنهادیِ Expansion:
- توضیح‌ها فارسی
- identifiers/اصطلاحات فنی انگلیسی (طبق `00-GOVERNANCE.md`)
