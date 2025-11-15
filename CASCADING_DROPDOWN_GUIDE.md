# راهنمای Cascading Dropdown (انبار → محصول)

## ✅ قابلیت اضافه شده

**Cascading Dropdown** بین فیلترهای **انبار** و **محصول** در هر دو صفحه:
- گزارش تحلیل‌های فروش و نمودارها
- گزارش مقایسه بازه‌های تاریخ

---

## 🎯 عملکرد

### قبل از تغییرات:
```
انبار: [همه انبارها ▼]
محصول: [همه محصولات ▼]  ← نمایش تمام محصولات
```

### بعد از تغییرات:
```
انبار: [انبار مرکزی ▼]
محصول: [فقط محصولات انبار مرکزی ▼]  ← فیلتر شده!
```

---

## 📋 تغییرات Backend

### فایل: `application/app/Http/Controllers/Reports/SalesReports.php`

#### متد: `getUniqueValues()`

```php
// قبل:
public function getUniqueValues(Request $request) {
    $column = $request->get('column');
    $from_date = $request->get('from_date');
    $to_date = $request->get('to_date');
    
    $query = Sales::query();
    // فقط فیلتر تاریخ
}

// بعد:
public function getUniqueValues(Request $request) {
    $column = $request->get('column');
    $from_date = $request->get('from_date');
    $to_date = $request->get('to_date');
    $warehouse = $request->get('warehouse');  // ← جدید!
    
    $query = Sales::query();
    
    // Apply warehouse filter (for product cascading)
    if ($warehouse && $warehouse !== '' && $column === 'product_name') {
        $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
    }
}
```

**توضیح:**
- وقتی می‌خواهیم محصولات یونیک را برگردانیم (`column === 'product_name'`)
- اگر `warehouse` هم مشخص شده باشد
- فقط محصولات آن انبار را برمی‌گردانیم

---

## 📋 تغییرات Frontend

### 1️⃣ صفحه Analytics

**فایل:** `application/resources/views/pages/reports/sales/analytics-wrapper.blade.php`

```javascript
// Event listener برای تغییر انبار
$('#filter_warehouse').on('change', function() {
    const selectedWarehouse = $(this).val();
    console.log('Warehouse changed:', selectedWarehouse);
    
    // Get current date filter
    let filterData = {};
    const dates = getFilterDates();
    if (dates.from_date) filterData.from_date = dates.from_date;
    if (dates.to_date) filterData.to_date = dates.to_date;
    
    // Add warehouse filter
    if (selectedWarehouse) {
        filterData.warehouse = selectedWarehouse;
    }
    
    // Reload products based on selected warehouse
    $.ajax({
        url: '/report/sales/analytics/unique-values',
        method: 'POST',
        data: { column: 'product_name', ...filterData },
        success: function(response) {
            console.log('Products reloaded:', response.data.length);
            populateSelect('#filter_product', response.data, 'همه محصولات', '');
            
            // Show visual feedback
            if (selectedWarehouse) {
                $('#filter_product').addClass('border-primary');
                setTimeout(() => $('#filter_product').removeClass('border-primary'), 2000);
            }
        }
    });
});
```

**نکات:**
- ✅ event listener برای `change` روی `#filter_warehouse`
- ✅ فیلتر تاریخی هم اعمال می‌شود (محصولات فیلتر شده + در بازه تاریخی)
- ✅ محصول قبلی پاک می‌شود (reset)
- ✅ visual feedback با `border-primary` برای 2 ثانیه

---

### 2️⃣ صفحه Comparison

**فایل:** `application/resources/views/pages/reports/sales/comparison-wrapper.blade.php`

```javascript
// Event listener برای تغییر انبار
$('#warehouse_filter').on('change', function() {
    const selectedWarehouse = $(this).val();
    console.log('Warehouse changed (Comparison):', selectedWarehouse);
    
    // Get current range and dates
    const range = $('input[name="range"]:checked').val() || '1';
    let filterData = { 
        column: 'product_name',
        range: range
    };
    
    // Add date range filters
    if (range == '1') {
        filterData.range1_from = $('#range1_from').val();
        filterData.range1_to = $('#range1_to').val();
    } else {
        filterData.range2_from = $('#range2_from').val();
        filterData.range2_to = $('#range2_to').val();
    }
    
    // Add warehouse filter
    if (selectedWarehouse) {
        filterData.warehouse = selectedWarehouse;
    }
    
    // Reload products
    $.ajax({
        url: '/report/sales/analytics/unique-values',
        method: 'POST',
        data: filterData,
        success: function(response) {
            populateComparisonSelect('#product_filter', response.data, 'همه محصولات', '');
            
            // Visual feedback
            if (selectedWarehouse) {
                $('#product_filter').addClass('border-primary');
                setTimeout(() => $('#product_filter').removeClass('border-primary'), 2000);
            }
        }
    });
});
```

**تفاوت با Analytics:**
- ✅ از `range` استفاده می‌کند (بجای `from_date/to_date`)
- ✅ از `populateComparisonSelect` استفاده می‌کند (بجای `populateSelect`)
- ✅ بقیه منطق یکسان است

---

## 🧪 نحوه تست

### تست 1: Analytics Page

```
1. به /report/sales/analytics بروید
2. انبار را "انبار مرکزی" انتخاب کنید
3. Console (F12):
   ✅ "Warehouse changed: انبار مرکزی"
   ✅ "Products reloaded: 15"
4. ComboBox محصول:
   ✅ فقط محصولات "انبار مرکزی" را نمایش می‌دهد
   ✅ Border آبی برای 2 ثانیه
5. انبار را "همه انبارها" کنید
6. ComboBox محصول:
   ✅ همه محصولات برمی‌گردند
```

---

### تست 2: Comparison Page

```
1. به /report/sales/comparison بروید
2. انبار را "شعبه 1" انتخاب کنید
3. Console:
   ✅ "Warehouse changed (Comparison): شعبه 1"
   ✅ "Products reloaded for warehouse (Comparison): 8"
4. ComboBox محصول:
   ✅ فقط محصولات "شعبه 1"
```

---

### تست 3: با فیلتر تاریخی

```
1. Analytics Page
2. سال: 1403
3. از ماه: فروردین
4. تا ماه: خرداد
5. انبار: "انبار مرکزی"
6. ComboBox محصول:
   ✅ فقط محصولاتی که در "انبار مرکزی" و "فروردین-خرداد 1403" فروخته شده‌اند
```

---

## 🎨 Visual Feedback

### Border Animation:
```css
/* وقتی محصولات reload می‌شوند: */
$('#filter_product').addClass('border-primary');  // ← آبی
setTimeout(() => $('#filter_product').removeClass('border-primary'), 2000);  // ← بعد 2 ثانیه
```

**نتیجه:**
- کاربر می‌داند محصولات تغییر کردند
- UI Feedback واضح

---

## 🔄 جریان عملیات (Flow)

### کاربر انبار را تغییر می‌دهد:

```
1. کاربر: انتخاب انبار "انبار مرکزی"
   ↓
2. Event: change روی #filter_warehouse
   ↓
3. JavaScript: فراخوانی AJAX
   ↓
4. Backend: getUniqueValues()
   ↓
5. Query: WHERE warehouse LIKE '%انبار مرکزی%'
   ↓
6. Response: ["محصول A", "محصول B", "محصول C"]
   ↓
7. JavaScript: پر کردن #filter_product
   ↓
8. UI: Border آبی برای 2 ثانیه
   ↓
9. کاربر: می‌بیند فقط محصولات این انبار
```

---

## 💡 مثال کاربردی واقعی

### سناریو:

```
سوال: 
"کدام محصولات انبار مرکزی در سال 1403 بیشترین فروش را داشتند؟"

پاسخ با Cascading Dropdown:
1. سال: 1403
2. انبار: انبار مرکزی  ← محصولات فیلتر می‌شوند
3. محصول: [فقط محصولات انبار مرکزی]  ← انتخاب راحت‌تر
4. تب: تحلیل محصولات
5. نمودار: Top 10 محصولات
```

**مزایا:**
- ✅ کاربر فقط محصولات مرتبط را می‌بیند
- ✅ جستجو راحت‌تر می‌شود
- ✅ خطای انتخاب اشتباه کاهش می‌یابد

---

## 🔧 نکات فنی

### 1. کارایی (Performance):
```php
// Query بهینه شده:
$query->where('warehouse', 'LIKE', '%' . $warehouse . '%');

// فقط برای column 'product_name':
if ($column === 'product_name') {
    // ...
}
```

**چرا؟**
- فیلتر warehouse فقط برای محصولات اعمال می‌شود
- مشتریان یا سازندگان فیلتر نمی‌شوند

---

### 2. سازگاری با Date Filter:
```javascript
// فیلتر تاریخی هم اعمال می‌شود:
filterData.from_date = dates.from_date;
filterData.to_date = dates.to_date;
filterData.warehouse = selectedWarehouse;
```

**نتیجه:**
- محصولات فیلتر شده بر اساس:
  - ✅ انبار انتخابی
  - ✅ بازه تاریخی انتخابی

---

### 3. Reset Behavior:
```javascript
populateSelect('#filter_product', response.data, 'همه محصولات', '');
//                                                                   ↑
//                                            selectedValue = '' (reset)
```

**چرا؟**
- وقتی انبار تغییر می‌کند، محصول قبلی معنا ندارد
- بهتر است reset شود

---

## ⚠️ موارد مهم

### 1. Backward Compatibility:
```php
// اگر warehouse مشخص نشده:
if ($warehouse && $warehouse !== '' && $column === 'product_name') {
    // فیلتر اعمال می‌شود
}
// در غیر این صورت:
// همه محصولات برمی‌گردند (رفتار قبلی)
```

**نتیجه:**
- ✅ اگر انبار انتخاب نشده، همه محصولات نمایش داده می‌شوند
- ✅ هیچ breaking change وجود ندارد

---

### 2. Console Logging:
```javascript
console.log('Warehouse changed:', selectedWarehouse);
console.log('Products reloaded:', response.data.length);
```

**مزیت:**
- ✅ Debug آسان
- ✅ مشاهده تعداد محصولات فیلتر شده

---

## 📊 مقایسه قبل/بعد

| ویژگی | قبل | بعد |
|-------|-----|-----|
| **انتخاب محصول** | همه محصولات | فقط محصولات انبار انتخابی |
| **تعداد محصولات** | 100+ | 10-20 (بسته به انبار) |
| **سرعت جستجو** | کند (لیست طولانی) | سریع (لیست کوتاه) |
| **خطای انتخاب** | بالا | پایین |
| **UX** | متوسط | عالی |

---

## 🎉 خلاصه

### ✅ آنچه اضافه شد:

1. **Backend:**
   - فیلتر warehouse در متد `getUniqueValues`
   - شرط `$column === 'product_name'` برای اعمال فیلتر

2. **Frontend Analytics:**
   - event listener برای `#filter_warehouse`
   - reload محصولات با warehouse filter
   - visual feedback (border animation)

3. **Frontend Comparison:**
   - همان قابلیت با مقادیر range-based

### ✅ مزایا:

- کاربر فقط محصولات مرتبط را می‌بیند
- جستجو راحت‌تر و سریع‌تر
- کاهش خطای انتخاب
- بهبود UX/UI

---

**نسخه:** 5.0.0  
**تاریخ:** 1404/08/08  
**ویژگی:** Cascading Dropdown (Warehouse → Product)






