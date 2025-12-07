# راهنمای استفاده از سیستم ورودهای انبار

## 📋 خلاصه

سیستم انبار به دو سطح تقسیم می‌شود:

1. **کالا (Inventory)**: اطلاعات پایه کالا (کد، نام، واحد، موجودی کل)
2. **ورود (Entry)**: هر قلم/بچ کالا با تاریخ ورود، انقضا، بچ، سریال و...

## 🔗 کلید ارتباطی

**کلید خارجی**: `inventory_entries.inventory_id` → `inventory.inventory_id`

- هر ورود (Entry) به یک کالا (Inventory) متصل است
- رابطه: **Many-to-One** (هر کالا می‌تواند چندین ورود داشته باشد)
- Foreign Key با `CASCADE DELETE`: اگر کالا حذف شود، ورودهایش هم حذف می‌شوند

## 📥 ایمپورت داده‌ها

### 1. ایمپورت کالاهای پایه

**مسیر**: `/import/inventory`

**فایل**: `anbar.xlsx`

**محتوا**: اطلاعات پایه کالاها (کد، نام، موجودی اولیه)

**نکته**: این ایمپورت باید **قبل** از ایمپورت ورودها انجام شود.

### 2. ایمپورت ورودهای قلمی/بچ

**مسیر**: `/import/inventory-entry`

**فایل**: `قالب گردش كالا.xlsx`

**محتوا**: 
- کد کالا (باید با کدهای موجود در سیستم مطابقت داشته باشد)
- تاریخ ورود
- تاریخ انقضا (اختیاری)
- مقدار
- قیمت واحد
- شماره بچ/سریال (اختیاری)
- نام انبار (اختیاری)

**فرآیند**:
1. سیستم کد کالا را در جدول `inventory` جستجو می‌کند
2. اگر پیدا شد، یک رکورد در `inventory_entries` ایجاد می‌کند
3. موجودی کل کالا (`inventory.current_quantity`) به صورت خودکار به‌روزرسانی می‌شود

## 👀 مشاهده داده‌ها

### API Endpoints

#### 1. لیست همه ورودها
```
POST /report/warehouse/list-entries
```

**پارامترها**:
- `inventory_id` (اختیاری): فیلتر بر اساس کالا
- `status` (اختیاری): `all`, `expired`, `near_expiry`, `available`
- `search` (اختیاری): جستجو در کد، بچ، سریال، نام کالا
- `warehouse` (اختیاری): فیلتر بر اساس نام انبار
- `from_date`, `to_date` (اختیاری): فیلتر بر اساس تاریخ ورود

#### 2. ورودهای یک کالا
```
GET /report/warehouse/inventory-entries?inventory_id=123
```

**پارامترها**:
- `inventory_id` (اجباری): شناسه کالا
- `status` (اختیاری): فیلتر وضعیت
- `search` (اختیاری): جستجو در کد، بچ، سریال

### UI

**مسیر**: `/report/warehouse`

در صفحه گزارش انبار می‌توانید:
- لیست ورودها را مشاهده کنید
- بر اساس کالا، تاریخ، انبار فیلتر کنید
- برای هر ورود دسته‌بندی یا هشدار تنظیم کنید

## 🔍 بررسی و مدیریت

### ساختار دیتابیس

```sql
-- جدول کالاها
inventory
  - inventory_id (PK)
  - inventory_code (کد کالا)
  - inventory_name (نام کالا)
  - current_quantity (موجودی کل - محاسبه شده از entries)

-- جدول ورودها
inventory_entries
  - entry_id (PK)
  - inventory_id (FK → inventory.inventory_id)
  - entry_code (کد سند)
  - lot_number (شماره بچ)
  - serial_number (سریال)
  - entry_date (تاریخ ورود)
  - expiry_date (تاریخ انقضا)
  - initial_quantity (مقدار اولیه)
  - remaining_quantity (مقدار باقی‌مانده)
  - unit_cost (قیمت واحد)
  - warehouse_name (نام انبار)
```

### روابط

```php
// در مدل Inventory
public function entries()
{
    return $this->hasMany(InventoryEntry::class, 'inventory_id', 'inventory_id');
}

// در مدل InventoryEntry
public function inventory()
{
    return $this->belongsTo(Inventory::class, 'inventory_id', 'inventory_id');
}
```

### به‌روزرسانی خودکار موجودی

هنگامی که یک ورود جدید ایجاد می‌شود:

```php
// در InventoryEntryService
public function syncInventoryBalances(int $inventoryId): void
{
    $inventory = Inventory::find($inventoryId);
    $remaining = $inventory->entries()->sum('remaining_quantity');
    $inventory->current_quantity = $remaining;
    $inventory->save();
}
```

این متد به صورت خودکار پس از هر `create` یا `consume` فراخوانی می‌شود.

## 🎯 دسته‌بندی و هشدار در سطح ورود

### دسته‌بندی

می‌توانید یک ورود خاص را به یک دسته‌بندی اضافه کنید:

```php
// در InventoryCustomCategoryController
public function addInventory(Request $request)
{
    // ...
    InventoryCustomCategoryItem::create([
        'inventory_id' => $inventoryId,
        'inventory_entry_id' => $entryId, // ← ورود خاص
        'custom_category_id' => $categoryId,
        // ...
    ]);
}
```

### هشدار

می‌توانید برای یک ورود خاص هشدار تنظیم کنید:

```php
// در InventoryAlertController
InventoryAlertSetting::create([
    'inventory_id' => $inventoryId,
    'inventory_entry_id' => $entryId, // ← ورود خاص
    'alert_type' => 'expiry',
    'threshold_days' => 30,
    // ...
]);
```

## 📊 گزارش‌گیری

### گزارش موجودی فعلی

موجودی کل هر کالا از مجموع `remaining_quantity` ورودهایش محاسبه می‌شود.

### گزارش انقضا

می‌توانید ورودهای در حال انقضا را بر اساس `expiry_date` فیلتر کنید.

### گزارش بر اساس انبار

می‌توانید ورودها را بر اساس `warehouse_name` فیلتر کنید.

## ⚠️ نکات مهم

1. **ترتیب ایمپورت**: همیشه ابتدا کالاها را ایمپورت کنید، سپس ورودها
2. **کد کالا**: کد کالا در فایل ورودها باید دقیقاً با کد موجود در سیستم مطابقت داشته باشد
3. **موجودی**: موجودی کل کالا به صورت خودکار از ورودها محاسبه می‌شود
4. **حذف**: اگر کالا حذف شود، تمام ورودهایش هم حذف می‌شوند (CASCADE)
5. **تاریخ**: تاریخ‌ها می‌توانند شمسی یا میلادی باشند

## 🔧 دستورات CLI

### ایمپورت از طریق Command Line

```bash
php artisan inventory:import-entries "path/to/قالب گردش كالا.xlsx"
```

این دستور برای ایمپورت دسته‌ای یا از طریق cron job استفاده می‌شود.

