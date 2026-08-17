<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\Values;

final readonly class AppSlug
{
    public function __construct(
        public string $value,
    )
    {
        if ($value === '') {
            throw new \InvalidArgumentException('Slug cannot be empty.');
        }
    }
}
