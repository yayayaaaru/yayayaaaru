<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GameMediaDto;

final readonly class GameMediaDto
{
    /**
     * @param VideoDto[] $videos
     */
    public function __construct(
        public ImageDto $cover,
        public ImageDto $icon,
        public array $videos,
    )
    {
    }
}
