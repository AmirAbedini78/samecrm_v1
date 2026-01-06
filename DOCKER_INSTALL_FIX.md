# 🔧 راهنمای رفع خطاهای نصب Docker در Linux

این فایل شامل راه‌حل‌های کامل برای خطاهای رایج در نصب Docker است.

## ❌ خطاهای رایج و راه‌حل‌ها

### خطای 1: `tee: /etc/apt/source.list.d/docker.list: No such file or directory`

**مشکل:** مسیر اشتباه است. باید `sources.list.d` باشد نه `source.list.d`

**راه‌حل:**
```bash
# ایجاد پوشه صحیح
sudo mkdir -p /etc/apt/sources.list.d

# سپس دستور را دوباره اجرا کنید
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```

**نکته:** همیشه `sources.list.d` (با s) نه `source.list.d`

---

### خطای 2: `Command 'lsb_release' not found`

**مشکل:** دستور `lsb_release` پیدا نمی‌شود

**راه‌حل 1: نصب مجدد**
```bash
sudo apt install --reinstall lsb-release
```

**راه‌حل 2: استفاده از کد نام مستقیماً**
```bash
# برای Ubuntu 20.04
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu focal stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# برای Ubuntu 22.04
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu jammy stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# برای Ubuntu 24.04
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu noble stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```

**راه‌حل 3: بررسی نسخه Ubuntu**
```bash
# بررسی نسخه
cat /etc/os-release

# سپس کد نام را از خروجی پیدا کنید (مثلاً VERSION_CODENAME=jammy)
```

---

### خطای 3: `curl: (22) The requested URL returned error: 403`

**مشکل:** مشکل در دسترسی به URL Docker

**راه‌حل 1: بررسی اتصال**
```bash
# بررسی اتصال به سرور Docker
ping -c 3 download.docker.com

# بررسی دسترسی به URL
curl -I https://download.docker.com/linux/ubuntu/gpg
```

**راه‌حل 2: استفاده از VPN (اگر در ایران هستید)**
```bash
# ممکن است نیاز به VPN داشته باشید
# یا استفاده از mirror داخلی
```

**راه‌حل 3: دانلود دستی GPG key**
```bash
# دانلود فایل
wget https://download.docker.com/linux/ubuntu/gpg -O /tmp/docker.gpg

# تبدیل به فرمت مناسب
sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg /tmp/docker.gpg
```

---

### خطای 4: `gpg: dearmoring failed` یا `gpg: no valid OpenPGP data found`

**مشکل:** Typo در دستور یا مشکل در دانلود فایل

**راه‌حل:**
```bash
# دستور صحیح (توجه به -o):
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# اگر فایل از قبل وجود دارد:
sudo rm /usr/share/keyrings/docker-archive-keyring.gpg
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
```

**نکته:** همیشه `--dearmor -o` باشد (با خط تیره قبل از o)

---

## ✅ دستورات کامل نصب Docker (بدون خطا)

```bash
#!/bin/bash

# 1. به‌روزرسانی سیستم
echo "به‌روزرسانی سیستم..."
sudo apt update
sudo apt upgrade -y

# 2. نصب پیش‌نیازها
echo "نصب پیش‌نیازها..."
sudo apt install -y apt-transport-https ca-certificates curl gnupg lsb-release

# 3. نصب مجدد lsb-release در صورت نیاز
echo "بررسی lsb-release..."
sudo apt install --reinstall -y lsb-release

# 4. ایجاد پوشه sources.list.d
echo "ایجاد پوشه sources.list.d..."
sudo mkdir -p /etc/apt/sources.list.d

# 5. اضافه کردن GPG key
echo "اضافه کردن GPG key..."
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# 6. بررسی نسخه Ubuntu
echo "بررسی نسخه Ubuntu..."
UBUNTU_CODENAME=$(lsb_release -cs 2>/dev/null || echo "jammy")
echo "کد نام Ubuntu: $UBUNTU_CODENAME"

# 7. اضافه کردن repository
echo "اضافه کردن repository Docker..."
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $UBUNTU_CODENAME stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# 8. به‌روزرسانی apt
echo "به‌روزرسانی apt..."
sudo apt update

# 9. نصب Docker
echo "نصب Docker..."
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# 10. اضافه کردن کاربر به گروه docker
echo "اضافه کردن کاربر به گروه docker..."
sudo usermod -aG docker $USER

# 11. راه‌اندازی Docker
echo "راه‌اندازی Docker..."
sudo systemctl enable docker
sudo systemctl start docker

# 12. بررسی نصب
echo "بررسی نصب..."
docker --version
docker compose version

echo "✅ نصب Docker با موفقیت انجام شد!"
echo "⚠️ توجه: برای اعمال تغییرات گروه docker، باید از سیستم خارج شده و دوباره وارد شوید."
```

---

## 🔍 بررسی مشکلات

### بررسی مسیرها
```bash
# بررسی وجود پوشه
ls -la /etc/apt/sources.list.d/

# بررسی فایل GPG key
ls -la /usr/share/keyrings/docker-archive-keyring.gpg
```

### بررسی repository
```bash
# بررسی محتوای فایل repository
cat /etc/apt/sources.list.d/docker.list

# بررسی apt update
sudo apt update
```

### بررسی نصب Docker
```bash
# بررسی سرویس Docker
sudo systemctl status docker

# بررسی نسخه
docker --version
docker compose version
```

---

## 📝 نکات مهم

1. **مسیر صحیح:** همیشه `/etc/apt/sources.list.d/` (با s) استفاده کنید
2. **دستور gpg:** همیشه `--dearmor -o` (با خط تیره قبل از o)
3. **lsb_release:** اگر کار نمی‌کند، کد نام را مستقیماً وارد کنید
4. **403 Error:** ممکن است نیاز به VPN داشته باشید
5. **پس از نصب:** حتماً از سیستم خارج شده و دوباره وارد شوید

---

## 🆘 اگر هنوز مشکل دارید

1. تمام خطاها را کپی کنید
2. خروجی `cat /etc/os-release` را بررسی کنید
3. خروجی `dpkg --print-architecture` را بررسی کنید
4. لاگ‌های کامل را ذخیره کنید

---

**نکته:** این راهنما برای Ubuntu/Debian نوشته شده است. برای سایر توزیع‌های Linux به راهنمای اصلی مراجعه کنید.
