# 🚀 Швидкий старт - Composer пакет

## Що було зроблено

Ваш проект перетворено з Laravel додатку на повноцінний Composer пакет.

### Основні зміни:

1. **Namespace змінено** з `App\...` на `Voronyuk\TelegramNotifier\...`
2. **Створено `src/`** директорію замість `app/`
3. **Додано `composer.json`** з правильною конфігурацією
4. **Створено Service Provider** з автореєстрацією для Laravel
5. **Додано конфігурацію** в `config/telegram-notifier.php`

## Швидка публікація (3 хвилини)

### 1. Підготовка

```bash
# Запустіть скрипт підготовки
./prepare-for-publish.sh
```

Або вручну:
```bash
rm -rf app/
rm install.sh
mv README.md README_OLD.md
mv README_PACKAGE.md README.md
```

### 2. Git

```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://gitlab.com/YOUR_USERNAME/laravel-telegram-notifier.git
git push -u origin main
git tag -a v1.0.0 -m "v1.0.0"
git push origin v1.0.0
```

### 3. Packagist

1. Зайдіть на https://packagist.org
2. Submit → вставте URL репозиторію
3. Готово!

## Встановлення пакету

Після публікації на Packagist:

```bash
composer require voronyuk/laravel-telegram-notifier
```

## Використання

```php
use Voronyuk\TelegramNotifier\Facades\Telegram;

Telegram::send('Hello from package!');
```

## Файли для редагування

### composer.json
Змініть `name`, `authors`, `email`:

```json
{
    "name": "your-username/laravel-telegram-notifier",
    "authors": [
        {
            "name": "Your Name",
            "email": "your@email.com"
        }
    ]
}
```

### README.md
Оновіть приклади та посилання на ваш репозиторій.

## Структура пакету

```
✅ src/                              # Вихідні коди
   ├── Facades/Telegram.php
   ├── Services/TelegramNotifier.php
   ├── Notifications/...
   ├── helpers.php
   └── TelegramNotifierServiceProvider.php

✅ config/telegram-notifier.php     # Конфігурація

✅ composer.json                    # Composer package

✅ README.md                        # Документація

✅ LICENSE                          # MIT License

❌ app/                             # Видалити
❌ install.sh                       # Видалити
```

## Приватний репозиторій

Якщо не хочете публікувати на Packagist:

```json
// У вашому Laravel проекті composer.json:
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://gitlab.com/your-username/laravel-telegram-notifier.git"
        }
    ],
    "require": {
        "voronyuk/laravel-telegram-notifier": "^1.0"
    }
}
```

## Оновлення версії

```bash
# Bug fix (1.0.0 → 1.0.1)
git tag -a v1.0.1 -m "Bug fixes"
git push origin v1.0.1

# New feature (1.0.0 → 1.1.0)
git tag -a v1.1.0 -m "New features"
git push origin v1.1.0

# Breaking changes (1.0.0 → 2.0.0)
git tag -a v2.0.0 -m "Major update"
git push origin v2.0.0
```

## Корисні команди

```bash
# Перевірка синтаксису
composer validate

# Перевірка autoload
composer dump-autoload

# Локальне тестування
composer install
php vendor/bin/phpunit
```

## Документація

- `PUBLISHING.md` - Повна інструкція по публікації
- `PACKAGE_READY.md` - Детальний огляд змін
- `README.md` - Документація для користувачів

## Питання?

Прочитайте `PUBLISHING.md` для детальної інформації.

---

**Успіхів! 🎉**