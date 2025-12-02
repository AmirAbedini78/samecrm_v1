<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>هشدار انقضای کالا</title>
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
            background-color: #dc3545;
            color: #ffffff;
            padding: 20px;
            text-align: center;
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
        .alert-box.expired {
            background-color: #f8d7da;
            border-color: #dc3545;
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
        <div class="header">
            <h1>هشدار انقضای کالا</h1>
        </div>
        <div class="content">
            <div class="alert-box {{ $isExpired ? 'expired' : '' }}">
                @if($isExpired)
                    <strong>⚠️ کالای زیر منقضی شده است!</strong>
                @else
                    <strong>⚠️ کالای زیر نزدیک به انقضا است!</strong>
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
                <span class="info-label">تاریخ انقضا:</span>
                <span class="info-value">{{ $expiryDate }}</span>
            </div>

            @if(!$isExpired)
            <div class="info-row">
                <span class="info-label">روزهای باقی‌مانده:</span>
                <span class="info-value">{{ $daysUntil }} روز</span>
            </div>
            @endif

            <div class="info-row">
                <span class="info-label">موجودی فعلی:</span>
                <span class="info-value">{{ $currentQuantity }} {{ $unit }}</span>
            </div>

            @if($warehouse)
            <div class="info-row">
                <span class="info-label">انبار:</span>
                <span class="info-value">{{ $warehouse }}</span>
            </div>
            @endif

            <p style="margin-top: 30px; color: #666;">
                لطفاً در اسرع وقت نسبت به بررسی و اقدام لازم اقدام فرمایید.
            </p>
        </div>
        <div class="footer">
            این ایمیل به صورت خودکار از سیستم مدیریت انبار ارسال شده است.
        </div>
    </div>
</body>
</html>


