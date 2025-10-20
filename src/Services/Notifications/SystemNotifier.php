<?php

namespace Voronyuk\TelegramNotifier\Services\Notifications;

use Voronyuk\TelegramNotifier\Services\TelegramNotifier;

/**
 * Системні нотифікації
 *
 * Використання:
 * $notifier = new SystemNotifier();
 * $notifier->info('Система запущена');
 */
class SystemNotifier extends TelegramNotifier
{
    protected function getDefaultChatId(): string
    {
        return config('telegram-notifier.chats.system')
            ?? config('telegram-notifier.system_chat_id')
            ?? parent::getDefaultChatId();
    }

    /**
     * Інформаційне повідомлення
     */
    public function info(string $message): void
    {
        $msg = "ℹ️ <b>Інформація</b>\n\n";
        $msg .= $message;
        $msg .= "\n\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($msg);
    }

    /**
     * Успішна операція
     */
    public function success(string $message): void
    {
        $msg = "✅ <b>Успішно</b>\n\n";
        $msg .= $message;
        $msg .= "\n\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($msg);
    }

    /**
     * Система запущена
     */
    public function startup(): void
    {
        $message = "🚀 <b>Система запущена</b>\n\n";
        $message .= "Сервер: " . gethostname() . "\n";
        $message .= "Середовище: " . config('app.env') . "\n";
        $message .= "Версія PHP: " . PHP_VERSION . "\n";
        $message .= "Версія Laravel: " . app()->version() . "\n";
        $message .= "\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->withoutQueue()->send($message);
    }

    /**
     * Система зупинена
     */
    public function shutdown(?string $reason = null): void
    {
        $message = "🛑 <b>Система зупинена</b>\n\n";

        if ($reason) {
            $message .= "Причина: <i>{$reason}</i>\n\n";
        }

        $message .= "Сервер: " . gethostname() . "\n";
        $message .= "Час: " . now()->format('d.m.Y H:i:s');

        $this->withoutQueue()->send($message);
    }

    /**
     * Планове обслуговування
     */
    public function maintenance(string $startTime, string $duration): void
    {
        $message = "🔧 <b>Планове обслуговування</b>\n\n";
        $message .= "Початок: <b>{$startTime}</b>\n";
        $message .= "Тривалість: <i>{$duration}</i>\n\n";
        $message .= "Під час обслуговування система буде недоступна.\n";
        $message .= "Дякуємо за розуміння!";

        $this->send($message);
    }

    /**
     * Backup завершено
     */
    public function backupCompleted(string $filename, string $size): void
    {
        $message = "💾 <b>Backup завершено</b>\n\n";
        $message .= "Файл: <code>{$filename}</code>\n";
        $message .= "Розмір: <b>{$size}</b>\n";
        $message .= "Час: " . now()->format('d.m.Y H:i:s');

        $this->send($message);
    }

    /**
     * Deployment завершено
     */
    public function deploymentCompleted(string $version, ?string $branch = null): void
    {
        $message = "🚀 <b>Deployment завершено</b>\n\n";
        $message .= "Версія: <code>{$version}</code>\n";

        if ($branch) {
            $message .= "Гілка: <code>{$branch}</code>\n";
        }

        $message .= "Сервер: " . gethostname() . "\n";
        $message .= "Час: " . now()->format('d.m.Y H:i:s');

        $this->send($message);
    }

    /**
     * Високе навантаження
     */
    public function highLoad(array $metrics): void
    {
        $message = "⚠️ <b>Високе навантаження!</b>\n\n";

        if (isset($metrics['cpu'])) {
            $message .= "CPU: <b>{$metrics['cpu']}%</b>\n";
        }

        if (isset($metrics['memory'])) {
            $message .= "Пам'ять: <b>{$metrics['memory']}%</b>\n";
        }

        if (isset($metrics['disk'])) {
            $message .= "Диск: <b>{$metrics['disk']}%</b>\n";
        }

        if (isset($metrics['queue'])) {
            $message .= "Черга: <b>{$metrics['queue']} завдань</b>\n";
        }

        $message .= "\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->withoutQueue()->send($message);
    }
}