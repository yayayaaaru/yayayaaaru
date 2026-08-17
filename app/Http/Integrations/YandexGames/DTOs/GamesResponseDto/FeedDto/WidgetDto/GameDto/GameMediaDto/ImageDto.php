<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GameMediaDto;

final readonly class ImageDto
{
    public function __construct(
        public string $prefixUrl,
        public string $mainColor,
    )
    {
    }
}
