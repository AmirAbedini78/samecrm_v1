# ADR-002 — تولید CSS سفارشی در BootHelper و اعمال با data-page-route

تاریخ: 2026-02-17  
وضعیت: Proposed

## Context
استایل‌های سفارشی باید به‌صورت «اسکوپ‌دار» اعمال شوند تا یک صفحه، صفحهٔ دیگر را خراب نکند. همچنین نیاز است CSS بر اساس تنظیمات ذخیره‌شده تولید شود.

## Decision
CSS سفارشی از تنظیمات تولید شود و برای اسکوپ کردن صفحات از attribute مثل `data-page-route` در layout استفاده شود.

## Alternatives
- CSS سراسری بدون اسکوپ (ریسک تداخل بالا)
- فایل CSS جدا برای هر صفحه (مدیریت سخت‌تر)

## Consequences
- اثر مثبت: کنترل دقیق دامنهٔ اثر CSS؛ کاهش باگ‌های استایل بین صفحات.
- ریسک: اگر نام route ها یا attribute ها تغییر کند، CSS ممکن است بی‌اثر شود.
- کار بعدی: یک جدول نگاشت/قرارداد ثابت برای route → scope.

## References
- Domain Doc: `02-domains/theme-custom-styling-belzona.md`
