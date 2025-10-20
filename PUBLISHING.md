# Публікація пакету на GitLab та Packagist

Цей документ описує процес публікації Laravel пакету на GitLab і подальшої реєстрації на Packagist.

## Підготовка проекту

### 1. Перевірка структури

Переконайтеся, що структура проекту правильна:

```
laravel-telegram-notifier/
├── config/
│   └── telegram-notifier.php
├── src/
│   ├── Facades/
│   │   └── Telegram.php
│   ├── Notifications/
│   │   ├── Channels/
│   │   │   └── TelegramBotChannel.php
│   │   └── TelegramBotNotification.php
│   ├── Services/
│   │   ├── Notifications/
│   │   │   ├── Error1CNotifier.php
│   │   │   ├── ErrorNotifier.php
│   │   │   ├── ParcelNotifier.php
│   │   │   ├── ReportNotifier.php
│   │   │   └── SystemNotifier.php
│   │   ├── TelegramNotifier.php
│   │   └── TelegramNotifierFactory.php
│   ├── helpers.php
│   └── TelegramNotifierServiceProvider.php
├── .gitignore
├── CHANGELOG.md
├── composer.json
├── LICENSE
├── README.md (переіменуйте README_PACKAGE.md в README.md)
└── PUBLISHING.md (цей файл)
```

### 2. Підготовка файлів

```bash
# Видаліть зайві файли (якщо є)
rm -rf app/
rm -rf docs/old/
rm install.sh

# Переіменуйте README для пакету
mv README_PACKAGE.md README.md
```

## Публікація на GitLab

### 1. Створення репозиторію на GitLab

1. Зайдіть на https://gitlab.com
2. Натисніть "New project"
3. Виберіть "Create blank project"
4. Заповніть:
   - Project name: `laravel-telegram-notifier`
   - Project URL: `https://gitlab.com/your-username/laravel-telegram-notifier`
   - Visibility: Public (для Packagist потрібен публічний репозиторій)
5. Натисніть "Create project"

### 2. Ініціалізація Git та пуш

```bash
cd /Users/taras/PhpstormProjects/meestchina/TelegramBot

# Ініціалізуйте git (якщо ще не ініціалізовано)
git init

# Додайте всі файли
git add .

# Створіть перший коміт
git commit -m "Initial commit: Laravel Telegram Notifier package"

# Додайте remote репозиторій GitLab
git remote add origin https://gitlab.com/your-username/laravel-telegram-notifier.git

# Створіть основну гілку
git branch -M main

# Відправте код на GitLab
git push -u origin main
```

### 3. Створення тегу (версії)

```bash
# Створіть тег для версії 1.0.0
git tag -a v1.0.0 -m "Release version 1.0.0"

# Відправте тег на GitLab
git push origin v1.0.0
```

## Публікація на Packagist

### 1. Створення акаунту

1. Зайдіть на https://packagist.org
2. Зареєструйтеся або увійдіть через GitHub/GitLab

### 2. Додавання пакету

1. Натисніть "Submit" у верхньому меню
2. Вставте URL вашого GitLab репозиторію:
   ```
   https://gitlab.com/your-username/laravel-telegram-notifier
   ```
3. Натисніть "Check"
4. Якщо все ОК, натисніть "Submit"

### 3. Налаштування Auto-Update

Щоб Packagist автоматично оновлювався при нових комітах:

1. На сторінці вашого пакету на Packagist натисніть "Settings"
2. Скопіюйте "API Token" і URL webhook
3. На GitLab:
   - Зайдіть в Settings → Webhooks
   - Вставте URL webhook від Packagist
   - Виберіть тригери: "Push events", "Tag push events"
   - Натисніть "Add webhook"

## Встановлення пакету

Тепер ваш пакет можна встановити через Composer:

```bash
composer require voronyuk/laravel-telegram-notifier
```

Замініть `voronyuk` на ваш username на Packagist.

## Оновлення пакету

### Виправлення помилок (patch)

```bash
# Внесіть зміни
git add .
git commit -m "Fix: описание исправления"

# Створіть новий тег
git tag -a v1.0.1 -m "Bug fixes"
git push origin main
git push origin v1.0.1
```

### Нові функції (minor)

```bash
git add .
git commit -m "Feature: описание новой функции"

git tag -a v1.1.0 -m "New features"
git push origin main
git push origin v1.1.0
```

### Великі зміни (major)

```bash
git add .
git commit -m "Breaking: описание изменений"

git tag -a v2.0.0 -m "Major update with breaking changes"
git push origin main
git push origin v2.0.0
```

## Використання приватного репозиторію

Якщо ваш проект приватний і ви не хочете публікувати на Packagist:

### Варіант 1: Приватний Packagist (платний)

https://packagist.com

### Варіант 2: GitLab Package Registry

1. В `composer.json` вашого Laravel проекту додайте:

```json
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

2. Для доступу до приватного репозиторію:

```bash
composer config --global gitlab-token.gitlab.com YOUR_GITLAB_TOKEN
```

### Варіант 3: Composer з Git через SSH

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@gitlab.com:your-username/laravel-telegram-notifier.git"
        }
    ],
    "require": {
        "voronyuk/laravel-telegram-notifier": "dev-main"
    }
}
```

## CI/CD (опціонально)

Створіть файл `.gitlab-ci.yml` для автоматичного тестування:

```yaml
image: php:8.2

before_script:
  - apt-get update -yqq
  - apt-get install -yqq git libzip-dev
  - docker-php-ext-install zip
  - curl -sS https://getcomposer.org/installer | php
  - php composer.phar install

test:
  script:
    - vendor/bin/phpunit
  only:
    - main
    - merge_requests
```

## Чекліст перед публікацією

- [ ] composer.json правильно налаштований
- [ ] Всі namespace правильні (Voronyuk\TelegramNotifier)
- [ ] LICENSE файл створено
- [ ] README.md описує встановлення та використання
- [ ] CHANGELOG.md оновлений
- [ ] .gitignore налаштований правильно
- [ ] Код протестовано
- [ ] Створено тег версії (v1.0.0)
- [ ] Код відправлено на GitLab
- [ ] Пакет зареєстрований на Packagist (якщо публічний)

## Підтримка

Після публікації не забувайте:

1. Відповідати на Issues
2. Розглядати Pull Requests
3. Регулярно оновлювати CHANGELOG.md
4. Дотримуватися Semantic Versioning

## Корисні посилання

- GitLab: https://gitlab.com
- Packagist: https://packagist.org
- Semantic Versioning: https://semver.org
- Composer Documentation: https://getcomposer.org/doc/
- Laravel Package Development: https://laravel.com/docs/packages