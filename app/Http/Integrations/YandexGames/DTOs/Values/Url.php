<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\Values;

final readonly class Url
{
    public function __construct(
        public string $value,
    )
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid URL.');
        }
    }
}
