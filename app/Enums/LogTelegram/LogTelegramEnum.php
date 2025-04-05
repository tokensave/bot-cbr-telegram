<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Enums\LogTelegram;

enum LogTelegramEnum: string
{
    case SEND = 'send';
    case FAILED = 'failed';

    public function toString(): string
    {
        return match ($this) {
            self::SEND => 'Доставлена',
            self::FAILED => 'Ошибка',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::FAILED => 'red',
            self::SEND => 'green',
        };
    }
}
