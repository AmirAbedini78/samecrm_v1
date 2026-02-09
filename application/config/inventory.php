<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Inventory Default Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for inventory management system
    |
    */

    // Default expiry days if not set for an item
    'default_expiry_days' => env('INVENTORY_DEFAULT_EXPIRY_DAYS', 90),

    // Default alert days before expiry
    'default_alert_days_before' => env('INVENTORY_DEFAULT_ALERT_DAYS', 7),

    // SMS Configuration
    'sms' => [
        'enabled' => env('SMS_ENABLED', false),
        'provider' => env('SMS_PROVIDER', 'ghasedak'),
        'ghasedak' => [
            'api_key' => env('GHASEDAK_API_KEY'),
            'sender' => env('GHASEDAK_SENDER'),
        ],
    ],

    // Email Configuration for Alerts
    'email' => [
        'enabled' => env('INVENTORY_EMAIL_ALERTS_ENABLED', true),
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Inventory System'),
    ],

    // Alert Settings
    'alerts' => [
        'check_interval_minutes' => env('INVENTORY_ALERT_CHECK_INTERVAL', 5),
        'expiry_enabled' => env('INVENTORY_EXPIRY_ALERTS_ENABLED', true),
        'quantity_enabled' => env('INVENTORY_QUANTITY_ALERTS_ENABLED', true),
    ],

    // Pusher Configuration (for real-time notifications)
    'pusher' => [
        'enabled' => env('PUSHER_ENABLED', false),
        'app_id' => env('PUSHER_APP_ID'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'cluster' => env('PUSHER_APP_CLUSTER', 'mt1'),
    ],

    // Threshold for high value quick filter
    'high_value_threshold' => env('INVENTORY_HIGH_VALUE_THRESHOLD', 500000000),

    // فعال بودن فیچرهای وابسته به پایتون (PDF، LSTM، Autoencoder). روی هاست cPanel معمولاً false بگذارید.
    'enable_python_ml' => env('ENABLE_PYTHON_ML', true),

    // PDF extraction (Python script path - relative to project root or absolute)
    'pdf_extract_script' => env('INVENTORY_PDF_EXTRACT_SCRIPT', 'tools/pdf_inventory_extract.py'),
    'python_path' => env('PYTHON_PATH', 'python'),

    // ML scripts (LSTM forecast, Autoencoder anomaly) - relative to project root
    'lstm_forecast_script' => env('INVENTORY_LSTM_SCRIPT', 'tools/lstm_forecast.py'),
    'autoencoder_anomaly_script' => env('INVENTORY_AUTOENCODER_SCRIPT', 'tools/autoencoder_anomaly.py'),
];

