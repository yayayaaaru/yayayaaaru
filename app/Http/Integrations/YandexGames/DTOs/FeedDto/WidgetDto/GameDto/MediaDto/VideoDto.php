<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\MediaDto;

use App\Http\Integrations\YandexGames\Values\Url;
use App\Http\Integrations\YandexGames\Values\VideoDimensions;

final readonly class VideoDto
{
    public function __construct(
        public Url $thumbnailUrl,
        public Url $thumbnailUrlPrefix,
        public Url $previewUrl,
        public Url $mp4StreamUrl,
        public VideoDimensions $dimensions,
    )
    {
    }
}
