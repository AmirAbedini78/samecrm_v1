# ابزارهای ML/هوش مصنوعی – چک‌لیست نصب و اجرا

## ۱. پایتون

- **نسخه:** Python 3.8 تا 3.11 (برای TensorFlow 2.10).
- در ویندوز از **Command Prompt** یا **PowerShell** اجرا کنید:
  ```bash
  python --version
  ```
  اگر دستور `python` شناخته نشد، امتحان کنید:
  ```bash
  py -3 --version
  ```
  در آن صورت در فایل `.env` پروژه مقدار دهید:
  ```env
  PYTHON_PATH=py -3
  ```
  یا مسیر کامل مفسر، مثلاً:
  ```env
  PYTHON_PATH=C:\Users\USERNAME\AppData\Local\Programs\Python\Python311\python.exe
  ```

---

## ۲. پکیج‌های نصب‌شده با `requirements-ml.txt`

| پکیج | کاربرد |
|------|--------|
| **pdfplumber** | استخراج متن و جدول از PDF (ایمپورت گردش کالا از PDF). |
| **pandas, numpy** | پیش‌پردازش داده در اسکریپت‌ها. |
| **scikit-learn** | Autoencoder (در صورت نبود TensorFlow) و امور کمکی. |
| **scipy** | محاسبات علمی. |
| **tensorflow** | LSTM برای پیش‌بینی تقاضا و Autoencoder برای تشخیص ناهنجاری. |

اگر `pip install -r tools/requirements-ml.txt` را بدون خطا اجرا کرده‌اید، **چیز دیگری از نظر پایتون لازم نیست.**

---

## ۳. تنظیمات اختیاری در Laravel (`.env`)

**لاراگون (ویندوز)** – اگر پایتون در مسیر زیر نصب است:
```env
PYTHON_PATH=C:\laragon\bin\python\python-3.13\python.exe
```
(حتماً به فایل `python.exe` داخل پوشه ختم شود.)

در غیر این صورت:
```env
# اگر python در PATH نیست، مسیر کامل یا دستور (مثلاً py -3) را بگذارید
PYTHON_PATH=python

# در صورت جابجایی اسکریپت‌ها، مسیر را عوض کنید (نسبت به ریشه پروژه)
# INVENTORY_PDF_EXTRACT_SCRIPT=tools/pdf_inventory_extract.py
# INVENTORY_LSTM_SCRIPT=tools/lstm_forecast.py
# INVENTORY_AUTOENCODER_SCRIPT=tools/autoencoder_anomaly.py
```

پیش‌فرض‌ها درست است؛ معمولاً نیازی به این خطوط نیست.

---

## ۴. تست سریع از ترمینال

```bash
cd C:\laragon\www\samecrm_v1

# تست PDF (خروجی JSON)
python tools/pdf_inventory_extract.py --pdf "مسیر\به\یک\فایل.pdf"

# تست LSTM (نیاز به فایل JSON نمونه)
echo {"series": [10,12,11,13,14,15,16,17,18,19,20]} > temp_series.json
python tools/lstm_forecast.py --input temp_series.json --steps 4

# تست Autoencoder (نیاز به فایل JSON نمونه)
echo {"days": [{"date": "2024-01-01", "amount": 1000, "count": 5}, {"date": "2024-01-02", "amount": 1200, "count": 6}]} > temp_days.json
python tools/autoencoder_anomaly.py --input temp_days.json
```

اگر هر سه دستور بدون خطای import و اجرا انجام شدند، محیط پایتون و اسکریپت‌ها آماده‌اند.

---

## ۵. خلاصه: آیا چیز دیگری مانده؟

| مورد | وضعیت |
|------|--------|
| نصب پکیج‌های `requirements-ml.txt` | ✅ شما انجام دادید. |
| پایتون در PATH یا تنظیم `PYTHON_PATH` در `.env` | فقط اگر از داخل Laravel خطای «python not found» دیدید. |
| پکیج یا ابزار اضافی دیگر | ❌ لازم نیست. |
| تغییر در Composer/PHP | ❌ نیازی نیست. |

**جمع‌بندی:** با نصب موفق `requirements-ml.txt` و در دسترس بودن دستور `python` (یا تنظیم `PYTHON_PATH`)، همه فیچرهای ML (PDF، LSTM، Autoencoder) از نظر ابزار آماده‌اند. در صورت خطا، معمولاً یا مسیر پایتون اشتباه است یا یک پکیج (مثلاً TensorFlow) ناقص نصب شده؛ با پیام خطا می‌توان همان را رفع کرد.
