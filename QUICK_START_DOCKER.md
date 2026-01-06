# 🚀 راهنمای سریع Docker - SameCRM

## ⚡ شروع سریع (5 دقیقه)

### Linux:

```bash
# 1. نصب Docker (اگر نصب نیست)
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
newgrp docker

# 2. دریافت پروژه
git clone https://github.com/AmirAbedini78/samecrm_v1.git
cd samecrm_v1

# 3. تنظیم .env
cd application
nano .env  # یا ویرایشگر دیگر
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

### Windows:

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

## 📝 محتوای فایل .env

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

## 🔧 دستورات ضروری

```bash
# راه‌اندازی
docker compose up -d

# توقف
docker compose down

# مشاهده لاگ
docker compose logs -f

# اجرای artisan
docker compose exec app php /var/www/html/application/artisan [command]

# ورود به container
docker compose exec app sh
```

## 📚 مستندات کامل

- **Linux:** [DOCKER_GUIDE_LINUX.md](./DOCKER_GUIDE_LINUX.md)
- **Windows:** [DOCKER_GUIDE_WINDOWS.md](./DOCKER_GUIDE_WINDOWS.md)
- **README:** [DOCKER_README.md](./DOCKER_README.md)

## ❓ مشکلات رایج

### Port در حال استفاده است
```bash
# تغییر port در docker-compose.yml
# APP_PORT: 8081  # به جای 8080
```

### Docker Desktop اجرا نمی‌شود (Windows)
- کامپیوتر را Restart کنید
- Virtualization را در BIOS فعال کنید

### مشکل دسترسی فایل‌ها (Windows)
- Docker Desktop > Settings > Resources > File Sharing
- درایو C: را اضافه کنید

### Windows Containers فعال است (Windows)
- روی آیکون Docker در System Tray راست کلیک کنید
- "Switch to Linux containers" را انتخاب کنید
- برای پروژه‌های Laravel/PHP همیشه از Linux Containers استفاده کنید

---

**نکته:** برای راهنمای کامل، به فایل‌های راهنمای تفصیلی مراجعه کنید.
