<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\TagDto;

final readonly class StatDto
{
    public function __construct(
        public float $rating,
        public int $ratingCount,
    )
    {
    }
}
