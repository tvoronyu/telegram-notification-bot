<?php

namespace Voronyuk\TelegramNotifier\Services;

use Voronyuk\TelegramNotifier\Notifications\TelegramBotNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Базовий клас для відправки Telegram нотифікацій
 *
 * Використання:
 * $notifier = new TelegramNotifier();
 * $notifier->send('Привіт!');
 *
 * Або розширюйте клас для створення спеціалізованих нотифікацій
 */
class TelegramNotifier
{
    protected int $botId;
    protected string $chatId;
    protected ?string $parseMode = 'HTML';
    protected ?int $messageThreadId = null;
    protected bool $useQueue = true;
    protected ?string $queueName = null;

    /**
     * Конструктор з дефолтними значеннями
     */
    public function __construct(
        ?int $botId = null,
        ?string $chatId = null,
        ?string $parseMode = 'HTML',
        ?int $messageThreadId = null
    ) {
        $this->botId = $botId ?? $this->getDefaultBotId();
        $this->chatId = $chatId ?? $this->getDefaultChatId();
        $this->parseMode = $parseMode;
        $this->messageThreadId = $messageThreadId;
    }

    /**
     * Встановити ID бота
     */
    public function botId(int $botId): self
    {
        $this->botId = $botId;
        return $this;
    }

    /**
     * Встановити ID чату
     */
    public function chatId(string $chatId): self
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * Встановити режим парсингу
     */
    public function parseMode(?string $parseMode): self
    {
        $this->parseMode = $parseMode;
        return $this;
    }

    /**
     * Встановити ID топіку
     */
    public function threadId(?int $threadId): self
    {
        $this->messageThreadId = $threadId;
        return $this;
    }

    /**
     * Використовувати чергу (асинхронно)
     */
    public function withQueue(?string $queueName = null): self
    {
        $this->useQueue = true;
        $this->queueName = $queueName;
        return $this;
    }

    /**
     * Не використовувати чергу (синхронно)
     */
    public function withoutQueue(): self
    {
        $this->useQueue = false;
        return $this;
    }

    /**
     * Встановити назву черги
     */
    public function onQueue(string $queueName): self
    {
        $this->queueName = $queueName;
        $this->useQueue = true;
        return $this;
    }

    /**
     * Відправити повідомлення
     */
    public function send(string $message): void
    {
        $notification = new TelegramBotNotification(
            botId: $this->botId,
            chatId: $this->chatId,
            message: $message,
            parseMode: $this->parseMode,
            messageThreadId: $this->messageThreadId
        );

        // Встановити назву черги для нотифікації
        $queueName = $this->queueName ?? config('telegram-notifier.queue', 'default');
        $notification->onQueue($queueName);

        if ($this->useQueue) {
            Notification::route('telegram-bot', null)->notify($notification);
        } else {
            Notification::route('telegram-bot', null)->notifyNow($notification);
        }
    }

    /**
     * Отримати дефолтний ID бота з конфігу
     */
    protected function getDefaultBotId(): int
    {
        return config('telegram-notifier.default_bot_id', 1);
    }

    /**
     * Отримати дефолтний ID чату з конфігу
     */
    protected function getDefaultChatId(): string
    {
        return config('telegram-notifier.default_chat_id', '-1001234567890');
    }
}
