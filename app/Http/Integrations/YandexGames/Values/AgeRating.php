<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Values;

use App\Enums\GameAgeRating;

final readonly class AgeRating
{
    public function __construct(
        public string $value,
    )
    {
        if (GameAgeRating::tryFrom($value) === null) {
            throw new \InvalidArgumentException('Invalid age rating.');
        }
    }
}
