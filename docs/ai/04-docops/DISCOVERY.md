# DISCOVERY — Self-Bootstrapping Documentation (Discovery Mode)

Date: 2026-02-17

این سند زمانی استفاده می‌شود که کاربر تسکی دربارهٔ یک بخش/ماژول می‌دهد اما:
- در `docs/ai/02-domains/INDEX.yml` یافت نمی‌شود، یا
- فایل ماژول وجود ندارد، یا
- داکیومنت آن‌قدر ناقص است که نمی‌تواند راهنمای تغییر باشد.

هدف: AI ابتدا «واقعیت کد» را پیدا کند، سپس داکیومنت ماژول را **بسازد/ترمیم کند**،
بعد تسک را انجام دهد و تاریخچه/معماری را ثبت کند.

---

## 1) Trigger conditions
Discovery Mode را فعال کن اگر یکی از این‌ها رخ داد:
- Resolver در INDEX به هیچ فایل ماژولی نرسید.
- فایل ماژول پیدا شد ولی Frontmatter ندارد/خراب است.
- داکیومنت ماژول وجود دارد ولی `touches_code` یا `validation_queries` عملاً خالی است و برای Validation کافی نیست.

---

## 2) Discovery Strategy (Strong Model / Cursor)
برای مدل قوی، Discovery باید «کامل‌تر» باشد (در شعاع منطقی ماژول).

### Step A — Entry points
به ترتیب این‌ها را پیدا کن:
1) Route / URL / page identifier (اگر کاربر نام صفحه داد)
2) Controller / Action
3) View / Blade
4) Related Models / Queries
5) JS/CSS assets مربوط
6) Policies/Middleware (اگر امنیت/دسترسی دارد)

اگر کاربر نام دقیق route را نگفت، از clues استفاده کن:
- نام صفحه
- متن UI
- نام فایل‌ها
- مسیرهای views

### Step B — Follow references (2 hops max)
از entry point ها:
- import/include/extends/partial ها را دنبال کن
- service/helper هایی که استفاده می‌شوند را دنبال کن
- جایی که داده ساخته/خوانده می‌شود را پیدا کن

هدف: نقشهٔ فایل‌ها + جریان داده/رفتار.

---

## 3) Build or Repair the Module Doc
پس از Discovery، این فایل را بساز/آپدیت کن:

`docs/ai/02-domains/<module-id>.md`

### Frontmatter (حداقل‌ها)
- id (English kebab-case)
- title
- type: module
- reads: [00-GOVERNANCE.md, 01-PROJECT-MAP.md]
- depends_on: (shared capabilities + ADRهای مرتبط)
- touches_code: (فایل‌های واقعی کشف‌شده)
- smoke_checks: (چک‌های سریع واقعی)
- validation_queries: (سؤال‌های شاهددار)
- update_rules: (کجا History/ADR آپدیت شود)
- aliases: (نام‌های فارسی/انگلیسی که کاربر استفاده می‌کند)

### Body (حداقل‌ها)
- Current State Summary (بر اساس کد)
- Key Files Map (5–15 فایل)
- Data & State notes
- Risks & Traps (چیزهایی که احتمالاً می‌شکند)

### History (اولین entry)
- تاریخ: امروز
- نوع: “Bootstrapped by Discovery”
- فایل‌های کلیدی
- فرض‌ها (اگر چیزی را نتوانستی با کد قطعی کنی)

---

## 4) Update INDEX
پس از ساخت ماژول:
- `docs/ai/02-domains/INDEX.yml` را آپدیت کن تا:
  - نام فارسی‌ای که کاربر گفت → مسیر فایل ماژول

---

## 5) Then do the task
وقتی ماژول doc ساخته شد:
- به چرخهٔ اصلی برگرد (Validate → Change → Update Docs → Expansion)

---

## 6) Quality Gate
اگر در Discovery به ابهام مهم رسیدی:
- سؤال تست‌محور بپرس
- و پاسخ را بعد از تأیید کاربر وارد داکیومنت کن (Gate)
