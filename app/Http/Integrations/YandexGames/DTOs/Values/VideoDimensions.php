<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\Values;

final readonly class VideoDimensions
{
    public function __construct(
        public int $width,
        public int $height,
    )
    {
        if ($width <= 0 || $height <= 0) {
            throw new \InvalidArgumentException('Video dimensions must be positive.');
        }
    }
}
