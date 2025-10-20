<?php

namespace Voronyuk\TelegramNotifier\Notifications;

use Voronyuk\TelegramNotifier\Notifications\Channels\TelegramBotChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TelegramBotNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected int $botId;
    protected string $chatId;
    protected string $message;
    protected ?string $parseMode;
    protected ?int $messageThreadId;

    /**
     * Create a new notification instance.
     *
     * @param int $botId ID бота через який відправити (з бази даних сервісу)
     * @param string $chatId ID чату куди відправити (Telegram chat_id)
     * @param string $message Текст повідомлення (до 4096 символів)
     * @param string|null $parseMode Режим парсингу: HTML, Markdown, MarkdownV2 (опціонально)
     * @param int|null $messageThreadId ID топіку/теми для супергруп (опціонально)
     */
    public function __construct(
        int $botId,
        string $chatId,
        string $message,
        ?string $parseMode = null,
        ?int $messageThreadId = null
    ) {
        $this->botId = $botId;
        $this->chatId = $chatId;
        $this->message = $message;
        $this->parseMode = $parseMode;
        $this->messageThreadId = $messageThreadId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramBotChannel::class];
    }

    /**
     * Get the data representation of the notification for Telegram Bot API.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toTelegramBot($notifiable)
    {
        $data = [
            'bot_id' => $this->botId,
            'chat_id' => $this->chatId,
            'message' => $this->message,
        ];

        if ($this->parseMode !== null) {
            $data['parse_mode'] = $this->parseMode;
        }

        if ($this->messageThreadId !== null) {
            $data['message_thread_id'] = $this->messageThreadId;
        }

        return $data;
    }
}