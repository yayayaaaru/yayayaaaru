<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Values;

final readonly class AppId
{
    public function __construct(
        public int $value,
    )
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('App ID must be positive.');
        }
    }
}
