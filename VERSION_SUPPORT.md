# Підтримувані версії

## Framework Support

### Laravel
- ✅ Laravel 8.x (PHP 7.3+)
- ✅ Laravel 9.x (PHP 8.0+)
- ✅ Laravel 10.x (PHP 8.1+)
- ✅ Laravel 11.x (PHP 8.2+)

### Lumen
- ✅ Lumen 8.x (PHP 7.3+)
- ✅ Lumen 9.x (PHP 8.0+)
- ✅ Lumen 10.x (PHP 8.1+)
- ✅ Lumen 11.x (PHP 8.2+)

## PHP Version Support

- PHP 7.3 ✅
- PHP 7.4 ✅
- PHP 8.0 ✅
- PHP 8.1 ✅
- PHP 8.2 ✅
- PHP 8.3 ✅
- PHP 8.4 ✅

**Рекомендовано:** PHP 8.1 або вище

## Compatibility Matrix

| Framework    | PHP Version | Package Version | Auto-discovery |
|-------------|-------------|-----------------|----------------|
| Laravel 8.x | 7.3 - 8.4   | ^1.0            | ✅             |
| Laravel 9.x | 8.0 - 8.4   | ^1.0            | ✅             |
| Laravel 10.x| 8.1 - 8.4   | ^1.0            | ✅             |
| Laravel 11.x| 8.2 - 8.4   | ^1.0            | ✅             |
| Lumen 8.x   | 7.3 - 8.4   | ^1.0            | ❌ (manual)    |
| Lumen 9.x   | 8.0 - 8.4   | ^1.0            | ❌ (manual)    |
| Lumen 10.x  | 8.1 - 8.4   | ^1.0            | ❌ (manual)    |
| Lumen 11.x  | 8.2 - 8.4   | ^1.0            | ❌ (manual)    |

## Dependencies

### Required
- `php`: ^7.3|^8.0|^8.1|^8.2|^8.3|^8.4
- `illuminate/support`: ^8.0|^9.0|^10.0|^11.0
- `illuminate/notifications`: ^8.0|^9.0|^10.0|^11.0
- `guzzlehttp/guzzle`: ^7.0

### Development
- `orchestra/testbench`: ^6.0|^7.0|^8.0|^9.0
- `phpunit/phpunit`: ^9.0|^10.0

## Features Support by Framework

| Feature | Laravel | Lumen |
|---------|---------|-------|
| Auto-discovery | ✅ | ❌ |
| Facade | ✅ | ✅* |
| Service Provider | ✅ | ✅ |
| Config Publishing | ✅ | ❌** |
| Helper Functions | ✅ | ✅ |
| Queue Support | ✅ | ✅ |
| Notifications | ✅ | ✅ |

\* Потрібна ручна реєстрація
\** Потрібне ручне копіювання

## Installation Notes

### Laravel 8-11
Автоматична реєстрація працює з коробки:
```bash
composer require voronyuk/laravel-telegram-notifier
php artisan vendor:publish --tag=telegram-notifier-config
```

### Lumen 8-11
Потрібна ручна реєстрація:
```bash
composer require voronyuk/laravel-telegram-notifier
```

Додайте в `bootstrap/app.php`:
```php
$app->register(Voronyuk\TelegramNotifier\TelegramNotifierServiceProvider::class);
$app->configure('telegram-notifier');
```

Детальніше: [LUMEN_INSTALLATION.md](LUMEN_INSTALLATION.md)

## Testing

Пакет протестовано на:
- ✅ Laravel 8.83 (PHP 7.3, 7.4, 8.0, 8.1)
- ✅ Laravel 9.52 (PHP 8.0, 8.1, 8.2)
- ✅ Laravel 10.48 (PHP 8.1, 8.2, 8.3)
- ✅ Laravel 11.x (PHP 8.2, 8.3, 8.4)
- ✅ Lumen 8.x, 9.x, 10.x, 11.x

## Deprecation Notice

Підтримка PHP 7.3 та 7.4 може бути припинена у майбутніх версіях (v2.0+).

Рекомендуємо оновитися до PHP 8.1+ для кращої продуктивності та безпеки.

## Migration Guides

### Upgrading from Laravel 7
Laravel 7 офіційно не підтримується, але пакет може працювати при ручній реєстрації.

### Upgrading PHP Version
При оновленні версії PHP:
1. Перевірте composer.json на сумісність
2. Виконайте `composer update`
3. Очистіть кеш: `php artisan cache:clear`

## Known Issues

### PHP 7.3
- Typed properties не підтримуються (використовуємо DocBlocks)
- Деякі нові функції PHP 8 недоступні

### Lumen
- Немає auto-discovery
- `vendor:publish` не працює
- Потрібна ручна конфігурація

Рішення описані в [LUMEN_INSTALLATION.md](LUMEN_INSTALLATION.md)

## Support Timeline

| Version | PHP    | Laravel | Support Until |
|---------|--------|---------|---------------|
| 1.x     | 7.3+   | 8-11    | Active        |
| 2.x     | 8.1+   | 10-12   | Planned       |

## Questions?

Створіть issue на GitLab з тегом `compatibility` або `version-support`.