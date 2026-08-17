<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto;

final readonly class ConfigDto
{
    public function __construct(
        public string $size,
    )
    {
    }
}
