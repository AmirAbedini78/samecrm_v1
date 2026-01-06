# 📦 خلاصه فایل‌های Docker ایجاد شده

این سند خلاصه‌ای از تمام فایل‌های Docker ایجاد شده برای پروژه SameCRM است.

## 📁 فایل‌های ایجاد شده

### 1. فایل‌های اصلی Docker

#### `Dockerfile`
- فایل اصلی برای ساخت تصویر Docker
- از Multi-stage build استفاده می‌کند
- شامل PHP 8.2-FPM، Nginx، Supervisor
- تمام extensionهای لازم PHP نصب شده است
- بهینه‌سازی شده برای production

#### `docker-compose.yml`
- فایل اصلی Docker Compose برای production
- شامل 4 سرویس: app, nginx, mysql, redis
- تنظیمات کامل برای شبکه و volumeها
- Health check برای MySQL

#### `docker-compose.dev.yml`
- فایل Docker Compose برای محیط development
- تنظیمات debug فعال
- نام containerها متفاوت برای جلوگیری از تداخل

#### `.dockerignore`
- فایل‌ها و پوشه‌هایی که در Docker build نادیده گرفته می‌شوند
- شامل node_modules، vendor، cacheها و ...

### 2. فایل‌های پیکربندی

#### `docker/nginx/default.conf`
- تنظیمات Nginx برای Laravel
- پشتیبانی از PHP-FPM
- Cache برای فایل‌های استاتیک
- امنیت (block کردن فایل‌های مخفی)

#### `docker/supervisor/supervisord.conf`
- تنظیمات Supervisor
- مدیریت PHP-FPM و Nginx
- اجرای خودکار سرویس‌ها

#### `docker/mysql/my.cnf`
- تنظیمات MySQL
- UTF8MB4 برای پشتیبانی کامل از Unicode
- بهینه‌سازی buffer pool

#### `docker/start.sh`
- اسکریپت راه‌اندازی خودکار
- انتظار برای آماده شدن MySQL
- ایجاد فایل .env خودکار
- تولید APP_KEY
- تنظیم دسترسی‌ها
- پاک کردن cache

### 3. مستندات

#### `DOCKER_README.md`
- README اصلی برای Docker
- معرفی ساختار فایل‌ها
- دستورات مهم
- لینک به راهنماهای تفصیلی

#### `DOCKER_GUIDE_LINUX.md`
- راهنمای کامل برای Linux
- نصب Docker در Ubuntu/Debian/CentOS
- مراحل کامل راه‌اندازی
- دستورات مفید
- رفع مشکلات رایج
- نکات امنیتی

#### `DOCKER_GUIDE_WINDOWS.md`
- راهنمای کامل برای Windows
- نصب Docker Desktop
- فعال‌سازی WSL 2
- مراحل کامل راه‌اندازی
- دستورات مفید PowerShell
- رفع مشکلات خاص Windows
- تنظیمات Docker Desktop

#### `QUICK_START_DOCKER.md`
- راهنمای سریع 5 دقیقه‌ای
- دستورات ضروری
- محتوای فایل .env
- لینک به مستندات کامل

## 🏗️ معماری

```
┌─────────────────────────────────────────┐
│           Docker Network                │
│                                         │
│  ┌──────────┐    ┌──────────┐         │
│  │  Nginx   │───▶│   App    │         │
│  │  :8080   │    │ (PHP-FPM)│         │
│  └──────────┘    └────┬──────┘         │
│                      │                 │
│  ┌──────────┐    ┌──▼──────┐         │
│  │  Redis   │    │  MySQL  │         │
│  │  :6379   │    │  :3306  │         │
│  └──────────┘    └──────────┘         │
│                                         │
└─────────────────────────────────────────┘
```

## 🔧 سرویس‌ها

### 1. App (PHP-FPM)
- **Image:** Custom build از Dockerfile
- **PHP Version:** 8.2
- **Extensions:** pdo, pdo_mysql, zip, mbstring, gd, intl, opcache و ...
- **Working Directory:** /var/www/html
- **User:** www-data

### 2. Nginx
- **Image:** nginx:alpine
- **Port:** 8080 (قابل تغییر)
- **Config:** docker/nginx/default.conf
- **Root:** /var/www/html/public

### 3. MySQL
- **Image:** mysql:8.0
- **Port:** 3306 (قابل تغییر)
- **Database:** samecrm (پیش‌فرض)
- **Volume:** mysql_data (persistent)

### 4. Redis
- **Image:** redis:7-alpine
- **Port:** 6379 (قابل تغییر)
- **Volume:** redis_data (persistent)
- **Persistence:** AOF enabled

## 📋 متغیرهای محیطی

فایل `.env` باید شامل موارد زیر باشد:

```env
APP_NAME=SameCRM
APP_ENV=production
APP_KEY=                    # خودکار تولید می‌شود
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

## 🚀 مراحل راه‌اندازی

### Linux:
1. نصب Docker و Docker Compose
2. کلون کردن پروژه
3. تنظیم فایل .env
4. اجرای `docker compose up -d --build`
5. اجرای migrationها
6. دسترسی به http://localhost:8080

### Windows:
1. نصب Docker Desktop
2. فعال‌سازی WSL 2 (توصیه می‌شود)
3. کلون کردن پروژه
4. تنظیم فایل .env
5. اجرای `docker compose up -d --build`
6. اجرای migrationها
7. دسترسی به http://localhost:8080

## 🔑 دستورات کلیدی

```bash
# راه‌اندازی
docker compose up -d --build

# توقف
docker compose down

# مشاهده لاگ
docker compose logs -f

# اجرای artisan
docker compose exec app php /var/www/html/application/artisan [command]

# ورود به container
docker compose exec app sh

# پشتیبان‌گیری دیتابیس
docker compose exec mysql mysqldump -u samecrm_user -psamecrm_password samecrm > backup.sql
```

## ⚠️ نکات مهم

1. **امنیت:**
   - حتماً رمزهای عبور پیش‌فرض را تغییر دهید
   - برای production از SSL استفاده کنید
   - فایروال را تنظیم کنید

2. **Backup:**
   - به صورت منظم از دیتابیس backup بگیرید
   - Volumeها را backup کنید

3. **Performance:**
   - منابع کافی به Docker اختصاص دهید
   - از WSL 2 در Windows استفاده کنید
   - OPcache فعال است

4. **Development:**
   - از `docker-compose.dev.yml` برای development استفاده کنید
   - Debug mode را فعال کنید

## 📊 Volumeها

- `mysql_data`: داده‌های MySQL (persistent)
- `redis_data`: داده‌های Redis (persistent)
- `./application/storage`: فایل‌های Laravel storage
- `./storage`: فایل‌های عمومی storage

## 🌐 Ports

- **8080:** Nginx (قابل تغییر با APP_PORT)
- **3306:** MySQL (قابل تغییر با DB_PORT)
- **6379:** Redis (قابل تغییر با REDIS_PORT)

## 🔄 به‌روزرسانی

```bash
# دریافت آخرین تغییرات
git pull origin main

# ساخت مجدد
docker compose up -d --build

# اجرای migrationها
docker compose exec app php /var/www/html/application/artisan migrate --force

# پاک کردن cache
docker compose exec app php /var/www/html/application/artisan config:clear
docker compose exec app php /var/www/html/application/artisan cache:clear
```

## 📞 پشتیبانی

برای مشکلات:
1. لاگ‌ها را بررسی کنید: `docker compose logs`
2. به راهنمای مربوط به سیستم‌عامل مراجعه کنید
3. وضعیت containerها را بررسی کنید: `docker compose ps`

## ✅ چک‌لیست راه‌اندازی

- [ ] Docker نصب شده است
- [ ] پروژه کلون شده است
- [ ] فایل .env تنظیم شده است
- [ ] Containerها ساخته و راه‌اندازی شده‌اند
- [ ] Migrationها اجرا شده‌اند
- [ ] برنامه در http://localhost:8080 قابل دسترسی است
- [ ] لاگ‌ها بررسی شده‌اند
- [ ] رمزهای عبور تغییر کرده‌اند (برای production)

---

**تاریخ ایجاد:** 2024  
**نسخه:** 1.0  
**پروژه:** SameCRM Docker Setup
