<?php

namespace Voronyuk\TelegramNotifier\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramBotChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Illuminate\Http\Client\Response|null
     */
    public function send($notifiable, Notification $notification)
    {
        // Get the data from the notification
        $data = $notification->toTelegramBot($notifiable);

        if (empty($data)) {
            Log::warning('TelegramBotChannel: Empty notification data');
            return null;
        }

        // Validate required fields according to API spec
        if (!isset($data['bot_id']) || !isset($data['chat_id']) || !isset($data['message'])) {
            Log::error('TelegramBotChannel: Missing required fields', [
                'data' => $data,
                'required' => ['bot_id', 'chat_id', 'message']
            ]);
            return null;
        }

        // Get API key from config
        $apiKey = config('telegram-notifier.api_key');

        if (empty($apiKey)) {
            Log::error('TelegramBotChannel: API key not configured. Set TELEGRAM_BOT_API_KEY in .env');
            return null;
        }

        $url = config('telegram-notifier.notify_url', 'https://telegram-bot.voronyuk.com/api/v1/notify');

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-API-Key' => $apiKey,
                ])
                ->post($url, $data);

            if ($response->successful()) {
                Log::info('TelegramBotChannel: Notification sent successfully', [
                    'message_id' => $response->json('message_id'),
                    'chat_id' => $data['chat_id'],
                ]);
            } else {
                Log::error('TelegramBotChannel: Failed to send notification', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'data' => $data,
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('TelegramBotChannel: Exception while sending notification', [
                'message' => $e->getMessage(),
                'data' => $data,
            ]);

            throw $e;
        }
    }
}
