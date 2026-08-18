<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto;

final readonly class BadgeDto
{
    public function __construct(
        public string $title,
        public string $type,
    )
    {
    }
}
