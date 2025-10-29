# راهنمای Debug و رفع مشکل - تحلیل‌های فروش

## 🔧 مشکلات رایج و راه‌حل‌ها

### 1️⃣ مشکل: فیلتر تاریخ کار نمی‌کند

#### علل احتمالی:
- ✅ Persian Date Picker به درستی تاریخ را set نمی‌کند
- ✅ فرمت تاریخ با دیتابیس مطابقت ندارد
- ✅ تاریخ‌های پیش‌فرض set نشده‌اند

#### راه‌حل:
1. **روی دکمه Debug کلیک کنید** - این دکمه اطلاعات دیتابیس را نمایش می‌دهد
2. **Console مرورگر را باز کنید** (F12) و به دنبال این پیام‌ها باشید:
   ```
   Analytics page loaded
   From date: 1403/01/01
   To date: 1403/12/29
   ```
3. **از دکمه "سال جاری" استفاده کنید** تا تاریخ‌های پیش‌فرض set شوند
4. **تاریخ‌ها را manually در input وارد کنید** (مثال: 1403/01/01)

#### تست سریع:
```
1. به آدرس زیر بروید:
   /report/sales/analytics/test-data

2. باید JSON با این فرمت نمایش داده شود:
   {
     "success": true,
     "total_records": [تعداد],
     "date_range": {...}
   }
```

---

### 2️⃣ مشکل: نمودارها لود نمی‌شوند

#### بررسی Console:
در Console دنبال این خطاها باشید:
- ❌ `Chart is not defined` → Chart.js لود نشده
- ❌ `404 Not Found` → Route مشکل دارد
- ❌ `500 Server Error` → مشکل Backend
- ❌ `CSRF Token Mismatch` → توکن مشکل دارد

#### راه‌حل:
```bash
# 1. Cache را Clear کنید
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# 2. Routes را بررسی کنید
php artisan route:list | grep analytics

# باید این routes را ببینید:
# - POST /report/sales/analytics/monthly-trend
# - POST /report/sales/analytics/top-products
# - POST /report/sales/analytics/top-customers
# - POST /report/sales/analytics/profit-analysis
# - POST /report/sales/analytics/seasonal-analysis
# - POST /report/sales/analytics/delivery-status
```

---

### 3️⃣ مشکل: صفحه سفید یا بدون استایل

#### علل:
- Layout file مشکل دارد
- CSS/JS لود نشده‌اند

#### راه‌حل:
1. بررسی کنید که `layout/wrapper.blade.php` شامل این تگ باشد:
   ```html
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```

2. مطمئن شوید jQuery قبل از script های شما لود شده

---

### 4️⃣ مشکل: داده‌ها نمایش داده نمی‌شوند

#### بررسی داده‌ها:
1. روی دکمه **Debug** کلیک کنید
2. پیام Alert باید تعداد رکوردها و بازه تاریخی را نشان دهد
3. اگر تعداد رکوردها 0 بود:
   - داده‌ها در جدول `sales` وجود ندارند
   - باید از فایل Excel import کنید

#### Import داده از Excel:
```php
// اگر نیاز به import دارید، از SalesImport استفاده کنید
// فایل موجود: application/app/Imports/SalesImport.php
```

---

## 🐛 Debug با Console

### کدهای Debug مفید:

```javascript
// 1. بررسی تاریخ‌های انتخاب شده
console.log('From:', $('#analytics_from_date').val());
console.log('To:', $('#analytics_to_date').val());

// 2. بررسی AJAX Request
// در Console این لاگ‌ها را ببینید:
// - "Loading time analytics..."
// - "Monthly trend response: ..."

// 3. تست Manual AJAX:
$.ajax({
    url: '/report/sales/analytics/monthly-trend',
    method: 'POST',
    data: {
        from_date: '1403/01/01',
        to_date: '1403/12/29'
    },
    success: function(r) { console.log(r); }
});
```

---

## ✅ Checklist تست

- [ ] صفحه `/report/sales/analytics` بدون خطا باز می‌شود
- [ ] تاریخ‌های پیش‌فرض (1403/01/01 تا 1403/12/29) set شده‌اند
- [ ] روی دکمه Debug کلیک کنید و تعداد رکوردها را ببینید
- [ ] Persian Date Picker باز می‌شود و تاریخ را set می‌کند
- [ ] روی "به‌روزرسانی نمودارها" کلیک کنید
- [ ] Badge فیلتر نمایش داده می‌شود
- [ ] نمودارها رسم می‌شوند
- [ ] بین تب‌ها جابجا شوید و نمودارها لود شوند

---

## 📊 فرمت تاریخ در دیتابیس

تاریخ‌ها باید به فرمت **شمسی** باشند:
```
مثال: 1403/06/15
فرمت: YYYY/MM/DD
```

اگر تاریخ‌ها به فرمت میلادی هستند، باید تبدیل شوند.

---

## 🚀 مراحل نهایی

1. ✅ Cache را Clear کنید
2. ✅ روی دکمه Debug کلیک کنید
3. ✅ Console را باز کنید (F12)
4. ✅ تاریخ‌ها را انتخاب کنید
5. ✅ روی "به‌روزرسانی نمودارها" کلیک کنید
6. ✅ خطاها را از Console کپی کنید (اگر خطا بود)

---

## 🎯 تست URL ها

این URL ها باید کار کنند:

```
✅ GET  /report/sales/analytics
✅ GET  /report/sales/analytics/test-data
✅ POST /report/sales/analytics/monthly-trend
✅ POST /report/sales/analytics/top-products
✅ POST /report/sales/analytics/top-customers
✅ POST /report/sales/analytics/profit-analysis
✅ POST /report/sales/analytics/seasonal-analysis
✅ POST /report/sales/analytics/delivery-status
```

---

## 📝 لاگ‌های مهم Laravel

اگر خطای 500 دیدید، این فایل را بررسی کنید:
```
storage/logs/laravel.log
```

دنبال این کلمات کلیدی بگردید:
- `Monthly Trend Error`
- `Top Products Error`
- `Top Customers Error`
- `Profit Analysis Error`
- `Seasonal Analysis Error`
- `Delivery Status Error`

---

## 🔍 نکات مهم

### فیلد‌های مورد نیاز در جدول sales:
- ✅ `document_date` - تاریخ سند (فرمت: 1403/01/01)
- ✅ `month` - شماره ماه (1 تا 12)
- ✅ `product_name` - نام محصول
- ✅ `customer_name` - نام مشتری
- ✅ `base_sales_amount` - مبلغ فروش
- ✅ `base_net_amount` - مبلغ خالص
- ✅ `main_quantity` - مقدار
- ✅ `issued_main_quantity` - مقدار تحویل شده
- ✅ `remaining_main_quantity` - مقدار باقیمانده

---

## 💡 نکته طلایی

اگر همه چیز درست است اما نمودارها نمایش داده نمی‌شوند:

1. مطمئن شوید **داده‌های واقعی** در دیتابیس وجود دارند
2. بازه تاریخی انتخابی باید شامل داده باشد
3. فرمت تاریخ باید شمسی باشد (YYYY/MM/DD)

برای تست، ابتدا بدون فیلتر تاریخی نمودارها را لود کنید:
- فیلدهای تاریخ را خالی بگذارید
- روی "به‌روزرسانی نمودارها" کلیک کنید
- باید تمام داده‌های موجود نمایش داده شوند

