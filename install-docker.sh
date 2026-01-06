#!/bin/bash

# اسکریپت نصب Docker برای Ubuntu/Debian
# این اسکریپت تمام خطاهای رایج را در نظر می‌گیرد

set -e  # توقف در صورت خطا

echo "=========================================="
echo "  نصب Docker برای SameCRM"
echo "=========================================="
echo ""

# بررسی دسترسی root
if [ "$EUID" -ne 0 ]; then 
    echo "⚠️  این اسکریپت باید با sudo اجرا شود"
    echo "استفاده: sudo bash install-docker.sh"
    exit 1
fi

# 1. به‌روزرسانی سیستم
echo "📦 مرحله 1: به‌روزرسانی سیستم..."
apt update
apt upgrade -y
echo "✅ به‌روزرسانی انجام شد"
echo ""

# 2. نصب پیش‌نیازها
echo "📦 مرحله 2: نصب پیش‌نیازها..."
apt install -y apt-transport-https ca-certificates curl gnupg lsb-release
echo "✅ پیش‌نیازها نصب شدند"
echo ""

# 3. نصب/بازنصب lsb-release
echo "📦 مرحله 3: بررسی lsb-release..."
apt install --reinstall -y lsb-release 2>/dev/null || true
echo "✅ lsb-release آماده است"
echo ""

# 4. ایجاد پوشه sources.list.d
echo "📁 مرحله 4: ایجاد پوشه sources.list.d..."
mkdir -p /etc/apt/sources.list.d
echo "✅ پوشه ایجاد شد"
echo ""

# 5. اضافه کردن GPG key
echo "🔑 مرحله 5: اضافه کردن GPG key Docker..."
# حذف فایل قبلی در صورت وجود
if [ -f /usr/share/keyrings/docker-archive-keyring.gpg ]; then
    echo "⚠️  فایل GPG از قبل وجود دارد. در حال overwrite..."
    rm -f /usr/share/keyrings/docker-archive-keyring.gpg
fi

# دانلود و اضافه کردن GPG key
if curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg 2>/dev/null; then
    echo "✅ GPG key اضافه شد"
else
    echo "❌ خطا در دانلود GPG key"
    echo "💡 در حال تلاش با روش جایگزین..."
    # روش جایگزین: دانلود مستقیم
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /tmp/docker.gpg
    gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg /tmp/docker.gpg
    rm -f /tmp/docker.gpg
    echo "✅ GPG key با روش جایگزین اضافه شد"
fi
echo ""

# 6. تشخیص کد نام Ubuntu/Debian
echo "🔍 مرحله 6: تشخیص نسخه سیستم..."
if command -v lsb_release &> /dev/null; then
    CODENAME=$(lsb_release -cs)
    echo "✅ کد نام: $CODENAME"
else
    echo "⚠️  lsb_release پیدا نشد. استفاده از کد نام پیش‌فرض..."
    # تشخیص از /etc/os-release
    if [ -f /etc/os-release ]; then
        CODENAME=$(grep VERSION_CODENAME /etc/os-release | cut -d= -f2)
        if [ -z "$CODENAME" ]; then
            CODENAME="jammy"  # پیش‌فرض برای Ubuntu 22.04
        fi
    else
        CODENAME="jammy"  # پیش‌فرض
    fi
    echo "✅ کد نام (پیش‌فرض): $CODENAME"
fi
echo ""

# 7. تشخیص معماری
ARCH=$(dpkg --print-architecture)
echo "🏗️  معماری سیستم: $ARCH"
echo ""

# 8. اضافه کردن repository
echo "📝 مرحله 7: اضافه کردن repository Docker..."
# حذف فایل قبلی در صورت وجود
if [ -f /etc/apt/sources.list.d/docker.list ]; then
    echo "⚠️  فایل repository از قبل وجود دارد. در حال overwrite..."
    rm -f /etc/apt/sources.list.d/docker.list
fi

# تشخیص توزیع
if [ -f /etc/debian_version ]; then
    # Debian
    echo "deb [arch=$ARCH signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian $CODENAME stable" > /etc/apt/sources.list.d/docker.list
    echo "✅ Repository برای Debian اضافه شد"
else
    # Ubuntu
    echo "deb [arch=$ARCH signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $CODENAME stable" > /etc/apt/sources.list.d/docker.list
    echo "✅ Repository برای Ubuntu اضافه شد"
fi
echo ""

# 9. به‌روزرسانی apt
echo "🔄 مرحله 8: به‌روزرسانی apt..."
apt update
echo "✅ apt به‌روزرسانی شد"
echo ""

# 10. نصب Docker
echo "🐳 مرحله 9: نصب Docker..."
apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
echo "✅ Docker نصب شد"
echo ""

# 11. راه‌اندازی Docker
echo "🚀 مرحله 10: راه‌اندازی Docker..."
systemctl enable docker
systemctl start docker
echo "✅ Docker راه‌اندازی شد"
echo ""

# 12. اضافه کردن کاربر به گروه docker
echo "👤 مرحله 11: اضافه کردن کاربر به گروه docker..."
if [ -n "$SUDO_USER" ]; then
    usermod -aG docker "$SUDO_USER"
    echo "✅ کاربر $SUDO_USER به گروه docker اضافه شد"
else
    echo "⚠️  نتوانست کاربر را تشخیص داد. لطفاً دستی اضافه کنید:"
    echo "   sudo usermod -aG docker \$USER"
fi
echo ""

# 13. بررسی نصب
echo "✅ مرحله 12: بررسی نصب..."
echo ""
echo "نسخه Docker:"
docker --version
echo ""
echo "نسخه Docker Compose:"
docker compose version
echo ""

# 14. نمایش وضعیت
echo "📊 وضعیت سرویس Docker:"
systemctl status docker --no-pager | head -n 5
echo ""

echo "=========================================="
echo "  ✅ نصب Docker با موفقیت انجام شد!"
echo "=========================================="
echo ""
echo "⚠️  نکات مهم:"
echo "   1. برای اعمال تغییرات گروه docker، باید از سیستم خارج شده و دوباره وارد شوید"
echo "   2. پس از ورود مجدد، می‌توانید بدون sudo از docker استفاده کنید"
echo "   3. برای بررسی: docker ps"
echo ""
echo "📚 برای ادامه راه‌اندازی SameCRM، به فایل DOCKER_GUIDE_LINUX.md مراجعه کنید"
echo ""
