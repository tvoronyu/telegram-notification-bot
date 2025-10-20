<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Telegram Bot API settings. You'll need an
    | API key from your Telegram bot service.
    |
    */

    'api_key' => env('TELEGRAM_BOT_API_KEY'),

    'notify_url' => env('TELEGRAM_BOT_NOTIFY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Default Bot and Chat Configuration
    |--------------------------------------------------------------------------
    |
    | These values will be used as defaults when sending notifications.
    |
    */

    'default_bot_id' => env('TELEGRAM_BOT_DEFAULT_BOT_ID', 1),

    'default_chat_id' => env('TELEGRAM_BOT_DEFAULT_CHAT_ID', '-1001234567890'),

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which queue to use for notifications.
    | If not set, will use the default queue.
    |
    */

    'queue' => env('TELEGRAM_BOT_QUEUE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Specialized Chat IDs
    |--------------------------------------------------------------------------
    |
    | Configure different chat IDs for different types of notifications.
    | If not set, the default_chat_id will be used.
    |
    */

    'chats' => [
        'parcels' => env('TELEGRAM_BOT_PARCELS_CHAT_ID'),
        'errors' => env('TELEGRAM_BOT_ERRORS_CHAT_ID'),
        'errors_1c' => env('TELEGRAM_BOT_ERRORS_1C_CHAT_ID'),
        'orders' => env('TELEGRAM_BOT_ORDERS_CHAT_ID'),
        'reports' => env('TELEGRAM_BOT_REPORTS_CHAT_ID'),
        'system' => env('TELEGRAM_BOT_SYSTEM_CHAT_ID'),
        'status_updates' => env('TELEGRAM_BOT_STATUS_UPDATES_CHAT_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Specialized Bot IDs
    |--------------------------------------------------------------------------
    |
    | Configure different bot IDs for different types of notifications.
    | If not set, the default_bot_id will be used.
    |
    */

    'bots' => [
        'errors' => env('TELEGRAM_BOT_ERRORS_BOT_ID'),
        'errors_1c' => env('TELEGRAM_BOT_ERRORS_1C_BOT_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backward Compatibility
    |--------------------------------------------------------------------------
    |
    | Legacy configuration keys for backward compatibility with existing
    | implementations that use services.telegram_bot config structure.
    |
    */

    'parcels_chat_id' => env('TELEGRAM_BOT_PARCELS_CHAT_ID'),
    'errors_chat_id' => env('TELEGRAM_BOT_ERRORS_CHAT_ID'),
    'errors_bot_id' => env('TELEGRAM_BOT_ERRORS_BOT_ID'),
    'orders_chat_id' => env('TELEGRAM_BOT_ORDERS_CHAT_ID'),
    'reports_chat_id' => env('TELEGRAM_BOT_REPORTS_CHAT_ID'),
    'system_chat_id' => env('TELEGRAM_BOT_SYSTEM_CHAT_ID'),
];
