<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\TagDto;

final readonly class InfoDto
{
    public function __construct(
        public int $gamesCount,
    )
    {
    }
}
