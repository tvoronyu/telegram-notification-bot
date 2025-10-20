<?php

namespace Voronyuk\TelegramNotifier\Services\Notifications;

use Voronyuk\TelegramNotifier\Services\TelegramNotifier;

/**
 * Нотифікації для звітів
 *
 * Використання:
 * $notifier = new ReportNotifier();
 * $notifier->daily($stats);
 */
class ReportNotifier extends TelegramNotifier
{
    protected function getDefaultChatId(): string
    {
        return config('services.telegram_bot.reports_chat_id', parent::getDefaultChatId());
    }

    /**
     * Щоденний звіт
     */
    public function daily(array $stats): void
    {
        $message = "📊 <b>Щоденний звіт</b>\n";
        $message .= "Дата: " . now()->format('d.m.Y') . "\n\n";

        if (isset($stats['parcels_scanned'])) {
            $message .= "📦 Посилок відскановано: <b>{$stats['parcels_scanned']}</b>\n";
        }

        if (isset($stats['parcels_processed'])) {
            $message .= "✅ Оброблено: <b>{$stats['parcels_processed']}</b>\n";
        }

        if (isset($stats['parcels_pending'])) {
            $message .= "⏳ В очікуванні: <b>{$stats['parcels_pending']}</b>\n";
        }

        if (isset($stats['errors'])) {
            $message .= "❌ Помилок: <b>{$stats['errors']}</b>\n";
        }

        if (isset($stats['active_users'])) {
            $message .= "\n👥 Активних користувачів: <b>{$stats['active_users']}</b>\n";
        }

        if (isset($stats['avg_processing_time'])) {
            $message .= "🕐 Середній час обробки: <i>{$stats['avg_processing_time']} хв</i>";
        }

        $this->send($message);
    }

    /**
     * Тижневий звіт
     */
    public function weekly(array $stats): void
    {
        $message = "📈 <b>Тижневий звіт</b>\n";
        $message .= "Період: " . now()->subWeek()->format('d.m') . " - " . now()->format('d.m.Y') . "\n\n";

        foreach ($stats as $key => $value) {
            $label = $this->formatStatLabel($key);
            $message .= "{$label}: <b>{$value}</b>\n";
        }

        $this->send($message);
    }

    /**
     * Місячний звіт
     */
    public function monthly(array $stats): void
    {
        $message = "📊 <b>Місячний звіт</b>\n";
        $message .= "Місяць: " . now()->format('F Y') . "\n\n";

        foreach ($stats as $key => $value) {
            $label = $this->formatStatLabel($key);
            $message .= "{$label}: <b>{$value}</b>\n";
        }

        $this->send($message);
    }

    /**
     * Кастомний звіт
     */
    public function custom(string $title, array $data): void
    {
        $message = "📋 <b>{$title}</b>\n\n";

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $message .= "<b>{$key}:</b>\n";
                foreach ($value as $subKey => $subValue) {
                    $message .= "  • {$subKey}: {$subValue}\n";
                }
            } else {
                $message .= "{$key}: <b>{$value}</b>\n";
            }
        }

        $message .= "\nЧас: " . now()->format('d.m.Y H:i:s');

        $this->send($message);
    }

    /**
     * Форматування назви статистики
     */
    protected function formatStatLabel(string $key): string
    {
        $labels = [
            'parcels_scanned' => '📦 Посилок відскановано',
            'parcels_processed' => '✅ Оброблено',
            'parcels_pending' => '⏳ В очікуванні',
            'errors' => '❌ Помилок',
            'active_users' => '👥 Активних користувачів',
            'avg_processing_time' => '🕐 Середній час обробки',
            'total_weight' => '⚖️ Загальна вага',
            'total_volume' => '📏 Загальний об\'єм',
        ];

        return $labels[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }
}