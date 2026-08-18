<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs;

final readonly class GamesRequestId
{
    public function __construct(
        public string $value,
    )
    {
    }
}
