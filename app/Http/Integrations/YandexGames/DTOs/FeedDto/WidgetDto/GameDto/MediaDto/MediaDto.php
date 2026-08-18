<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\MediaDto;

final readonly class MediaDto
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
