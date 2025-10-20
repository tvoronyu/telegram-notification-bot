# Laravel & Lumen Telegram Notifier

[![Latest Version on Packagist](https://img.shields.io/packagist/v/voronyuk/laravel-telegram-notifier.svg?style=flat-square)](https://packagist.org/packages/voronyuk/laravel-telegram-notifier)
[![Total Downloads](https://img.shields.io/packagist/dt/voronyuk/laravel-telegram-notifier.svg?style=flat-square)](https://packagist.org/packages/voronyuk/laravel-telegram-notifier)
[![License](https://img.shields.io/packagist/l/voronyuk/laravel-telegram-notifier.svg?style=flat-square)](https://packagist.org/packages/voronyuk/laravel-telegram-notifier)

Flexible Telegram notification system for Laravel and Lumen with support for different message types, queues, and specialized notification classes.

## Features

- Fluent API for sending Telegram notifications
- Specialized notifier classes (Parcels, Errors, Reports, System)
- Laravel Facade support
- Queue support for async notifications
- HTML/Markdown formatting
- Topic support for supergroups
- Helper functions for quick access
- Auto-discovery for Laravel 8+
- Full Lumen support (8+)

## Requirements

- PHP 7.3 or higher (PHP 8+ recommended)
- Laravel 8.x, 9.x, 10.x, or 11.x
- Lumen 8.x, 9.x, 10.x, or 11.x

## Installation

### For Laravel

Install the package via Composer:

```bash
composer require voronyuk/laravel-telegram-notifier
```

#### Publish Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=telegram-notifier-config
```

This will create a `config/telegram-notifier.php` file.

The package will be auto-discovered in Laravel 8+.

### For Lumen

Installation in Lumen requires additional manual setup. See the [Lumen Installation Guide](LUMEN_INSTALLATION.md) for detailed instructions.

Quick setup for Lumen:

1. Install via Composer:
```bash
composer require voronyuk/laravel-telegram-notifier
```

2. Register Service Provider in `bootstrap/app.php`:
```php
$app->register(Voronyuk\TelegramNotifier\TelegramNotifierServiceProvider::class);
```

3. Copy config file manually:
```bash
mkdir -p config
cp vendor/voronyuk/laravel-telegram-notifier/config/telegram-notifier.php config/
```

4. Load config in `bootstrap/app.php`:
```php
$app->configure('telegram-notifier');
```

See [LUMEN_INSTALLATION.md](LUMEN_INSTALLATION.md) for complete guide.

### Environment Configuration

Add the following variables to your `.env` file:

```env
# Required
TELEGRAM_BOT_API_KEY=your-api-key-here
TELEGRAM_BOT_DEFAULT_BOT_ID=1
TELEGRAM_BOT_DEFAULT_CHAT_ID=-1001234567890

# Optional - Specialized chats
TELEGRAM_BOT_PARCELS_CHAT_ID=-1001234567890
TELEGRAM_BOT_ERRORS_CHAT_ID=-1001234567891
TELEGRAM_BOT_REPORTS_CHAT_ID=-1001234567892
TELEGRAM_BOT_SYSTEM_CHAT_ID=-1001234567893
```

## Usage

### Basic Usage

```php
use Voronyuk\TelegramNotifier\Services\TelegramNotifier;

$notifier = new TelegramNotifier();
$notifier->send('Hello from Laravel!');
```

### Using Facade

```php
use Voronyuk\TelegramNotifier\Facades\Telegram;

// Simple message
Telegram::send('Hello!');

// Specialized notifications
Telegram::parcels()->scanned($parcel);
Telegram::errors()->exception($exception);
Telegram::reports()->daily($stats);
Telegram::system()->startup();
```

### Fluent API

```php
use Voronyuk\TelegramNotifier\Services\TelegramNotifier;

(new TelegramNotifier())
    ->chatId('-1001234567890')
    ->botId(1)
    ->parseMode('HTML')
    ->threadId(123)
    ->withoutQueue()
    ->send('<b>Important message!</b>');
```

### Helper Functions

```php
// Quick send
telegram_send('Message');

// Error notifications
telegram_error($exception);
telegram_critical('Critical error!');
telegram_warning('Warning message');

// Info and success
telegram_info('Info message');
telegram_success('Operation completed!');

// Specialized helpers
telegram_parcel_scanned($parcel);
telegram_daily_report($stats);
```

### Specialized Notifiers

#### Parcel Notifier

```php
use Voronyuk\TelegramNotifier\Services\Notifications\ParcelNotifier;

$notifier = new ParcelNotifier();

// Scanned parcel
$notifier->scanned($parcel);

// Scan error
$notifier->scanError($parcel, 'Barcode not found');

// Weight divergence
$notifier->weightDivergence($parcel, 10.5, 12.3);
```

#### Error Notifier

```php
use Voronyuk\TelegramNotifier\Services\Notifications\ErrorNotifier;

$notifier = new ErrorNotifier();

// Exception notification
try {
    // code
} catch (\Exception $e) {
    $notifier->exception($e, 'Additional context');
}

// Critical error
$notifier->critical('Database connection lost!');

// Warning
$notifier->warning('High memory usage detected');
```

#### Report Notifier

```php
use Voronyuk\TelegramNotifier\Services\Notifications\ReportNotifier;

$notifier = new ReportNotifier();

// Daily report
$notifier->daily([
    'parcels_processed' => 150,
    'errors' => 2,
    'revenue' => 15000,
]);

// Weekly report
$notifier->weekly($stats);

// Custom report
$notifier->custom('Monthly Stats', $data);
```

#### System Notifier

```php
use Voronyuk\TelegramNotifier\Services\Notifications\SystemNotifier;

$notifier = new SystemNotifier();

// System startup
$notifier->startup();

// Info message
$notifier->info('Cache cleared successfully');

// Success message
$notifier->success('Deployment completed');
```

### Creating Custom Notifier

```php
<?php

namespace App\Services\Notifications;

use Voronyuk\TelegramNotifier\Services\TelegramNotifier;

class OrderNotifier extends TelegramNotifier
{
    protected function getDefaultChatId(): string
    {
        return config('telegram-notifier.chats.orders', parent::getDefaultChatId());
    }

    public function newOrder(object $order): void
    {
        $message = "🛒 <b>New Order!</b>\n\n";
        $message .= "Order #: <code>{$order->id}</code>\n";
        $message .= "Total: <b>\${$order->total}</b>\n";
        $message .= "Customer: {$order->customer_name}";

        $this->send($message);
    }

    public function orderShipped(object $order): void
    {
        $message = "📦 <b>Order Shipped</b>\n\n";
        $message .= "Order #: <code>{$order->id}</code>\n";
        $message .= "Tracking: <code>{$order->tracking_number}</code>";

        $this->send($message);
    }
}
```

### Queue Support

By default, all notifications are queued. To send synchronously:

```php
(new TelegramNotifier())
    ->withoutQueue()
    ->send('Urgent message');
```

Or use the Facade:

```php
Telegram::make()->withoutQueue()->send('Urgent message');
```

## Configuration

The package configuration is published to `config/telegram-notifier.php`:

```php
return [
    'api_key' => env('TELEGRAM_BOT_API_KEY'),
    'notify_url' => env('TELEGRAM_BOT_NOTIFY_URL', 'https://telegram-bot.voronyuk.com/api/v1/notify'),

    'default_bot_id' => env('TELEGRAM_BOT_DEFAULT_BOT_ID', 1),
    'default_chat_id' => env('TELEGRAM_BOT_DEFAULT_CHAT_ID'),

    'chats' => [
        'parcels' => env('TELEGRAM_BOT_PARCELS_CHAT_ID'),
        'errors' => env('TELEGRAM_BOT_ERRORS_CHAT_ID'),
        'reports' => env('TELEGRAM_BOT_REPORTS_CHAT_ID'),
        'system' => env('TELEGRAM_BOT_SYSTEM_CHAT_ID'),
    ],
];
```

## Testing

You can test the package by creating a test route:

```php
// routes/web.php
Route::get('/test-telegram', function () {
    \Voronyuk\TelegramNotifier\Facades\Telegram::send('Test message!');
    return 'Message sent!';
});
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security-related issues, please email taras@voronyuk.com instead of using the issue tracker.

## Credits

- [Taras Voronyuk](https://github.com/voronyuk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Support

If you have any questions or issues, please create an issue in the GitHub repository.