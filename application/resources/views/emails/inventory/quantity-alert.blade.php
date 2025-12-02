<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>هشدار موجودی کالا</title>
    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
            direction: rtl;
            text-align: right;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #ffc107;
            color: #000000;
            padding: 20px;
            text-align: center;
        }
        .header.low {
            background-color: #dc3545;
            color: #ffffff;
        }
        .header.high {
            background-color: #17a2b8;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .alert-box.low {
            background-color: #f8d7da;
            border-color: #dc3545;
        }
        .alert-box.high {
            background-color: #d1ecf1;
            border-color: #17a2b8;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            color: #666;
        }
        .quantity-highlight {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $alertType }}">
            <h1>هشدار موجودی کالا</h1>
        </div>
        <div class="content">
            <div class="alert-box {{ $alertType }}">
                @if($alertType === 'low')
                    <strong>⚠️ موجودی کالای زیر به حداقل رسیده است!</strong>
                @else
                    <strong>⚠️ موجودی کالای زیر به حداکثر رسیده است!</strong>
                @endif
            </div>

            <div class="info-row">
                <span class="info-label">نام کالا:</span>
                <span class="info-value">{{ $inventoryName }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">کد کالا:</span>
                <span class="info-value">{{ $inventoryCode }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">موجودی فعلی:</span>
                <span class="info-value quantity-highlight">{{ $currentQuantity }} {{ $unit }}</span>
            </div>

            @if($alertType === 'low')
            <div class="info-row">
                <span class="info-label">حداقل موجودی:</span>
                <span class="info-value">{{ $minimumStock }} {{ $unit }}</span>
            </div>
            @else
            <div class="info-row">
                <span class="info-label">حداکثر موجودی:</span>
                <span class="info-value">{{ $maximumStock }} {{ $unit }}</span>
            </div>
            @endif

            @if($warehouse)
            <div class="info-row">
                <span class="info-label">انبار:</span>
                <span class="info-value">{{ $warehouse }}</span>
            </div>
            @endif

            <p style="margin-top: 30px; color: #666;">
                @if($alertType === 'low')
                    لطفاً نسبت به سفارش و تأمین کالا اقدام فرمایید.
                @else
                    موجودی کالا از حد مجاز تجاوز کرده است. لطفاً بررسی فرمایید.
                @endif
            </p>
        </div>
        <div class="footer">
            این ایمیل به صورت خودکار از سیستم مدیریت انبار ارسال شده است.
        </div>
    </div>
</body>
</html>


