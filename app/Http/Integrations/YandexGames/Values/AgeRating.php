<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Values;

use App\Enums\GameRating;

final readonly class AgeRating
{
    public function __construct(
        public string $value,
    )
    {
        if (GameRating::tryFrom($value) === null) {
            throw new \InvalidArgumentException('Invalid age rating.');
        }
    }
}
