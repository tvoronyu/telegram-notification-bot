# 📱 Laravel Telegram Notifier

Гнучка система нотифікацій для Laravel з підтримкою різних типів повідомлень, черги та спеціалізованих класів.

## 📦 Що входить у пакет

- ✅ Базовий клас `TelegramNotifier` з Fluent API
- ✅ Спеціалізовані класи для різних типів нотифікацій
- ✅ Facade для зручного доступу
- ✅ Підтримка черги Laravel
- ✅ HTML/Markdown форматування
- ✅ Підтримка топіків у супергрупах

## 🚀 Швидка установка (5 хвилин)

### Варіант 1: Автоматична установка (рекомендовано)

```bash
# Склонуйте репозиторій у ваш проект
cd /path/to/your/laravel-project
git clone https://github.com/your-username/telegram-notifier.git tmp-telegram
bash tmp-telegram/install.sh
rm -rf tmp-telegram
```

### Варіант 2: Ручна установка

#### Крок 1: Скопіюйте файли

```bash
# Склонуйте репозиторій
git clone https://github.com/your-username/telegram-notifier.git

# Скопіюйте файли у ваш проект
cp -r telegram-notifier/app/* /path/to/your/laravel-project/app/
```

#### Крок 2: Додайте конфігурацію в `config/services.php`

```php
'telegram_bot' => [
    'api_key' => env('TELEGRAM_BOT_API_KEY'),
    'notify_url' => env('TELEGRAM_BOT_NOTIFY_URL', 'https://telegram-bot.voronyuk.com/api/v1/notify'),

    // Дефолтні значення
    'default_bot_id' => env('TELEGRAM_BOT_DEFAULT_BOT_ID', 1),
    'default_chat_id' => env('TELEGRAM_BOT_DEFAULT_CHAT_ID', '-1001234567890'),

    // Чати для різних типів нотифікацій
    'parcels_chat_id' => env('TELEGRAM_BOT_PARCELS_CHAT_ID'),
    'errors_chat_id' => env('TELEGRAM_BOT_ERRORS_CHAT_ID'),
    'errors_bot_id' => env('TELEGRAM_BOT_ERRORS_BOT_ID'),
    'orders_chat_id' => env('TELEGRAM_BOT_ORDERS_CHAT_ID'),
    'reports_chat_id' => env('TELEGRAM_BOT_REPORTS_CHAT_ID'),
    'system_chat_id' => env('TELEGRAM_BOT_SYSTEM_CHAT_ID'),
],
```

#### Крок 3: Зареєструйте Service Provider в `config/app.php`

```php
'providers' => ServiceProvider::defaultProviders()->merge([
    // ...
    App\Providers\TelegramServiceProvider::class,
])->toArray(),

'aliases' => Facade::defaultAliases()->merge([
    // ...
    'Telegram' => App\Facades\Telegram::class,
])->toArray(),
```

#### Крок 4: Додайте змінні в `.env`

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

#### Крок 5: Очистіть кеш

```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

## 📖 Використання

### Базове використання

```php
use App\Services\TelegramNotifier;

// Просто відправити повідомлення
(new TelegramNotifier())->send('Привіт!');
```

### Через Facade

```php
use App\Facades\Telegram;

// Базова відправка
Telegram::send('Привіт!');

// Спеціалізовані нотифікації
Telegram::parcels()->scanned($parcel);
Telegram::errors()->exception($exception);
Telegram::reports()->daily($stats);
Telegram::system()->startup();
```

### Спеціалізовані класи

#### Посилки

```php
use App\Services\Notifications\ParcelNotifier;

$notifier = new ParcelNotifier();
$notifier->scanned($parcel);
$notifier->weightDivergence($parcel, 10.5, 12.3);
```

#### Помилки

```php
use App\Services\Notifications\ErrorNotifier;

try {
    // код
} catch (\Exception $e) {
    (new ErrorNotifier())->exception($e);
}

(new ErrorNotifier())->critical('База даних недоступна!');
```

#### Звіти

```php
use App\Services\Notifications\ReportNotifier;

(new ReportNotifier())->daily([
    'parcels_scanned' => 150,
    'errors' => 2,
]);
```

#### Системні події

```php
use App\Services\Notifications\SystemNotifier;

(new SystemNotifier())->startup();
(new SystemNotifier())->info('Черга очищена');
```

### Fluent API

```php
(new TelegramNotifier())
    ->chatId('-9999999')
    ->parseMode('HTML')
    ->withoutQueue()
    ->send('<b>Термінове!</b>');
```

## 🎨 Створення власного класу

```php
<?php

namespace App\Services\Notifications;

use App\Services\TelegramNotifier;

class OrderNotifier extends TelegramNotifier
{
    protected function getDefaultChatId(): string
    {
        return config('services.telegram_bot.orders_chat_id', parent::getDefaultChatId());
    }

    public function newOrder(object $order): void
    {
        $message = "🛒 <b>Новий заказ!</b>\n\n";
        $message .= "Номер: <code>#{$order->id}</code>\n";
        $message .= "Сума: <b>{$order->total} грн</b>";

        $this->send($message);
    }
}
```

## 📚 Документація

Детальна документація доступна в папці `docs/`:

- [QUICKSTART.md](docs/QUICKSTART.md) - Швидкий старт
- [README.md](docs/README.md) - Повна документація
- [FACADE_USAGE.md](docs/FACADE_USAGE.md) - Використання Facade
- [HELPERS.md](docs/HELPERS.md) - Helper functions
- [INSTALLATION.md](docs/INSTALLATION.md) - Детальна інструкція по установці
- [SUMMARY.md](docs/SUMMARY.md) - Огляд можливостей

## 🔧 Тестування

```php
// Створіть тестовий route в routes/web.php
Route::get('/test-telegram', function () {
    use App\Facades\Telegram;

    Telegram::send('🎉 Telegram Notifier працює!');

    return 'Повідомлення відправлено!';
});
```

Відкрийте в браузері: `http://your-domain/test-telegram`

## ❓ Troubleshooting

### Помилка: "API key not configured"

**Рішення:** Переконайтеся що в `.env` є `TELEGRAM_BOT_API_KEY` та виконайте:
```bash
php artisan config:clear
```

### Помилка: "Class not found"

**Рішення:**
```bash
composer dump-autoload
```

### Нотифікації не відправляються

**Рішення:** Перевірте:
1. Чи запущений queue worker (`php artisan queue:work`)
2. Чи правильний API ключ
3. Перегляньте логи: `storage/logs/laravel.log`

## 🔄 Оновлення

```bash
cd /path/to/your/laravel-project
git clone https://github.com/your-username/telegram-notifier.git tmp-telegram
bash tmp-telegram/install.sh
rm -rf tmp-telegram
```

## 📋 Вимоги

- Laravel 10.x+
- PHP 8.1+

## 📄 Ліцензія

MIT License

## 👨‍💻 Автор

Taras Voronyuk

---

**Підтримка:** Якщо виникли питання, створіть issue в репозиторії.