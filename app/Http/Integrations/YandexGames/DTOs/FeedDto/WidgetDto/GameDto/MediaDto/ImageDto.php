<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\MediaDto;

final readonly class ImageDto
{
    public function __construct(
        public string $prefixUrl,
        public string $mainColor,
    )
    {
    }
}
