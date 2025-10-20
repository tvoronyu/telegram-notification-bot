# Встановлення в Lumen

Цей пакет повністю сумісний з Lumen 8+. Однак, оскільки Lumen не підтримує auto-discovery пакетів, потрібна ручна реєстрація.

## Встановлення

### 1. Встановіть пакет через Composer

```bash
composer require voronyuk/laravel-telegram-notifier
```

### 2. Зареєструйте Service Provider

У файлі `bootstrap/app.php` додайте:

```php
// Розкоментуйте цей рядок, якщо він закоментований
$app->withFacades();

// Зареєструйте Service Provider
$app->register(Voronyuk\TelegramNotifier\TelegramNotifierServiceProvider::class);
```

### 3. Зареєструйте Facade (опціонально)

Якщо хочете використовувати Facade, додайте в `bootstrap/app.php`:

```php
// Після $app->withFacades();

if (!class_exists('Telegram')) {
    class_alias(Voronyuk\TelegramNotifier\Facades\Telegram::class, 'Telegram');
}
```

### 4. Скопіюйте конфігурацію

Оскільки Lumen не має команди `vendor:publish`, скопіюйте конфігурацію вручну:

```bash
# Створіть папку config (якщо не існує)
mkdir -p config

# Скопіюйте файл конфігурації
cp vendor/voronyuk/laravel-telegram-notifier/config/telegram-notifier.php config/telegram-notifier.php
```

### 5. Налаштуйте конфігурацію

У файлі `bootstrap/app.php` додайте:

```php
// Завантажте конфігурацію
$app->configure('telegram-notifier');

// Або старий спосіб через config/services.php
$app->configure('services');
```

### 6. Додайте змінні в `.env`

```env
# Обов'язкові налаштування
TELEGRAM_BOT_API_KEY=your-api-key-here
TELEGRAM_BOT_DEFAULT_BOT_ID=1
TELEGRAM_BOT_DEFAULT_CHAT_ID=-1001234567890

# Опціональні (окремі чати)
TELEGRAM_BOT_PARCELS_CHAT_ID=-1001234567890
TELEGRAM_BOT_ERRORS_CHAT_ID=-1001234567891
TELEGRAM_BOT_REPORTS_CHAT_ID=-1001234567892
TELEGRAM_BOT_SYSTEM_CHAT_ID=-1001234567893
```

## Використання в Lumen

### Базове використання

```php
use Voronyuk\TelegramNotifier\Services\TelegramNotifier;

// Просте повідомлення
$notifier = new TelegramNotifier();
$notifier->send('Привіт з Lumen!');
```

### Через Facade (якщо зареєстровано)

```php
use Telegram;

Telegram::send('Привіт!');
Telegram::errors()->exception($exception);
Telegram::parcels()->scanned($parcel);
```

### Helper functions

Helper functions працюють без додаткових налаштувань:

```php
telegram_send('Повідомлення');
telegram_error($exception);
telegram_info('Інформація');
```

### Спеціалізовані Notifiers

```php
use Voronyuk\TelegramNotifier\Services\Notifications\ErrorNotifier;
use Voronyuk\TelegramNotifier\Services\Notifications\ParcelNotifier;
use Voronyuk\TelegramNotifier\Services\Notifications\ReportNotifier;

// Помилки
$errorNotifier = new ErrorNotifier();
$errorNotifier->exception($exception);
$errorNotifier->critical('Критична помилка!');

// Посилки
$parcelNotifier = new ParcelNotifier();
$parcelNotifier->scanned($parcel);

// Звіти
$reportNotifier = new ReportNotifier();
$reportNotifier->daily(['parcels' => 100, 'errors' => 2]);
```

## Повний приклад bootstrap/app.php

```php
<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
*/

$app->configure('telegram-notifier');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
*/

// Розкоментуйте для використання Facades
$app->withFacades();

// Розкоментуйте для використання Eloquent
// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
*/

// Реєструємо Telegram Notifier
$app->register(Voronyuk\TelegramNotifier\TelegramNotifierServiceProvider::class);

// Реєструємо Facade alias (опціонально)
if (!class_exists('Telegram')) {
    class_alias(Voronyuk\TelegramNotifier\Facades\Telegram::class, 'Telegram');
}

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
```

## Тестування

Створіть тестовий маршрут в `routes/web.php`:

```php
$router->get('/test-telegram', function () {
    // Використання через клас
    $notifier = new \Voronyuk\TelegramNotifier\Services\TelegramNotifier();
    $notifier->send('🎉 Telegram працює в Lumen!');

    // Або через helper
    telegram_send('✅ Helper також працює!');

    return 'Повідомлення відправлено!';
});
```

Відкрийте в браузері: `http://your-lumen-app/test-telegram`

## Використання черги

Для використання черги в Lumen:

1. Налаштуйте з'єднання черги в `.env`:
```env
QUEUE_CONNECTION=database
# або
QUEUE_CONNECTION=redis
```

2. Запустіть worker:
```bash
php artisan queue:work
```

3. Використовуйте нотифікації як зазвичай (за замовчуванням вони йдуть в чергу):
```php
telegram_send('Це повідомлення буде в черзі');
```

Для синхронної відправки:
```php
(new TelegramNotifier())->withoutQueue()->send('Синхронне повідомлення');
```

## Відмінності від Laravel

### ❌ Не працює в Lumen:
- `php artisan vendor:publish` - потрібно копіювати конфігурацію вручну
- Auto-discovery пакетів - потрібна ручна реєстрація Service Provider

### ✅ Працює однаково:
- Всі класи нотифікацій
- Helper functions
- Facade (після реєстрації)
- Черга
- Fluent API

## Troubleshooting

### Помилка: "Class 'Telegram' not found"

**Рішення:** Зареєструйте Facade alias в `bootstrap/app.php`:
```php
class_alias(Voronyuk\TelegramNotifier\Facades\Telegram::class, 'Telegram');
```

### Помилка: "Configuration file [telegram-notifier] not found"

**Рішення:** Додайте в `bootstrap/app.php`:
```php
$app->configure('telegram-notifier');
```

### Помилка: "API key not configured"

**Рішення:**
1. Перевірте що `.env` містить `TELEGRAM_BOT_API_KEY`
2. Переконайтеся що конфігурація завантажена в `bootstrap/app.php`

## Підтримувані версії

- **Lumen:** 8.x, 9.x, 10.x, 11.x
- **PHP:** 7.3+, 8.0+, 8.1+, 8.2+, 8.3+, 8.4+

## Додаткові ресурси

- [Lumen Documentation](https://lumen.laravel.com/docs)
- [Package README](README.md)
- [Laravel Installation](README.md#installation)

---

Якщо у вас виникли питання або проблеми, створіть issue на GitLab.