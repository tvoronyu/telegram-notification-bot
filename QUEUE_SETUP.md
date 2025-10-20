# Налаштування черги для Laravel Telegram Notifier

## Проблема: Повідомлення не відправляються з чергою

Якщо `$notifier->withoutQueue()->send()` працює, а з чергою ні - це означає, що черга в Laravel не налаштована або не запущена.

## ⚠️ ВАЖЛИВО: Налаштування назви черги

**Якщо ваш worker слухає НЕ `default` чергу**, потрібно вказати назву черги в конфігурації!

### Варіант 1: Глобальне налаштування в .env
```env
# Встановіть назву черги, яку слухає ваш worker
TELEGRAM_BOT_QUEUE=notifications  # замість 'default'
```

### Варіант 2: Програмно для конкретного повідомлення
```php
// Використати конкретну чергу
$notifier = new TelegramNotifier();
$notifier->onQueue('notifications')->send('Тест');

// Або через withQueue()
$notifier->withQueue('notifications')->send('Тест');
```

### Перевірка: В яку чергу йдуть повідомлення?
```sql
-- Подивіться в таблиці jobs
SELECT queue, COUNT(*) FROM jobs GROUP BY queue;
```

### Запуск worker для конкретної черги
```bash
# Слухати ТІЛЬКИ чергу 'notifications'
php artisan queue:work --queue=notifications

# Слухати кілька черг (пріоритет: high > default > low)
php artisan queue:work --queue=high,default,low
```

## Що потрібно перевірити в Laravel проекті

### 1. Налаштування черги в .env

Перевірте файл `.env` вашого проекту:

```env
# Для локальної розробки - sync (без черги)
QUEUE_CONNECTION=sync

# Для продакшену - database, redis, або sqs
QUEUE_CONNECTION=database
```

**Опції:**
- `sync` - виконувати завдання синхронно (без черги) - для тестування
- `database` - зберігати завдання в базі даних
- `redis` - використовувати Redis (найшвидший варіант)
- `sqs` - Amazon SQS

### 2. Налаштування database queue (рекомендовано для початку)

#### Крок 1: Створити таблиці для черги
```bash
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```

Це створить дві таблиці:
- `jobs` - для активних завдань
- `failed_jobs` - для провалених завдань

#### Крок 2: Налаштувати .env
```env
QUEUE_CONNECTION=database
```

#### Крок 3: Запустити queue worker

**Для локальної розробки:**
```bash
php artisan queue:work
```

**Для продакшену (з Supervisor):**
Створіть конфіг `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600
```

Перезапустіть Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 3. Налаштування Redis queue (для високого навантаження)

#### Крок 1: Встановити Redis
```bash
# Ubuntu/Debian
sudo apt-get install redis-server

# macOS
brew install redis
```

#### Крок 2: Встановити PHP Redis extension
```bash
sudo apt-get install php-redis
# або
pecl install redis
```

#### Крок 3: Встановити predis через Composer
```bash
composer require predis/predis
```

#### Крок 4: Налаштувати .env
```env
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Крок 5: Запустити queue worker
```bash
php artisan queue:work redis
```

### 4. Діагностика проблем

#### Перевірка 1: Чи додаються завдання в чергу?

**Для database:**
```sql
SELECT * FROM jobs ORDER BY id DESC LIMIT 10;
```

**Для Redis:**
```bash
redis-cli
> LLEN queues:default
```

#### Перевірка 2: Чи є провалені завдання?

```bash
php artisan queue:failed
```

Переглянути деталі:
```sql
SELECT * FROM failed_jobs ORDER BY id DESC LIMIT 10;
```

#### Перевірка 3: Чи запущений worker?

```bash
# Перевірити процеси
ps aux | grep "queue:work"

# Для Supervisor
sudo supervisorctl status
```

#### Перевірка 4: Тестування черги

```bash
# Ручне виконання одного завдання
php artisan queue:work --once

# Виконання з виводом debug інформації
php artisan queue:work --verbose
```

### 5. Швидке тестування в проекті

Створіть тестовий роут або команду:

```php
// routes/web.php або routes/api.php
Route::get('/test-telegram', function () {
    // Тест БЕЗ черги (має працювати одразу)
    $notifier = new \Voronyuk\TelegramNotifier\Services\TelegramNotifier();
    $notifier->withoutQueue()->send('Тест БЕЗ черги - ' . now());

    // Тест З чергою (потребує запущеного worker)
    $notifier = new \Voronyuk\TelegramNotifier\Services\TelegramNotifier();
    $notifier->withQueue()->send('Тест З чергою - ' . now());

    return 'Тести відправлені! Перевірте Telegram та таблицю jobs.';
});
```

### 6. Типові помилки

#### Помилка: "Driver [database] not supported"
**Рішення:** Виконайте міграції для створення таблиць черги:
```bash
php artisan queue:table
php artisan migrate
```

#### Помилка: Завдання додаються в jobs, але не виконуються
**Рішення:** Queue worker не запущений. Запустіть:
```bash
php artisan queue:work
```

#### Помилка: Worker зупиняється після кожного завдання
**Рішення:** Використовуйте Supervisor для автоматичного перезапуску

#### Помилка: Завдання падають в failed_jobs
**Рішення:** Перевірте логи:
```bash
php artisan queue:failed
php artisan queue:retry {id}
```

Перегляньте `storage/logs/laravel.log` для деталей помилки.

### 7. Рекомендації для різних середовищ

#### Локальна розробка
```env
QUEUE_CONNECTION=sync
```
Або запустіть worker в окремому терміналі:
```bash
php artisan queue:work --tries=3
```

#### Staging/Testing
```env
QUEUE_CONNECTION=database
```
З Supervisor для автоматичного запуску.

#### Production
```env
QUEUE_CONNECTION=redis
```
З Supervisor + Horizon (рекомендовано):
```bash
composer require laravel/horizon
php artisan horizon:install
php artisan migrate
```

### 8. Моніторинг черги

#### Laravel Horizon (для Redis)
```bash
composer require laravel/horizon
php artisan horizon:install
```

Відкрийте `/horizon` в браузері для моніторингу.

#### Без Horizon
```bash
# Подивитись скільки завдань в черзі
php artisan queue:work --once --verbose

# Очистити всі завдання
php artisan queue:flush

# Повторити провалені завдання
php artisan queue:retry all
```

## Швидкий чеклист

- [ ] `QUEUE_CONNECTION` встановлено в `.env`
- [ ] Міграції для черги виконано (`php artisan queue:table && php artisan migrate`)
- [ ] Queue worker запущено (`php artisan queue:work`)
- [ ] **Worker слухає правильну чергу** (перевірте `TELEGRAM_BOT_QUEUE` в .env або `--queue` в команді worker)
- [ ] API ключ Telegram налаштовано (`TELEGRAM_BOT_API_KEY`)
- [ ] Перевірено таблицю `jobs` на наявність завдань (і в якій черзі вони)
- [ ] Перевірено `failed_jobs` на помилки
- [ ] Логи перевірено (`storage/logs/laravel.log`)

## Підтримка

Якщо після всіх перевірок повідомлення все ще не відправляються, перевірте:

1. Логи Laravel: `storage/logs/laravel.log`
2. Таблицю failed_jobs: `SELECT * FROM failed_jobs`
3. Worker процес: `ps aux | grep queue:work`
4. Конфігурацію API ключа: `php artisan tinker` → `config('telegram-notifier.api_key')`