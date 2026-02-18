---
id: shared-theme-font
title: Shared Capability — Theme & Font Settings
type: shared-capability
reads:
  - 00-GOVERNANCE.md
depends_on:
  - 03-adr/ADR-001-settings2-json.md
touches_code:
  - resources/views/**/*.blade.php
  - app/Helpers/BootHelper.php
  - settings2 (storage)
smoke_checks:
  - "تغییر فونت/تم در پنل ادمین اعمال شود"
  - "fallback ها برای فونت/رنگ موجود باشد"
update_rules:
  - "تغییر ساختار JSON تنظیمات → ADR جدید یا به‌روزرسانی ADR-001"
---

# قابلیت مشترک: تنظیمات تم و فونت

## هدف
مدیریت تنظیمات ظاهری (فونت، تم، …) به‌صورت قابل پیکربندی.

## وضعیت فعلی (خلاصه)
- تنظیمات به‌صورت JSON در `settings2` نگهداری می‌شود.
- در زمان boot، CSS/کلاس‌ها بر اساس تنظیمات اعمال می‌شوند.

## قرارداد استفاده برای ماژول‌ها
هر ماژول UI که به فونت/تم حساس است:
- نباید مقادیر hardcode داشته باشد
- باید از کلاس‌ها/variables تعریف‌شده پیروی کند

## تاریخچه
- 2026-02-17 — ایجاد سند قابلیت مشترک.
