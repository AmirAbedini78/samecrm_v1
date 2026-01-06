# 🐳 راهنمای Docker برای SameCRM

این پروژه شامل فایل‌های Docker کامل برای اجرای SameCRM در محیط‌های Linux و Windows است.

## 📁 ساختار فایل‌های Docker

```
.
├── Dockerfile                 # فایل اصلی Docker برای ساخت تصویر
├── docker-compose.yml        # تنظیمات Docker Compose
├── .dockerignore            # فایل‌های نادیده گرفته شده در Docker
├── docker/
│   ├── nginx/
│   │   └── default.conf     # تنظیمات Nginx
│   ├── supervisor/
│   │   └── supervisord.conf # تنظیمات Supervisor
│   ├── mysql/
│   │   └── my.cnf           # تنظیمات MySQL
│   └── start.sh             # اسکریپت راه‌اندازی
├── DOCKER_GUIDE_LINUX.md    # راهنمای کامل برای Linux
└── DOCKER_GUIDE_WINDOWS.md  # راهنمای کامل برای Windows
```

## 🚀 شروع سریع

### برای Linux:
```bash
# نصب Docker (اگر نصب نیست)
# دستورات نصب در DOCKER_GUIDE_LINUX.md موجود است

# کلون کردن پروژه
git clone https://github.com/AmirAbedini78/samecrm_v1.git
cd samecrm_v1

# تنظیم .env
cd application
cp .env.example .env  # یا فایل .env را ایجاد کنید
cd ..

# راه‌اندازی
docker compose up -d --build

# دسترسی به برنامه
# http://localhost:8080
```

### برای Windows:
```powershell
# نصب Docker Desktop (اگر نصب نیست)
# راهنمای نصب در DOCKER_GUIDE_WINDOWS.md موجود است

# کلون کردن پروژه
git clone https://github.com/AmirAbedini78/samecrm_v1.git
cd samecrm_v1

# تنظیم .env
cd application
# ایجاد فایل .env با Notepad یا ویرایشگر دیگر
cd ..

# راه‌اندازی
docker compose up -d --build

# دسترسی به برنامه
# http://localhost:8080
```

## 📚 مستندات کامل

برای راهنمای کامل و تفصیلی، به فایل‌های زیر مراجعه کنید:

- **Linux:** [DOCKER_GUIDE_LINUX.md](./DOCKER_GUIDE_LINUX.md)
- **Windows:** [DOCKER_GUIDE_WINDOWS.md](./DOCKER_GUIDE_WINDOWS.md)

## 🏗️ معماری Docker

این پروژه از Docker Compose استفاده می‌کند و شامل سرویس‌های زیر است:

1. **app** - PHP-FPM با Laravel
2. **nginx** - وب سرور Nginx
3. **mysql** - پایگاه داده MySQL 8.0
4. **redis** - کش Redis (اختیاری)

## ⚙️ تنظیمات پیش‌فرض

### Ports:
- **Application:** `8080` (قابل تغییر در docker-compose.yml)
- **MySQL:** `3306` (قابل تغییر در docker-compose.yml)
- **Redis:** `6379` (قابل تغییر در docker-compose.yml)

### Database:
- **Database Name:** `samecrm`
- **Username:** `samecrm_user`
- **Password:** `samecrm_password`
- **Root Password:** `rootpassword`

⚠️ **هشدار:** برای محیط production حتماً این رمزهای عبور را تغییر دهید!

## 🔧 دستورات مهم

```bash
# راه‌اندازی
docker compose up -d --build

# توقف
docker compose down

# مشاهده لاگ
docker compose logs -f

# اجرای دستور artisan
docker compose exec app php /var/www/html/application/artisan [command]

# ورود به container
docker compose exec app sh

# پشتیبان‌گیری دیتابیس
docker compose exec mysql mysqldump -u samecrm_user -psamecrm_password samecrm > backup.sql
```

## 📋 پیش‌نیازها

### Linux:
- Docker Engine 20.10+
- Docker Compose 2.0+
- Git

### Windows:
- Docker Desktop 4.0+
- WSL 2 (توصیه می‌شود)
- Git

## 🔍 بررسی وضعیت

```bash
# بررسی وضعیت containerها
docker compose ps

# بررسی استفاده از منابع
docker stats

# بررسی لاگ‌ها
docker compose logs -f app
```

## 🐛 رفع مشکلات

برای راهنمای رفع مشکلات، به بخش "رفع مشکلات رایج" در فایل‌های راهنمای Linux یا Windows مراجعه کنید.

## 📝 نکات مهم

1. **اولین اجرا:** اولین بار ممکن است چند دقیقه طول بکشد (دانلود تصاویر)
2. **دسترسی فایل‌ها:** در Windows مطمئن شوید File Sharing در Docker Desktop فعال است
3. **Ports:** اگر port 8080 استفاده شده، در docker-compose.yml تغییر دهید
4. **Backup:** همیشه از دیتابیس backup بگیرید
5. **Environment:** فایل .env را برای تنظیمات محیطی ویرایش کنید

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
1. لاگ‌ها را بررسی کنید
2. به راهنمای مربوط به سیستم‌عامل خود مراجعه کنید
3. با تیم توسعه تماس بگیرید

---

**نسخه:** 1.0  
**تاریخ:** 2024  
**پروژه:** SameCRM
