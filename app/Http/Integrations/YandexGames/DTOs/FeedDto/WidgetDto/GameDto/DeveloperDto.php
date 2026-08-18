<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto;

final readonly class DeveloperDto
{
    public function __construct(
        public int $id,
        public string $name,
    )
    {
    }
}
