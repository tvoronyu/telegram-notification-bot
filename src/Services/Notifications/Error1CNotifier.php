<?php

namespace Voronyuk\TelegramNotifier\Services\Notifications;

use Voronyuk\TelegramNotifier\Services\TelegramNotifier;

/**
 * Нотифікації про помилки
 *
 * Використання:
 * $notifier = new ErrorNotifier();
 * $notifier->send('Критична помилка!');
 * // або
 * $notifier->exception($exception);
 */
class Error1CNotifier extends TelegramNotifier
{
    public function __construct()
    {
        parent::__construct();
        // Помилки відправляємо негайно
        $this->withoutQueue();
    }

    protected function getDefaultChatId(): string
    {
        return config('services.telegram_bot.errors_chat_id', parent::getDefaultChatId());
    }

    protected function getDefaultBotId(): int
    {
        return config('services.telegram_bot.errors_bot_id', parent::getDefaultBotId());
    }

    /**
     * Відправити повідомлення про Exception
     */
    public function exception(\Throwable $exception, ?string $context = null): void
    {
        $message = "🚨 <b>Помилка в системі!</b>\n\n";

        if ($context) {
            $message .= "Контекст: <i>{$context}</i>\n\n";
        }

        $message .= "Тип: <code>" . get_class($exception) . "</code>\n";
        $message .= "Повідомлення: <code>{$exception->getMessage()}</code>\n";
        $message .= "Файл: <code>{$exception->getFile()}:{$exception->getLine()}</code>\n";
        $message .= "\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($message);
    }

    /**
     * Критична помилка
     */
    public function critical(string $message, ?array $context = null): void
    {
        $msg = "🔴 <b>КРИТИЧНА ПОМИЛКА! (1C)</b>\n\n";
        $msg .= $message;

        if ($context) {
            $msg .= "\n\n<b>Контекст:</b>\n<pre>" . json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
        }

        $msg .= "\n\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($msg);
    }

    /**
     * Попередження
     */
    public function warning(string $message): void
    {
        $msg = "⚠️ <b>Попередження</b>\n\n";
        $msg .= $message;
        $msg .= "\n\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($msg);
    }

    /**
     * Помилка валідації
     */
    public function validationError(string $field, string $error, ?array $data = null): void
    {
        $message = "❌ <b>Помилка валідації</b>\n\n";
        $message .= "Поле: <code>{$field}</code>\n";
        $message .= "Помилка: <i>{$error}</i>\n";

        if ($data) {
            $message .= "\nДані:\n<pre>" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
        }

        $message .= "\n\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($message);
    }
}
