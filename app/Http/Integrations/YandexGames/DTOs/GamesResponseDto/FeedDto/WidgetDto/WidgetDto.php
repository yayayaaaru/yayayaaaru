<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto;

use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GameDto;

final readonly class WidgetDto
{
    public function __construct(
        public string $type,
        public ConfigDto $config,
        public GameDto $data,
    )
    {
    }
}
