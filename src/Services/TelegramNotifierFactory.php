<?php

namespace Voronyuk\TelegramNotifier\Services;

use Voronyuk\TelegramNotifier\Services\Notifications\Error1CNotifier;
use Voronyuk\TelegramNotifier\Services\Notifications\ParcelNotifier;
use Voronyuk\TelegramNotifier\Services\Notifications\ErrorNotifier;
use Voronyuk\TelegramNotifier\Services\Notifications\ReportNotifier;
use Voronyuk\TelegramNotifier\Services\Notifications\SystemNotifier;

/**
 * Factory для створення різних типів Telegram нотифікацій
 */
class TelegramNotifierFactory
{
    /**
     * Створити базовий нотифаєр
     */
    public function make(): TelegramNotifier
    {
        return new TelegramNotifier();
    }

    /**
     * Нотифікації про посилки
     */
    public function parcels(): ParcelNotifier
    {
        return new ParcelNotifier();
    }

    /**
     * Нотифікації про помилки
     */
    public function errors(): ErrorNotifier
    {
        return new ErrorNotifier();
    }

    /**
     * Нотифікації про помилки 1C
     */
    public function errors1C(): Error1CNotifier
    {
        return new Error1CNotifier();
    }

    /**
     * Звіти
     */
    public function reports(): ReportNotifier
    {
        return new ReportNotifier();
    }

    /**
     * Системні нотифікації
     */
    public function system(): SystemNotifier
    {
        return new SystemNotifier();
    }

    /**
     * Швидка відправка повідомлення
     */
    public function send(string $message): void
    {
        (new TelegramNotifier())->send($message);
    }
}
