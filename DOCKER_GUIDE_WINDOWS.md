# راهنمای کامل اجرای SameCRM با Docker در ویندوز

این راهنما به صورت کامل و گام به گام نحوه اجرای پروژه SameCRM را با استفاده از Docker در سیستم‌عامل ویندوز توضیح می‌دهد.

## 📋 پیش‌نیازها

### 1. نصب Docker Desktop برای ویندوز

#### روش 1: دانلود از سایت رسمی (توصیه می‌شود)

1. به آدرس زیر بروید:
   ```
   https://www.docker.com/products/docker-desktop
   ```

2. روی دکمه "Download for Windows" کلیک کنید

3. فایل `Docker Desktop Installer.exe` را دانلود کنید

4. فایل را اجرا کنید و مراحل نصب را دنبال کنید:
   - ✅ "Use WSL 2 instead of Hyper-V" را انتخاب کنید (اگر WSL 2 نصب دارید)
   - ❌ **"Allow Windows containers to be used with this installation" را انتخاب نکنید**
     - این گزینه برای پروژه‌های .NET یا Windows-specific است
     - پروژه SameCRM از Linux Containers استفاده می‌کند
     - اگر این گزینه را فعال کنید، باید بعداً آن را در Settings تغییر دهید
   - ✅ "Add shortcut to desktop" را انتخاب کنید

5. پس از نصب، کامپیوتر را Restart کنید

6. Docker Desktop را اجرا کنید و منتظر بمانید تا راه‌اندازی شود

#### روش 2: استفاده از Chocolatey

```powershell
# باز کردن PowerShell به عنوان Administrator
choco install docker-desktop -y
```

#### بررسی نصب Docker

```powershell
# باز کردن PowerShell یا Command Prompt
docker --version
docker compose version
```

اگر دستورات بالا نسخه Docker را نمایش دادند، نصب موفقیت‌آمیز بوده است.

### 2. فعال‌سازی WSL 2 (اختیاری اما توصیه می‌شود)

WSL 2 عملکرد بهتری نسبت به Hyper-V دارد:

```powershell
# باز کردن PowerShell به عنوان Administrator
wsl --install

# یا برای به‌روزرسانی WSL
wsl --update

# بررسی نسخه WSL
wsl --version
```

پس از نصب WSL 2، کامپیوتر را Restart کنید.

### 3. نصب Git (اگر نصب نیست)

1. از آدرس زیر دانلود کنید:
   ```
   https://git-scm.com/download/win
   ```

2. فایل را اجرا و نصب کنید

3. بررسی نصب:
   ```powershell
   git --version
   ```

## 🚀 مراحل نصب و راه‌اندازی

### مرحله 1: دریافت پروژه

#### روش 1: استفاده از Git

```powershell
# باز کردن PowerShell یا Git Bash
cd C:\
git clone https://github.com/AmirAbedini78/samecrm_v1.git
cd samecrm_v1
```

#### روش 2: دانلود ZIP

1. به آدرس پروژه در GitHub بروید
2. روی "Code" > "Download ZIP" کلیک کنید
3. فایل را Extract کنید
4. به دایرکتوری پروژه بروید

### مرحله 2: تنظیم فایل .env

```powershell
# رفتن به دایرکتوری application
cd application

# ایجاد یا ویرایش فایل .env
notepad .env
```

**یا از طریق File Explorer:**
1. به پوشه `application` بروید
2. اگر فایل `.env` وجود ندارد، یک فایل متنی جدید ایجاد کنید
3. نام آن را `.env` بگذارید (با نقطه در ابتدا)

محتوای پیشنهادی برای فایل `.env`:

```env
APP_NAME=SameCRM
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=samecrm
DB_USERNAME=samecrm_user
DB_PASSWORD=samecrm_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**نکته:** `APP_KEY` به صورت خودکار در هنگام اجرای container تولید می‌شود.

### مرحله 3: اطمینان از اجرای Docker Desktop

1. Docker Desktop را اجرا کنید
2. منتظر بمانید تا آیکون Docker در System Tray (کنار ساعت) سبز شود
3. روی آیکون راست کلیک کنید و "Settings" را باز کنید
4. در بخش "General" اطمینان حاصل کنید که:
   - ✅ "Use the WSL 2 based engine" فعال است (اگر WSL 2 دارید)
   - ✅ "Start Docker Desktop when you log in" (اختیاری)

### مرحله 4: ساخت و راه‌اندازی Containerها

```powershell
# بازگشت به دایرکتوری اصلی پروژه
cd ..

# ساخت و راه‌اندازی تمام سرویس‌ها
docker compose up -d --build
```

**نکته:** اولین بار ممکن است چند دقیقه طول بکشد چون باید تصاویر Docker دانلود شوند.

این دستور:
- تصویر Docker را می‌سازد
- Containerهای MySQL، Redis، PHP-FPM و Nginx را ایجاد می‌کند
- شبکه‌های Docker را تنظیم می‌کند
- Volumeها را برای ذخیره داده‌ها ایجاد می‌کند

### مرحله 5: بررسی وضعیت Containerها

```powershell
# مشاهده وضعیت تمام containerها
docker compose ps

# مشاهده لاگ‌ها
docker compose logs -f

# مشاهده لاگ یک سرویس خاص
docker compose logs -f app
docker compose logs -f mysql
docker compose logs -f nginx
```

**نکته:** برای خروج از مشاهده لاگ‌ها، `Ctrl + C` را فشار دهید.

### مرحله 6: اجرای Migrationها و Setup اولیه

```powershell
# ورود به container برنامه
docker compose exec app sh

# در داخل container:
cd /var/www/html/application

# تولید کلید برنامه (اگر انجام نشده)
php artisan key:generate

# اجرای migrationها
php artisan migrate --force

# ایجاد لینک symbolic برای storage
php artisan storage:link

# پاک کردن cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# بهینه‌سازی
php artisan config:cache
php artisan route:cache
php artisan view:cache

# خروج از container
exit
```

### مرحله 7: دسترسی به برنامه

پس از اتمام مراحل بالا، می‌توانید به برنامه از طریق مرورگر دسترسی پیدا کنید:

```
http://localhost:8080
```

## 🔧 دستورات مفید

### مدیریت Containerها

```powershell
# توقف تمام containerها
docker compose stop

# راه‌اندازی مجدد containerها
docker compose start

# توقف و حذف containerها
docker compose down

# توقف و حذف containerها همراه با volumeها (⚠️ تمام داده‌ها پاک می‌شود)
docker compose down -v

# راه‌اندازی مجدد یک سرویس خاص
docker compose restart app
docker compose restart mysql
```

### دسترسی به Containerها

```powershell
# ورود به container برنامه
docker compose exec app sh

# ورود به container MySQL
docker compose exec mysql mysql -u samecrm_user -p samecrm
# رمز عبور: samecrm_password

# اجرای دستور artisan
docker compose exec app php /var/www/html/application/artisan migrate

# اجرای دستور composer
docker compose exec app composer install --working-dir=/var/www/html/application
```

### مدیریت Database

```powershell
# پشتیبان‌گیری از دیتابیس
docker compose exec mysql mysqldump -u samecrm_user -psamecrm_password samecrm > backup.sql

# بازگردانی دیتابیس
Get-Content backup.sql | docker compose exec -T mysql mysql -u samecrm_user -psamecrm_password samecrm

# مشاهده جداول دیتابیس
docker compose exec mysql mysql -u samecrm_user -psamecrm_password -e "USE samecrm; SHOW TABLES;"
```

### مشاهده و مدیریت لاگ‌ها

```powershell
# مشاهده لاگ‌های برنامه
docker compose logs -f app

# مشاهده لاگ‌های Nginx
docker compose logs -f nginx

# مشاهده لاگ‌های MySQL
docker compose logs -f mysql

# مشاهده لاگ‌های Laravel
docker compose exec app tail -f /var/www/html/application/storage/logs/laravel.log
```

### به‌روزرسانی پروژه

```powershell
# دریافت آخرین تغییرات از گیت
git pull origin main

# ساخت مجدد containerها
docker compose up -d --build

# اجرای migrationهای جدید
docker compose exec app php /var/www/html/application/artisan migrate --force

# پاک کردن cache
docker compose exec app php /var/www/html/application/artisan config:clear
docker compose exec app php /var/www/html/application/artisan cache:clear
```

## 🐛 رفع مشکلات رایج در ویندوز

### مشکل 1: Docker Desktop اجرا نمی‌شود

**راه‌حل:**
1. کامپیوتر را Restart کنید
2. مطمئن شوید Virtualization در BIOS فعال است
3. Windows Update را انجام دهید
4. Docker Desktop را به‌صورت Run as Administrator اجرا کنید

### مشکل 2: Port در حال استفاده است

```powershell
# بررسی portهای استفاده شده
netstat -ano | findstr :8080
netstat -ano | findstr :3306

# تغییر port در docker-compose.yml
# تغییر APP_PORT و DB_PORT
```

### مشکل 3: مشکل دسترسی به فایل‌ها

**راه‌حل:**
1. در Docker Desktop به Settings > Resources > File Sharing بروید
2. مطمئن شوید درایو C: (یا درایوی که پروژه در آن است) به اشتراک گذاشته شده است
3. Docker Desktop را Restart کنید

### مشکل 4: خطای "WSL 2 installation is incomplete"

**راه‌حل:**
```powershell
# باز کردن PowerShell به عنوان Administrator
wsl --update
wsl --set-default-version 2

# Restart کامپیوتر
```

### مشکل 5: مشکل در Build Docker

```powershell
# پاک کردن cacheهای Docker
docker system prune -a

# ساخت مجدد بدون cache
docker compose build --no-cache
```

### مشکل 6: خطای "Cannot connect to the Docker daemon"

**راه‌حل:**
1. Docker Desktop را اجرا کنید
2. منتظر بمانید تا کاملاً راه‌اندازی شود (آیکون سبز شود)
3. اگر مشکل ادامه داشت، Docker Desktop را Restart کنید

### مشکل 7: مشکل Performance کند

**راه‌حل:**
1. در Docker Desktop به Settings > Resources بروید
2. CPU و Memory را افزایش دهید (حداقل 4 CPU و 4GB RAM)
3. از WSL 2 استفاده کنید به جای Hyper-V
4. فایل‌های پروژه را در درایو C: قرار دهید (نه در OneDrive یا شبکه)

### مشکل 8: خطای "Windows Containers" یا "This image requires Windows"

**علت:** به اشتباه Windows Containers فعال شده است، در حالی که پروژه SameCRM از Linux Containers استفاده می‌کند.

**راه‌حل:**
1. Docker Desktop را باز کنید
2. روی آیکون Docker در System Tray (کنار ساعت) راست کلیک کنید
3. روی "Switch to Linux containers" کلیک کنید
   - اگر این گزینه را نمی‌بینید، یعنی در حال حاضر Linux Containers فعال است
4. اگر گزینه "Switch to Linux containers" را نمی‌بینید:
   - Docker Desktop را ببندید
   - به Settings > General بروید
   - مطمئن شوید "Use the WSL 2 based engine" فعال است
   - Docker Desktop را Restart کنید

**نکته:** برای پروژه‌های Laravel/PHP همیشه باید از Linux Containers استفاده کنید.

## 📊 مانیتورینگ و بررسی وضعیت

```powershell
# مشاهده استفاده از منابع
docker stats

# مشاهده حجم volumeها
docker volume ls
docker volume inspect samecrm_v1111_mysql_data

# مشاهده شبکه‌های Docker
docker network ls
docker network inspect samecrm_v1111_samecrm_network
```

## 🔒 امنیت

### تغییر رمزهای عبور پیش‌فرض

در فایل `docker-compose.yml` و `.env` حتماً رمزهای عبور پیش‌فرض را تغییر دهید:

```yaml
environment:
  MYSQL_ROOT_PASSWORD: YOUR_STRONG_PASSWORD
  MYSQL_PASSWORD: YOUR_STRONG_PASSWORD
```

### فایروال ویندوز

```powershell
# باز کردن port 8080 در Windows Firewall
New-NetFirewallRule -DisplayName "SameCRM Docker" -Direction Inbound -LocalPort 8080 -Protocol TCP -Action Allow
```

## 💡 نکات مهم برای ویندوز

1. **Windows Containers:** ❌ هرگز Windows Containers را فعال نکنید. پروژه SameCRM از Linux Containers استفاده می‌کند. اگر به اشتباه فعال شده، از System Tray > Switch to Linux containers استفاده کنید.
2. **Antivirus:** ممکن است آنتی‌ویروس با Docker تداخل داشته باشد. Docker Desktop را به Exception اضافه کنید
3. **OneDrive:** پروژه را در OneDrive قرار ندهید (مشکلات Performance)
4. **Path طولانی:** از pathهای کوتاه استفاده کنید (مثلاً `C:\Projects\samecrm`)
5. **Line Endings:** Git باید line endings را به درستی مدیریت کند (autocrlf = true)
6. **WSL 2:** همیشه از WSL 2 استفاده کنید برای عملکرد بهتر

## 🎯 دستورات سریع (Quick Reference)

```powershell
# راه‌اندازی کامل
docker compose up -d --build

# توقف
docker compose down

# مشاهده لاگ
docker compose logs -f

# اجرای artisan
docker compose exec app php /var/www/html/application/artisan [command]

# پشتیبان‌گیری دیتابیس
docker compose exec mysql mysqldump -u samecrm_user -psamecrm_password samecrm > backup.sql
```

## 🔄 راه‌اندازی مجدد کامل

اگر مشکلی پیش آمد و می‌خواهید از صفر شروع کنید:

```powershell
# توقف و حذف همه چیز
docker compose down -v

# پاک کردن تصاویر
docker rmi samecrm_v1111-app

# ساخت مجدد
docker compose up -d --build
```

## 📝 تنظیمات پیشنهادی Docker Desktop

1. **Settings > General:**
   - ✅ Use the WSL 2 based engine
   - ✅ Start Docker Desktop when you log in
   - ❌ **Windows Containers را فعال نکنید** (برای پروژه‌های Laravel/PHP از Linux Containers استفاده می‌شود)

2. **Settings > Resources:**
   - CPUs: 4 (یا بیشتر)
   - Memory: 4GB (یا بیشتر)
   - Disk image size: 60GB (یا بیشتر)

3. **Settings > Docker Engine:**
   ```json
   {
     "builder": {
       "gc": {
         "enabled": true,
         "defaultKeepStorage": "20GB"
       }
     }
   }
   ```

## 📞 پشتیبانی

در صورت بروز مشکل:
1. لاگ‌ها را بررسی کنید: `docker compose logs`
2. وضعیت containerها را بررسی کنید: `docker compose ps`
3. Docker Desktop را Restart کنید
4. در صورت نیاز با تیم توسعه تماس بگیرید

---

**نکته:** این راهنما برای محیط production نوشته شده است. برای محیط development ممکن است نیاز به تغییراتی در تنظیمات باشد.

**نکته مهم:** در ویندوز، همیشه Docker Desktop باید در حال اجرا باشد تا بتوانید از دستورات Docker استفاده کنید.
