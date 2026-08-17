<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GameMediaDto;

use App\Http\Integrations\YandexGames\DTOs\Values\Url;
use App\Http\Integrations\YandexGames\DTOs\Values\VideoDimensions;

final readonly class VideoDto
{
    public function __construct(
        public Url $embedUrl,
        public Url $thumbnailUrl,
        public Url $thumbnailUrlPrefix,
        public Url $previewUrl,
        public Url $mp4StreamUrl,
        public VideoDimensions $dimensions,
    )
    {
    }
}
