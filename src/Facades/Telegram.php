<?php

namespace Voronyuk\TelegramNotifier\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Telegram Facade для зручного доступу до нотифікацій
 *
 * @method static \Voronyuk\TelegramNotifier\Services\TelegramNotifier make()
 * @method static \Voronyuk\TelegramNotifier\Services\Notifications\ParcelNotifier parcels()
 * @method static \Voronyuk\TelegramNotifier\Services\Notifications\ErrorNotifier errors()
 * @method static \Voronyuk\TelegramNotifier\Services\Notifications\Error1CNotifier errors1C()
 * @method static \Voronyuk\TelegramNotifier\Services\Notifications\ReportNotifier reports()
 * @method static \Voronyuk\TelegramNotifier\Services\Notifications\SystemNotifier system()
 * @method static void send(string $message)
 *
 * @see \Voronyuk\TelegramNotifier\Services\TelegramNotifierFactory
 */
class Telegram extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'telegram.notifier';
    }
}
