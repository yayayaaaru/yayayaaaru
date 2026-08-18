<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GameDto;

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
