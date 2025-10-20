<?php

/**
 * Helper functions для швидкого доступу до Telegram нотифікацій
 *
 * Використання:
 * telegram_send('Привіт!');
 * telegram_error($exception);
 * telegram_info('Інформація');
 */

if (!function_exists('telegram_send')) {
    /**
     * Швидка відправка Telegram повідомлення
     *
     * @param string $message
     * @param string|null $chatId
     * @param int|null $botId
     * @return void
     */
    function telegram_send(string $message, ?string $chatId = null, ?int $botId = null): void
    {
        $notifier = new \Voronyuk\TelegramNotifier\Services\TelegramNotifier($botId, $chatId);
        $notifier->send($message);
    }
}

if (!function_exists('telegram_error')) {
    /**
     * Відправити повідомлення про помилку
     *
     * @param \Throwable $exception
     * @param string|null $context
     * @return void
     */
    function telegram_error(\Throwable $exception, ?string $context = null): void
    {
        (new \Voronyuk\TelegramNotifier\Services\Notifications\ErrorNotifier())->exception($exception, $context);
    }
}

if (!function_exists('telegram_critical')) {
    /**
     * Відправити критичне повідомлення про помилку
     *
     * @param string $message
     * @param array|null $context
     * @return void
     */
    function telegram_critical(string $message, ?array $context = null): void
    {
        (new \Voronyuk\TelegramNotifier\Services\Notifications\ErrorNotifier())->critical($message, $context);
    }
}

if (!function_exists('telegram_warning')) {
    /**
     * Відправити попередження
     *
     * @param string $message
     * @return void
     */
    function telegram_warning(string $message): void
    {
        (new \Voronyuk\TelegramNotifier\Services\Notifications\ErrorNotifier())->warning($message);
    }
}

if (!function_exists('telegram_info')) {
    /**
     * Відправити інформаційне повідомлення
     *
     * @param string $message
     * @return void
     */
    function telegram_info(string $message): void
    {
        (new \Voronyuk\TelegramNotifier\Services\Notifications\SystemNotifier())->info($message);
    }
}

if (!function_exists('telegram_success')) {
    /**
     * Відправити повідомлення про успіх
     *
     * @param string $message
     * @return void
     */
    function telegram_success(string $message): void
    {
        (new \Voronyuk\TelegramNotifier\Services\Notifications\SystemNotifier())->success($message);
    }
}

if (!function_exists('telegram_parcel_scanned')) {
    /**
     * Відправити повідомлення про скан посилки
     *
     * @param object $parcel
     * @return void
     */
    function telegram_parcel_scanned(object $parcel): void
    {
        (new \Voronyuk\TelegramNotifier\Services\Notifications\ParcelNotifier())->scanned($parcel);
    }
}

if (!function_exists('telegram_daily_report')) {
    /**
     * Відправити щоденний звіт
     *
     * @param array $stats
     * @return void
     */
    function telegram_daily_report(array $stats): void
    {
        (new \Voronyuk\TelegramNotifier\Services\Notifications\ReportNotifier())->daily($stats);
    }
}