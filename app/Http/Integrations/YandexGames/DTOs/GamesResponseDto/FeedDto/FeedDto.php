<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto;

use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\WidgetDto;

final readonly class FeedDto
{
    /**
     * @param WidgetDto[] $widgets
     */
    public function __construct(
        public string $type,
        public int $baseRuleIndex,
        public string $blockLabel,
        public array $widgets,
        public bool $isFromFirstPage,
        public string $requestId,
    )
    {
    }
}
