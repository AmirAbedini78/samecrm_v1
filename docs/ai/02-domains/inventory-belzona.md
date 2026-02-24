---
id: inventory-belzona
title: ماژول — انبار بلزونا (Belzona Inventory)
type: module
reads:
  - 00-GOVERNANCE.md
  - 01-PROJECT-MAP.md
depends_on:
  - 02-domains/shared-custom-styling.md
  - 02-domains/shared-theme-font.md
  - 02-domains/shared-datatable.md
  - 03-adr/ADR-001-settings2-json.md
  - 03-adr/ADR-002-css-scope-data-page-route.md
touches_code:
  - app/Http/Controllers/**/*
  - resources/views/**/*
  - resources/js/**/*
  - app/Helpers/BootHelper.php
smoke_checks:
  - "صفحهٔ انبار بلزونا باز شود"
  - "استایل‌ها فقط در اسکوپ بلزونا اعمال شوند"
  - "دیتاتیبل (جستجو/فیلتر/صفحه‌بندی) درست کار کند"
update_rules:
  - "هر تغییر UI/لیست بلزونا → History همین فایل"
  - "هر تغییر در اسکوپ CSS یا تولید CSS → ADR لازم"
  - "هر تغییر در تنظیمات تم/فونت → آپدیت shared-theme-font + ADR-001 در صورت نیاز"
aliases:
  - "انبار بلزونا"
  - "Belzona Inventory"
validation_queries:
  - "layout اصلی: آیا attribute اسکوپ (مثل data-page-route) روی body/set شده؟ کجا؟"
  - "BootHelper: CSS سفارشی دقیقاً کجا تولید/تزریق می‌شود؟"
  - "تنظیمات تم/فونت: JSON در settings2 با چه کلیدی ذخیره می‌شود و کجا خوانده می‌شود؟"
  - "صفحه/route بلزونا: view/controller اصلی کدام است؟"
  - "datatable: از چه کامپوننت/partial یا js استفاده می‌کند؟"
  - "inventory-belzona"
---

# 02 — دامنه: Theme / Custom Styling / Font Settings / Belzona Inventory

## وضعیت فعلی / قوانین اجرایی

## استایل سفارشی و تنظیمات فونت (تم، انبار بلزونا، و صفحات دیگر)

### وضعیت فعلی

- **مودال تم از نوار بالا:** در ناوبار (topnav) یک آیکون تم (برای ادمین) وجود دارد که با کلیک، مودال تنظیمات تم را باز می‌کند (همان محتوای `/app/settings/theme`): تم اصلی، Head/Body، CSS سفارشی، و **بخش فونت و ظاهر**.
- **بخش فونت و ظاهر:** در تنظیمات تم یک بخش «فونت و ظاهر» وجود دارد با:
  - **حوزه اعمال:** «کل نرم‌افزار» یا «فقط این صفحه».
  - در حالت «فقط این صفحه» یک dropdown برای انتخاب صفحه (فعلاً فقط «انبار بلزونا») وجود دارد.
  - برای هر دسته: **نوع فونت، اندازه، رنگ** برای: عنوان دیتاتیبل، عنوان‌های صفحه، متن دیتاتیبل، متن صفحه، دکمه‌ها.
- **ذخیره:** تنظیمات فونت در جدول `settings2` در ستون `settings2_font_settings` (JSON) ذخیره می‌شود. در `BootHelper` این مقدار خوانده شده و به صورت CSS به `config('css.application')` اضافه می‌شود.
- **اعمال روی صفحه:** برای اعمال «فقط این صفحه»، روی `<body>` در layout از متغیر `$page['page_route']` استفاده می‌شود و در wrapper مقدار `data-page-route="{{ $page['page_route'] ?? '' }}"` ست می‌شود. سلکتورهای CSS در BootHelper برای حالت «فقط این صفحه» به صورت `[data-page-route="belzona-inventory"] .کلاس` ساخته می‌شوند.
- **کلاس‌های CSS برای انبار بلزونا:** در ویوی انبار بلزونا (`pages/belzona-inventory/index.blade.php`) این کلاس‌ها باید روی المان‌ها باشند تا استایل فونت اعمال شود:
  - `belzona-datatable-title` — عنوان کارت دیتاتیبل و هدرهای جدول (`<th>`).
  - `belzona-page-titles` — عنوان صفحه، زیرعنوان‌ها، لیبل‌ها.
  - `belzona-datatable-text` — جدول (و سلول‌ها)، فیلدها، اعداد داخل کارت‌ها.
  - `belzona-page-text` — متن‌های توضیحی صفحه.
  - `belzona-buttons` — همهٔ دکمه‌ها و لینک‌های دکمه‌مانند.
- **کنترلر انبار بلزونا:** در `BelzonaInventoryController` در متد `pageSettings` مقدار `'page_route' => 'belzona-inventory'` در آرایهٔ `$page` وجود دارد تا `data-page-route` روی body ست شود.

### تاریخچه (خلاصه)

- **افزایش فیچر تم/فونت:** اضافه شدن مودال تم از ناوبار، بخش فونت و ظاهر در تنظیمات تم، ذخیره در `settings2_font_settings`، تولید CSS در BootHelper، اسکوپ «کل نرم‌افزار» / «فقط این صفحه» با `data-page-route` و کلاس‌های `belzona-*` در صفحه انبار بلزونا.
- **بازگردانی کلاس‌ها پس از تغییرات دیگر:** بعد از تغییر نمایش تاریخ شلف لایف در صفحه انبار بلزونا، کلاس‌های فونت و `page_route` از ویو و کنترلر حذف شده بودند؛ دوباره به ویو و کنترلر اضافه شد تا استایل فونت بدون تغییر در منطق شلف لایف کار کند.

### اگر در این حوزه کار می‌کنی

- **تغییر در صفحه انبار بلزونا (مثلاً جدول، فیلتر، شلف لایف):** کلاس‌های `belzona-datatable-title`, `belzona-page-titles`, `belzona-datatable-text`, `belzona-page-text`, `belzona-buttons` را روی المان‌های مربوط حفظ کن (یا در المان‌های جدید اضافه کن). مقدار `page_route` را در `pageSettings` کنترلر انبار بلزونا حذف نکن.
- **اضافه کردن صفحهٔ جدید که باید استایل سفارشی فونت داشته باشد:**  
  (۱) در کنترلر آن صفحه در متد مشابه `pageSettings` مقدار `'page_route' => 'شناسه-یکتا'` (مثلاً `inventory` یا `reports-x`) به `$page` اضافه کن.  
  (۲) در ویوی آن صفحه همان کلاس‌ها را با پیشوند مناسب استفاده کن (فعلاً در BootHelper فقط `belzona-*` و اسکوپ `belzona-inventory` تعریف شده؛ برای صفحهٔ جدید یا همان کلاس‌ها را روی آن صفحه بگذار یا در تنظیمات تم و BootHelper شناسهٔ صفحهٔ جدید را اضافه کن).  
  (۳) در انتهای کار این بخش از داکیومنت را به‌روز کن (وضعیت فعلی + یک خط در تاریخچه).
- **تغییر در تنظیمات تم، مودال تم، یا ذخیرهٔ فونت:** بعد از تغییر، این بخش را به‌روز کن.
- **تغییر در BootHelper یا layout برای استایل/فونت:** بعد از تغییر، این بخش را به‌روز کن.

---

## تاریخچهٔ تغییرات (History)

> این بخش از این به بعد با قالب استاندارد پر می‌شود.

- 2026-02-18 — Feature: تب‌بندی صفحه انبار بلزونا + ستون‌های موقتاً غیرفعال
  - تب ورودها: دیتاتیبل ورودی‌ها (ستون‌های جمع خروجی، مانده پارت، تعداد خروجی‌ها، شلف لایف، تاریخ انقضا، عملیات موقتاً کامنت شدند)
  - تب خروجی‌ها: دیتاتیبل کامل انبار (همان گزارشات) با فیلترها
  - تب تاریخ انقضا: دیتاتیبل جدید با نام محصول، تاریخ ورود، شلف لایف، تاریخ انقضا (فقط پارت‌های ورودی)
  - فایل‌ها: `index.blade.php`, `belzona-inventory-inbounds.js`, `BelzonaInventoryController.php` (action datatables_expiry)
- 2026-02-23 — Feature: باکس «COC های آخرین پارت‌ها»
  - باکس در کنار باکس‌های بالای دیتاتیبل؛ با کلیک مودال باز می‌شود و COC رکوردهای «منتخب» کاربر نمایش داده می‌شود
  - تنظیم آخرین پارت‌ها: داخل مودال دکمهٔ تنظیمات؛ دیتاتیبل ورودها با چک‌باکس؛ کاربر انتخاب می‌کند کدام رکوردها به‌عنوان آخرین پارت‌ها باشند؛ ذخیره در localStorage
  - پیش‌فرض: اگر کاربر هنوز انتخاب نکرده باشد، ۲–۳ رکورد جدیدترین ورود به‌صورت خودکار به‌عنوان پیش‌فرض ست می‌شوند
  - فایل‌ها: index.blade.php، belzona-inventory-inbounds.js
- 2026-02-23 — Feature: COC برای همه رکوردها + افزودن COC (آپلود به ازای هر رکورد)
  - همهٔ ردیف‌های دیتاتیبل ورودها دکمه «COC» دارند؛ با کلیک فقط COC همان رکورد از مسیر public/documents/coc/{inbound_id}/ نمایش داده می‌شود
  - بخش «افزودن COC» بالای دیتاتیبل: دکمه باز کردن مودال؛ داخل مودال همان دیتاتیبل ورودها با ستون «انتخاب»، انتخاب رکورد، آپلود فایل‌ها، ذخیره در پوشهٔ همان inbound_id
  - Backend: getCocDocuments(inbound_id)، uploadCoc() و route POST belzona-inventory/upload-coc
  - فایل‌ها: BelzonaInventoryController.php، web.php، index.blade.php، belzona-inventory-inbounds.js
- 2026-02-23 — Feature: ستون COC ها در دیتاتیبل ورودها + اسلایدر با زوم و تمام‌صفحه
  - ستون COC ها، مودال اسکرین‌شات از public/documents/coc/{inbound_id}/، اسلایدر با زوم و تمام‌صفحه
  - فایل‌ها: BelzonaInventoryController.php، index.blade.php، belzona-inventory-inbounds.js
- 2026-02-23 — Refactor: بهینه‌سازی دیتاتیبل ورودها
  - حذف ساب‌کوئری‌های سنگین (outTotalSub, outCountSub, remaining) از getInboundDataTables چون ستون‌های مربوط نمایش داده نمی‌شوند
  - فایل: BelzonaInventoryController.php
  - تأثیر: سرعت لود تب ورودها نزدیک به تب خروجی‌ها شد
- 2026-02-18 — Feature: باکس «آخرین مانده خروجی‌های پارت» در صفحه انبار بلزونا
  - دلیل: نمایش آخرین رکورد ستون مانده از خروجی‌های آخرین پارت ورود در صفحه اصلی، بدون نیاز به باز کردن مودال
  - فایل‌های لمس‌شده: `BelzonaInventoryController.php` (getInboundSummary + متد جدید getLastOutboundBalanceForInbound), `belzona-inventory/index.blade.php`, `belzona-inventory-inbounds.js`
  - تأثیر: باکس جدید کنار «آخرین پارت ورود» با عنوان «آخرین مانده خروجی‌های پارت» که مقدار ماندهٔ آخرین رکورد خروجی را نمایش می‌دهد
- 2026-02-17 — Migration: جداسازی این دامنه از فایل تاریخچهٔ کلی و انتقال به `02-domains/`.
  - فایل‌ها: `01-PROJECT-CONTEXT-AND-HISTORY.md` → `02-domains/theme-custom-styling-belzona.md`
  - تغییر رفتار: ندارد (صرفاً بازآرایی مستندات)
  - نکته: تصمیم‌های معماری مرتبط در ADR ها ثبت شود.

## کارهای رایج در این حوزه (Playbook کوتاه)

- اگر تغییر فقط ظاهری/کلاس‌ها/اسکوپ CSS است → همین فایل را آپدیت کن (History + وضعیت فعلی اگر لازم شد)
- اگر ساختار ذخیره‌سازی تنظیمات یا نحوهٔ تولید CSS عوض شد → یک ADR جدید بساز
