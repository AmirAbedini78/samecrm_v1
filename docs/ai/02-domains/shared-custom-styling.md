---
id: shared-custom-styling
title: Shared Capability — Custom Styling (Scoped CSS)
type: shared-capability
reads:
  - 00-GOVERNANCE.md
  - 01-PROJECT-MAP.md
depends_on:
  - 03-adr/ADR-002-css-scope-data-page-route.md
touches_code:
  - resources/views/layouts/*.blade.php
  - app/Helpers/BootHelper.php
  - public/css/*.css
smoke_checks:
  - "تغییر یک صفحه، صفحهٔ دیگر را از نظر CSS خراب نکند"
  - "attribute اسکوپ (مثل data-page-route) روی body وجود داشته باشد"
update_rules:
validation_queries:
  - "attribute اسکوپ در layout دقیقاً چیست و روی کدام تگ ست می‌شود؟"
  - "الگوی انتخاب scope: route-name است یا چیز دیگر؟"
  - "CSS سفارشی در چه مرحله‌ای inject می‌شود و order آن نسبت به CSS اصلی چیست؟"
  - "هر تغییر در قرارداد اسکوپ یا تولید CSS → ADR جدید یا به‌روزرسانی ADR-002"
---

# قابلیت مشترک: کاستوم استایل اسکوپ‌دار (Scoped CSS)

## هدف
امکان اعمال استایل‌های سفارشی به **صورت محدود به یک صفحه/ماژول** تا از تداخل CSS بین صفحات جلوگیری شود.

## وضعیت فعلی (خلاصه)
- برای اسکوپ کردن استایل، از یک attribute در layout (مثلاً `data-page-route`) استفاده می‌شود.
- تولید/تزریق CSS سفارشی در سطح Boot/Helper انجام می‌شود.

## قرارداد استفاده برای ماژول‌ها
هر ماژولی که استایل سفارشی دارد باید:
1) یک «شناسهٔ اسکوپ» ثابت داشته باشد (معمولاً route یا نام صفحه)
2) CSS را زیر همان اسکوپ تولید کند
3) smoke_checks ماژول را شامل بررسی عدم تداخل با صفحات دیگر کند

## تاریخچه
- 2026-02-17 — ایجاد سند قابلیت مشترک برای اینکه ماژول‌ها وابستگی‌شان را صریح اعلام کنند.
