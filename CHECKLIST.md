# ✅ Чекліст підготовки Composer пакету

## Перед публікацією

### 1. Перевірка файлів

- [ ] `src/` директорія створена і містить всі файли
- [ ] `config/telegram-notifier.php` існує
- [ ] `composer.json` налаштований правильно
- [ ] `LICENSE` файл створений
- [ ] `README.md` оновлений (переіменований з README_PACKAGE.md)
- [ ] `.gitignore` налаштований

### 2. Очищення проекту

- [ ] Видалено папку `app/`
- [ ] Видалено `install.sh`
- [ ] Видалено старі backup файли (*.bak, *.backup)
- [ ] Старий README збережений як README_OLD.md (опціонально)

### 3. Перевірка composer.json

```bash
# Виконайте цю команду для перевірки
composer validate
```

- [ ] Package name коректний (`voronyuk/laravel-telegram-notifier` або ваш)
- [ ] Authors заповнені
- [ ] Email коректний
- [ ] Версія PHP вказана (`^8.1|^8.2|^8.3`)
- [ ] Laravel версії вказані (`^10.0|^11.0`)
- [ ] Autoload налаштований для `Voronyuk\TelegramNotifier`

### 4. Перевірка namespace

Всі класи мають використовувати `Voronyuk\TelegramNotifier\...`:

- [ ] `src/TelegramNotifierServiceProvider.php`
- [ ] `src/Facades/Telegram.php`
- [ ] `src/Services/TelegramNotifier.php`
- [ ] `src/Services/TelegramNotifierFactory.php`
- [ ] `src/Services/Notifications/*.php`
- [ ] `src/Notifications/*.php`
- [ ] `src/helpers.php`

### 5. Документація

- [ ] README.md описує встановлення через Composer
- [ ] README.md містить приклади використання
- [ ] CHANGELOG.md оновлений
- [ ] PUBLISHING.md містить інструкції
- [ ] Перевірте що всі посилання в документації коректні

## Git та GitLab

### 1. Ініціалізація репозиторію

```bash
git init
git add .
git commit -m "Initial commit: Laravel Telegram Notifier package"
```

- [ ] Git репозиторій ініціалізовано
- [ ] Зроблено перший коміт

### 2. Створення репозиторію на GitLab

1. Зайдіть на https://gitlab.com
2. New project → Create blank project
3. Заповніть дані:
   - Project name: `laravel-telegram-notifier`
   - Visibility: **Public** (для Packagist обов'язково!)

- [ ] Проект створено на GitLab
- [ ] Visibility встановлено як Public

### 3. Відправка коду

```bash
git remote add origin https://gitlab.com/YOUR_USERNAME/laravel-telegram-notifier.git
git branch -M main
git push -u origin main
```

- [ ] Remote репозиторій додано
- [ ] Код відправлено на GitLab

### 4. Створення тегу

```bash
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

- [ ] Тег v1.0.0 створено
- [ ] Тег відправлено на GitLab

## Packagist (якщо публічний пакет)

### 1. Реєстрація пакету

1. Зайдіть на https://packagist.org
2. Зареєструйтеся або увійдіть
3. Submit → вставте URL GitLab репозиторію

- [ ] Акаунт створено на Packagist
- [ ] Пакет зареєстровано

### 2. Налаштування Auto-Update

На сторінці пакету → Settings → скопіюйте webhook URL

На GitLab: Settings → Webhooks → додайте webhook від Packagist

- [ ] Webhook налаштовано
- [ ] Auto-update працює

## Тестування

### 1. Локальне тестування

Створіть тестовий Laravel проект:

```bash
composer create-project laravel/laravel test-app
cd test-app
```

Додайте ваш пакет через VCS:

```json
// composer.json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://gitlab.com/YOUR_USERNAME/laravel-telegram-notifier.git"
        }
    ],
    "require": {
        "voronyuk/laravel-telegram-notifier": "dev-main"
    }
}
```

```bash
composer update
```

- [ ] Пакет встановлюється без помилок
- [ ] Config публікується: `php artisan vendor:publish --tag=telegram-notifier-config`
- [ ] Facade працює: `Telegram::send('test')`

### 2. Тестування з Packagist

```bash
composer require voronyuk/laravel-telegram-notifier
```

- [ ] Пакет встановлюється з Packagist
- [ ] Auto-discovery працює (Service Provider автоматично зареєстровано)

## Після публікації

### 1. Перевірка

- [ ] Пакет доступний на https://packagist.org/packages/YOUR_USERNAME/laravel-telegram-notifier
- [ ] Badges в README відображаються коректно
- [ ] Документація читабельна на GitLab

### 2. Підтримка

- [ ] Налаштовано оповіщення про Issues
- [ ] Готовність відповідати на питання
- [ ] План оновлень (якщо є)

## Корисні команди

```bash
# Перевірка composer.json
composer validate

# Локальне тестування
composer install
composer dump-autoload

# Git команди
git status
git log --oneline
git tag -l

# Створення нового релізу
git tag -a v1.0.1 -m "Bug fixes"
git push origin v1.0.1
```

## Швидкий запуск

Якщо всі пункти вище виконані, можете запустити:

```bash
./prepare-for-publish.sh
```

Цей скрипт автоматично:
- Створить backup
- Видалить непотрібні файли
- Переіменує README
- Перевірить структуру

## Troubleshooting

### Помилка: "Package name is invalid"
Перевірте `composer.json` → `name` має бути `vendor/package`

### Помилка: "Could not find a matching version"
Перевірте що тег існує: `git tag -l`

### Packagist не оновлюється
Перевірте webhook на GitLab та Update на Packagist

### Auto-discovery не працює
Перевірте `composer.json` → `extra.laravel`

---

## Фінальна перевірка

✅ Всі чекбокси виконані?
✅ Код працює локально?
✅ Документація зрозуміла?

**Готові до публікації! 🚀**

Детальні інструкції в `PUBLISHING.md`