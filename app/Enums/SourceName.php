<?php

declare(strict_types=1);

namespace App\Enums;

enum SourceName: string
{
    case YANDEXGAMES = 'yandexgames';
    case CRAZYGAMES = 'crazygames';
    case POKI = 'poki';

    public function label(): string
    {
        return match($this) {
            self::YANDEXGAMES => 'Яндекс.Игры',
            self::CRAZYGAMES => 'CrazyGames',
            self::POKI => 'Poki',
        };
    }

    public function logo(): string
    {
        return match($this) {
            self::YANDEXGAMES => asset('static/media/brands/yandexgames.svg'),
            self::CRAZYGAMES => asset('static/media/brands/crazygames.svg'),
            self::POKI => asset('static/media/brands/poki.svg'),
        };
    }
}
