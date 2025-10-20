# Інструкція з міграції конфігурації

## Проблема
Раніше пакет використовував `config('services.telegram_bot.*')`, але конфігураційний файл публікувався як `telegram-notifier.php`, через що змінні з `.env` не підтягувалися.

## Виправлення
Тепер пакет використовує правильний ключ конфігурації `telegram-notifier.*` та підтримує зворотну сумісність.

## Міграція для існуючих користувачів

### Крок 1: Опублікувати новий конфігураційний файл
```bash
php artisan vendor:publish --tag=telegram-notifier-config --force
```

### Крок 2: Змінні середовища залишаються незмінними
Ваш `.env` файл залишається таким же:
```env
TELEGRAM_BOT_API_KEY=your-api-key-here
TELEGRAM_BOT_NOTIFY_URL=https://telegram-bot.voronyuk.com/api/v1/notify
TELEGRAM_BOT_DEFAULT_BOT_ID=1
TELEGRAM_BOT_DEFAULT_CHAT_ID=-1001234567890

# Спеціалізовані чати
TELEGRAM_BOT_PARCELS_CHAT_ID=-1001234567890
TELEGRAM_BOT_ERRORS_CHAT_ID=-1001234567891
TELEGRAM_BOT_ORDERS_CHAT_ID=-1001234567892
TELEGRAM_BOT_REPORTS_CHAT_ID=-1001234567893
TELEGRAM_BOT_STATUS_UPDATES_CHAT_ID=-1001234567894
TELEGRAM_BOT_SYSTEM_CHAT_ID=-1001234567895
```

### Крок 3: Видалити старі конфігурації (опціонально)
Якщо у вас були старі конфігурації в `config/services.php`, їх можна видалити:
```php
// Видаліть цей блок з config/services.php
'telegram_bot' => [
    'api_key' => env('TELEGRAM_BOT_API_KEY'),
    // ...
],
```

## Що змінилося в коді

### До:
```php
config('services.telegram_bot.api_key')
config('services.telegram_bot.default_chat_id')
config('services.telegram_bot.parcels_chat_id')
```

### Після:
```php
config('telegram-notifier.api_key')
config('telegram-notifier.default_chat_id')
config('telegram-notifier.chats.parcels')
// або зворотно сумісний варіант:
config('telegram-notifier.parcels_chat_id')
```

## Зворотна сумісність

Пакет підтримує зворотну сумісність на рівні коду:
- Спеціалізовані нотифаєри спочатку шукають конфігурацію в `telegram-notifier.chats.*`
- Якщо не знайдено, шукають в `telegram-notifier.*_chat_id` (старий формат)
- Якщо і це не знайдено, використовують дефолтні значення

## Переваги нової структури

1. **Правильна робота з .env**: Всі змінні з `.env` тепер коректно підтягуються
2. **Краща організація**: Чати та боти організовані в окремі масиви
3. **Зворотна сумісність**: Старий код продовжує працювати
4. **Гнучкість**: Можна використовувати як новий, так і старий формат

## Приклад використання

```php
use Voronyuk\TelegramNotifier\Services\Notifications\ParcelNotifier;

$notifier = new ParcelNotifier();
$notifier->send('Тестове повідомлення');

// ParcelNotifier автоматично:
// 1. Спробує config('telegram-notifier.chats.parcels')
// 2. Якщо не знайдено, спробує config('telegram-notifier.parcels_chat_id')
// 3. Якщо не знайдено, використає config('telegram-notifier.default_chat_id')
```