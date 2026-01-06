# راهنمای کامل اجرای SameCRM با Docker در لینوکس

این راهنما به صورت کامل و گام به گام نحوه اجرای پروژه SameCRM را با استفاده از Docker در سیستم‌عامل لینوکس توضیح می‌دهد.

## 📋 پیش‌نیازها

### 1. نصب Docker

#### برای Ubuntu/Debian:
```bash
# به‌روزرسانی سیستم
sudo apt update
sudo apt upgrade -y

# نصب پیش‌نیازها
sudo apt install -y apt-transport-https ca-certificates curl gnupg lsb-release

# اضافه کردن GPG key رسمی Docker
# توجه: در دستور gpg باید -o باشد (نه o)
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# اضافه کردن repository Docker
# ابتدا مطمئن شوید پوشه وجود دارد (توجه: sources.list.d نه source.list.d)
sudo mkdir -p /etc/apt/sources.list.d

# اضافه کردن repository
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# اگر lsb_release کار نمی‌کند، می‌توانید کد نام Ubuntu را مستقیماً وارد کنید:
# برای Ubuntu 20.04: jammy
# برای Ubuntu 22.04: jammy  
# برای Ubuntu 24.04: noble
# مثال: echo "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu jammy stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# نصب Docker
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# اضافه کردن کاربر به گروه docker (برای اجرا بدون sudo)
sudo usermod -aG docker $USER

# راه‌اندازی Docker
sudo systemctl enable docker
sudo systemctl start docker

# بررسی نصب
docker --version
docker compose version
```

#### برای CentOS/RHEL/Fedora:
```bash
# نصب Docker
sudo yum install -y yum-utils
sudo yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
sudo yum install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# راه‌اندازی Docker
sudo systemctl enable docker
sudo systemctl start docker

# اضافه کردن کاربر به گروه docker
sudo usermod -aG docker $USER

# بررسی نصب
docker --version
docker compose version
```

**⚠️ مهم:** پس از اضافه کردن کاربر به گروه docker، باید از سیستم خارج شده و دوباره وارد شوید.

## 🚀 مراحل نصب و راه‌اندازی

### مرحله 1: دریافت پروژه

```bash
# کلون کردن پروژه از گیت
git clone https://github.com/AmirAbedini78/samecrm_v1.git
cd samecrm_v1

# یا اگر پروژه را از قبل دارید
cd /path/to/samecrm_v1
```

### مرحله 2: تنظیم فایل .env

```bash
# کپی کردن فایل .env.example (اگر وجود دارد)
# یا ایجاد فایل .env جدید
cd application
nano .env
```

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

### مرحله 3: ساخت و راه‌اندازی Containerها

```bash
# بازگشت به دایرکتوری اصلی پروژه
cd ..

# ساخت و راه‌اندازی تمام سرویس‌ها
docker compose up -d --build
```

این دستور:
- تصویر Docker را می‌سازد
- Containerهای MySQL، Redis، PHP-FPM و Nginx را ایجاد می‌کند
- شبکه‌های Docker را تنظیم می‌کند
- Volumeها را برای ذخیره داده‌ها ایجاد می‌کند

### مرحله 4: بررسی وضعیت Containerها

```bash
# مشاهده وضعیت تمام containerها
docker compose ps

# مشاهده لاگ‌ها
docker compose logs -f

# مشاهده لاگ یک سرویس خاص
docker compose logs -f app
docker compose logs -f mysql
docker compose logs -f nginx
```

### مرحله 5: اجرای Migrationها و Setup اولیه

```bash
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

### مرحله 6: دسترسی به برنامه

پس از اتمام مراحل بالا، می‌توانید به برنامه از طریق مرورگر دسترسی پیدا کنید:

```
http://localhost:8080
```

یا اگر از IP استفاده می‌کنید:

```
http://YOUR_SERVER_IP:8080
```

## 🔧 دستورات مفید

### مدیریت Containerها

```bash
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

```bash
# ورود به container برنامه
docker compose exec app sh

# ورود به container MySQL
docker compose exec mysql mysql -u samecrm_user -p samecrm

# اجرای دستور artisan
docker compose exec app php /var/www/html/application/artisan migrate

# اجرای دستور composer
docker compose exec app composer install --working-dir=/var/www/html/application
```

### مدیریت Database

```bash
# پشتیبان‌گیری از دیتابیس
docker compose exec mysql mysqldump -u samecrm_user -psamecrm_password samecrm > backup.sql

# بازگردانی دیتابیس
docker compose exec -T mysql mysql -u samecrm_user -psamecrm_password samecrm < backup.sql

# مشاهده جداول دیتابیس
docker compose exec mysql mysql -u samecrm_user -psamecrm_password -e "USE samecrm; SHOW TABLES;"
```

### مشاهده و مدیریت لاگ‌ها

```bash
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

```bash
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

## 🐛 رفع مشکلات رایج

### مشکل 0: خطاهای نصب Docker

#### خطای 1: `tee: /etc/apt/source.list.d/docker.list: No such file or directory`

**علت:** مسیر اشتباه است. باید `sources.list.d` باشد نه `source.list.d`

**راه‌حل:**
```bash
# ایجاد پوشه اگر وجود ندارد
sudo mkdir -p /etc/apt/sources.list.d

# سپس دستور را دوباره اجرا کنید (توجه به sources نه source)
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```

#### خطای 2: `Command 'lsb_release' not found`

**علت:** دستور `lsb_release` پیدا نمی‌شود

**راه‌حل:**
```bash
# نصب مجدد lsb-release
sudo apt install --reinstall lsb-release

# یا استفاده از دستور جایگزین
# برای Ubuntu 20.04: focal
# برای Ubuntu 22.04: jammy
# برای Ubuntu 24.04: noble

# اگر lsb_release کار نمی‌کند، می‌توانید مستقیماً کد نام را وارد کنید:
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu jammy stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# یا برای Debian:
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```

#### خطای 3: `curl: (22) The requested URL returned error: 403`

**علت:** مشکل در دسترسی به URL یا مشکل شبکه

**راه‌حل:**
```bash
# روش 1: استفاده از URL جایگزین
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# روش 2: بررسی اتصال اینترنت
ping -c 3 download.docker.com

# روش 3: استفاده از mirror
# اگر در ایران هستید، ممکن است نیاز به VPN یا proxy داشته باشید
```

#### خطای 4: `gpg: dearmoring failed` یا Typo در دستور

**علت:** Typo در دستور - باید `-o` باشد نه `o`

**راه‌حل:**
```bash
# دستور صحیح (توجه به -o):
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# اگر فایل از قبل وجود دارد و می‌خواهید overwrite کنید:
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
# وقتی سوال پرسید، y را وارد کنید
```

#### راه‌حل کامل برای نصب Docker (با رفع تمام خطاها):

```bash
# 1. به‌روزرسانی سیستم
sudo apt update
sudo apt upgrade -y

# 2. نصب پیش‌نیازها
sudo apt install -y apt-transport-https ca-certificates curl gnupg lsb-release

# 3. نصب مجدد lsb-release در صورت نیاز
sudo apt install --reinstall lsb-release

# 4. ایجاد پوشه sources.list.d
sudo mkdir -p /etc/apt/sources.list.d

# 5. اضافه کردن GPG key
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# 6. اضافه کردن repository (با بررسی مسیر)
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# 7. به‌روزرسانی apt
sudo apt update

# 8. نصب Docker
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# 9. اضافه کردن کاربر به گروه docker
sudo usermod -aG docker $USER

# 10. راه‌اندازی Docker
sudo systemctl enable docker
sudo systemctl start docker

# 11. بررسی نصب
docker --version
docker compose version
```

### مشکل 1: Port در حال استفاده است

```bash
# بررسی portهای استفاده شده
sudo netstat -tulpn | grep :8080
sudo netstat -tulpn | grep :3306

# تغییر port در docker-compose.yml
# تغییر APP_PORT و DB_PORT
```

### مشکل 2: مشکل دسترسی به فایل‌ها

```bash
# تنظیم مجدد دسترسی‌ها
sudo chown -R $USER:$USER .
chmod -R 775 application/storage
chmod -R 775 storage
```

### مشکل 3: MySQL اتصال برقرار نمی‌کند

```bash
# بررسی وضعیت MySQL
docker compose ps mysql

# بررسی لاگ‌های MySQL
docker compose logs mysql

# راه‌اندازی مجدد MySQL
docker compose restart mysql
```

### مشکل 4: خطای Permission Denied

```bash
# تنظیم دسترسی‌های صحیح
docker compose exec app chown -R www-data:www-data /var/www/html
docker compose exec app chmod -R 775 /var/www/html/application/storage
```

### مشکل 5: مشکل در Build Docker

```bash
# پاک کردن cacheهای Docker
docker system prune -a

# ساخت مجدد بدون cache
docker compose build --no-cache
```

## 📊 مانیتورینگ و بررسی وضعیت

```bash
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

### فایروال

```bash
# باز کردن port 8080 در UFW (Ubuntu)
sudo ufw allow 8080/tcp

# باز کردن port در firewalld (CentOS/RHEL)
sudo firewall-cmd --permanent --add-port=8080/tcp
sudo firewall-cmd --reload
```

## 📝 نکات مهم

1. **Backup منظم:** همیشه از دیتابیس و فایل‌های مهم backup بگیرید
2. **به‌روزرسانی:** Docker و تصاویر را به‌طور منظم به‌روزرسانی کنید
3. **لاگ‌ها:** لاگ‌ها را به‌طور منظم بررسی کنید
4. **منابع سیستم:** استفاده از CPU و RAM را مانیتور کنید
5. **SSL/TLS:** برای production از SSL استفاده کنید (Let's Encrypt)

## 🎯 دستورات سریع (Quick Reference)

```bash
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

## 📞 پشتیبانی

در صورت بروز مشکل، لاگ‌ها را بررسی کنید و در صورت نیاز با تیم توسعه تماس بگیرید.

---

**نکته:** این راهنما برای محیط production نوشته شده است. برای محیط development ممکن است نیاز به تغییراتی در تنظیمات باشد.
