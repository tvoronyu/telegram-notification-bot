<?php

namespace Voronyuk\TelegramNotifier\Services\Notifications;

use Voronyuk\TelegramNotifier\Services\TelegramNotifier;

/**
 * Нотифікації про посилки
 *
 * Використання:
 * $notifier = new ParcelNotifier();
 * $notifier->send('Посилку відскановано');
 */
class ParcelNotifier extends TelegramNotifier
{
    protected function getDefaultChatId(): string
    {
        return config('telegram-notifier.chats.parcels')
            ?? config('telegram-notifier.parcels_chat_id')
            ?? parent::getDefaultChatId();
    }

    /**
     * Швидкий метод: Посилку відскановано
     */
    public function scanned(object $parcel): void
    {
        $message = $this->formatScanMessage($parcel);
        $this->send($message);
    }

    /**
     * Швидкий метод: Помилка при скануванні
     */
    public function scanError(object $parcel, string $error): void
    {
        $message = "⚠️ <b>Помилка сканування</b>\n\n";
        $message .= "Посилка ID: <code>{$parcel->id}</code>\n";
        $message .= "Штрихкод: <code>{$parcel->barcode}</code>\n";
        $message .= "Помилка: <i>{$error}</i>\n";
        $message .= "\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($message);
    }

    /**
     * Швидкий метод: Розбіжність ваги
     */
    public function weightDivergence(object $parcel, float $expectedWeight, float $actualWeight): void
    {
        $diff = abs($actualWeight - $expectedWeight);
        $percent = $expectedWeight > 0 ? round(($diff / $expectedWeight) * 100, 2) : 0;

        $message = "⚖️ <b>Розбіжність ваги!</b>\n\n";
        $message .= "Посилка: <code>{$parcel->barcode}</code>\n";
        $message .= "Очікувана вага: <b>{$expectedWeight} кг</b>\n";
        $message .= "Фактична вага: <b>{$actualWeight} кг</b>\n";
        $message .= "Різниця: <b>{$diff} кг ({$percent}%)</b>\n";
        $message .= "\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($message);
    }

    /**
     * Форматування повідомлення про скан
     */
    protected function formatScanMessage(object $parcel): string
    {
        $message = "📦 <b>Посилку відскановано</b>\n\n";
        $message .= "ID: <code>{$parcel->id}</code>\n";
        $message .= "Штрихкод: <code>{$parcel->barcode}</code>\n";

        if (isset($parcel->weight)) {
            $message .= "Вага: <b>{$parcel->weight} кг</b>\n";
        }

        if (isset($parcel->length, $parcel->width, $parcel->height)) {
            $message .= "Розміри: {$parcel->length} × {$parcel->width} × {$parcel->height} см\n";
        }

        if (isset($parcel->volume)) {
            $message .= "Об'єм: {$parcel->volume} м³\n";
        }

        $message .= "\nЧас сканування: " . now()->format('d.m.Y H:i:s');

        return $message;
    }
}