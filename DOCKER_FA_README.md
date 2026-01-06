# 🐳 راهنمای کامل Docker برای SameCRM

## ✅ فایل‌های ایجاد شده

تمام فایل‌های لازم برای اجرای پروژه SameCRM با Docker برای **Linux** و **Windows** ایجاد شده است.

### 📦 فایل‌های اصلی:

1. **Dockerfile** - فایل اصلی برای ساخت تصویر Docker
2. **docker-compose.yml** - تنظیمات Docker Compose برای Production
3. **docker-compose.dev.yml** - تنظیمات Docker Compose برای Development
4. **.dockerignore** - فایل‌های نادیده گرفته شده در Docker

### 📁 پوشه docker/:

- **docker/nginx/default.conf** - تنظیمات Nginx
- **docker/supervisor/supervisord.conf** - تنظیمات Supervisor
- **docker/mysql/my.cnf** - تنظیمات MySQL
- **docker/start.sh** - اسکریپت راه‌اندازی خودکار

### 📚 مستندات:

1. **DOCKER_README.md** - README اصلی Docker
2. **DOCKER_GUIDE_LINUX.md** - راهنمای کامل برای Linux (صفر تا صد)
3. **DOCKER_GUIDE_WINDOWS.md** - راهنمای کامل برای Windows (صفر تا صد)
4. **QUICK_START_DOCKER.md** - راهنمای سریع 5 دقیقه‌ای
5. **DOCKER_SETUP_SUMMARY.md** - خلاصه کامل فایل‌ها و معماری

## 🚀 شروع سریع

### برای Linux:

```bash
# 1. نصب Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
newgrp docker

# 2. دریافت پروژه
git clone https://github.com/AmirAbedini78/samecrm_v1.git
cd samecrm_v1

# 3. تنظیم .env
cd application
nano .env
# محتوای .env را از DOCKER_README.md کپی کنید
cd ..

# 4. راه‌اندازی
docker compose up -d --build

# 5. Setup اولیه
docker compose exec app php /var/www/html/application/artisan key:generate
docker compose exec app php /var/www/html/application/artisan migrate --force

# 6. دسترسی
# http://localhost:8080
```

### برای Windows:

```powershell
# 1. نصب Docker Desktop
# دانلود از: https://www.docker.com/products/docker-desktop
# در نصب: "Allow Windows containers" را انتخاب نکنید (از Linux Containers استفاده می‌کنیم)
# نصب و Restart کامپیوتر

# 2. دریافت پروژه
git clone https://github.com/AmirAbedini78/samecrm_v1.git
cd samecrm_v1

# 3. تنظیم .env
cd application
notepad .env
# محتوای .env را از DOCKER_README.md کپی کنید
cd ..

# 4. راه‌اندازی
docker compose up -d --build

# 5. Setup اولیه
docker compose exec app php /var/www/html/application/artisan key:generate
docker compose exec app php /var/www/html/application/artisan migrate --force

# 6. دسترسی
# http://localhost:8080
```

## 📖 مستندات تفصیلی

برای راهنمای کامل و تفصیلی، به فایل‌های زیر مراجعه کنید:

### برای Linux:
📄 **[DOCKER_GUIDE_LINUX.md](./DOCKER_GUIDE_LINUX.md)**
- نصب Docker در Ubuntu/Debian/CentOS
- مراحل کامل راه‌اندازی
- دستورات مفید
- رفع مشکلات رایج
- نکات امنیتی

### برای Windows:
📄 **[DOCKER_GUIDE_WINDOWS.md](./DOCKER_GUIDE_WINDOWS.md)**
- نصب Docker Desktop
- فعال‌سازی WSL 2
- مراحل کامل راه‌اندازی
- دستورات PowerShell
- رفع مشکلات خاص Windows
- تنظیمات Docker Desktop

### راهنمای سریع:
📄 **[QUICK_START_DOCKER.md](./QUICK_START_DOCKER.md)**
- راهنمای 5 دقیقه‌ای
- دستورات ضروری
- محتوای فایل .env

### خلاصه معماری:
📄 **[DOCKER_SETUP_SUMMARY.md](./DOCKER_SETUP_SUMMARY.md)**
- توضیح تمام فایل‌ها
- معماری سیستم
- Volumeها و Networkها

## 🏗️ معماری Docker

پروژه شامل 4 سرویس اصلی است:

1. **app** - PHP-FPM 8.2 با Laravel
2. **nginx** - وب سرور Nginx
3. **mysql** - پایگاه داده MySQL 8.0
4. **redis** - کش Redis

## ⚙️ تنظیمات پیش‌فرض

- **Application Port:** 8080
- **MySQL Port:** 3306
- **Redis Port:** 6379
- **Database:** samecrm
- **Username:** samecrm_user
- **Password:** samecrm_password

⚠️ **هشدار:** برای محیط production حتماً رمزهای عبور را تغییر دهید!

## 🔧 دستورات مهم

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

## 📋 محتوای فایل .env

فایل `.env` باید در پوشه `application/` قرار گیرد:

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

**نکته:** `APP_KEY` به صورت خودکار تولید می‌شود.

## 🐛 رفع مشکلات

برای راهنمای رفع مشکلات، به بخش "رفع مشکلات رایج" در فایل‌های راهنمای Linux یا Windows مراجعه کنید.

### مشکلات رایج:

1. **Port در حال استفاده است** - Port را در docker-compose.yml تغییر دهید
2. **Docker Desktop اجرا نمی‌شود (Windows)** - کامپیوتر را Restart کنید
3. **مشکل دسترسی فایل‌ها (Windows)** - File Sharing را در Docker Desktop فعال کنید
4. **MySQL اتصال برقرار نمی‌کند** - Health check را بررسی کنید

## 📝 نکات مهم

1. ✅ **اولین اجرا:** ممکن است چند دقیقه طول بکشد (دانلود تصاویر)
2. ✅ **دسترسی فایل‌ها:** در Windows File Sharing را فعال کنید
3. ✅ **Ports:** اگر port 8080 استفاده شده، تغییر دهید
4. ✅ **Backup:** همیشه از دیتابیس backup بگیرید
5. ✅ **امنیت:** رمزهای عبور را تغییر دهید

## 🔄 به‌روزرسانی

```bash
# دریافت آخرین تغییرات
git pull origin main

# ساخت مجدد
docker compose up -d --build

# اجرای migrationها
docker compose exec app php /var/www/html/application/artisan migrate --force
```

## 📞 پشتیبانی

در صورت بروز مشکل:
1. لاگ‌ها را بررسی کنید: `docker compose logs`
2. به راهنمای مربوط به سیستم‌عامل خود مراجعه کنید
3. وضعیت containerها را بررسی کنید: `docker compose ps`

## ✅ چک‌لیست راه‌اندازی

- [ ] Docker نصب شده است
- [ ] پروژه کلون شده است
- [ ] فایل .env در پوشه application/ تنظیم شده است
- [ ] Containerها ساخته و راه‌اندازی شده‌اند
- [ ] Migrationها اجرا شده‌اند
- [ ] برنامه در http://localhost:8080 قابل دسترسی است
- [ ] لاگ‌ها بررسی شده‌اند
- [ ] رمزهای عبور تغییر کرده‌اند (برای production)

---

## 📚 فهرست مستندات

1. **[DOCKER_README.md](./DOCKER_README.md)** - README اصلی
2. **[DOCKER_GUIDE_LINUX.md](./DOCKER_GUIDE_LINUX.md)** - راهنمای کامل Linux
3. **[DOCKER_GUIDE_WINDOWS.md](./DOCKER_GUIDE_WINDOWS.md)** - راهنمای کامل Windows
4. **[QUICK_START_DOCKER.md](./QUICK_START_DOCKER.md)** - راهنمای سریع
5. **[DOCKER_SETUP_SUMMARY.md](./DOCKER_SETUP_SUMMARY.md)** - خلاصه معماری

---

**نسخه:** 1.0  
**تاریخ:** 2024  
**پروژه:** SameCRM Docker Setup

**نکته:** تمام مستندات به زبان فارسی نوشته شده‌اند و شامل دستورالعمل‌های کامل از صفر تا صد هستند.
