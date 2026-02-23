# COVERAGE — پوشش مستندسازی (Documentation Coverage)

Date: 2026-02-17

هدف: بفهمیم چه بخش‌هایی از سیستم هنوز داکیومنت ماژول ندارند یا ناقص‌اند.

---

## معیارهای پوشش (High-level)
برای هر ماژول:
- Has module doc? (yes/no)
- touches_code filled? (yes/no)
- validation_queries filled? (yes/no)
- smoke_checks present? (yes/no)
- ADRs linked when needed? (yes/no)
- Expansion memory exists? (yes/no)

---

## قانون خودکار
هر بار که Discovery Mode اجرا شد:
- یک entry اینجا ثبت کن (ماژول + نتیجه)
- و اگر ماژول جدید ساخته شد، status را “bootstrapped” کن.

(این فایل را AI به‌صورت سبک آپدیت می‌کند.)

---

## Entries

- 2026-02-23 — inventory-belzona: Feature «ستون COC + اسلایدر زوم/تمام‌صفحه» + رفع مسیر فایل. Discovery: no. Coverage: History و metrics و expansion آپدیت شد. Gate: no → backlog ثبت شد.
- 2026-02-23 — inventory-belzona: Refactor «بهینه‌سازی دیتاتیبل ورودها». Discovery: no. Coverage: doc موجود بود، History و metrics و expansion memory آپدیت شد.
- 2026-02-18 — inventory-belzona: Feature «آخرین مانده خروجی‌های پارت». Discovery: no. Coverage: doc موجود بود، History و metrics آپدیت شد.
