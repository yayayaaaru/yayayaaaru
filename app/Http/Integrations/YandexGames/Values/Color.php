<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Values;

final readonly class Color
{
    public function __construct(
        public string $hex,
    )
    {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
            throw new \InvalidArgumentException('Invalid color.');
        }
    }
}
