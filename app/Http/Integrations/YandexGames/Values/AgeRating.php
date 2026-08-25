<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Values;

use App\Http\Integrations\YandexGames\Enums\AgeRating as Enum;

final readonly class AgeRating
{
    public function __construct(
        public string $value,
    )
    {
        if (Enum::tryFrom($value) === null) {
            throw new \InvalidArgumentException('Invalid age rating.');
        }
    }
}
