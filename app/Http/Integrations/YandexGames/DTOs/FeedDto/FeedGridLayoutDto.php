<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto;

use App\Http\Integrations\YandexGames\Contracts\FeedDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\WidgetDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;

final readonly class FeedGridLayoutDto implements FeedDto
{
    /**
     * @param WidgetDto[] $widgets
     */
    public function __construct(
        public FeedType $type,
        public int $baseRuleIndex,
        public string $blockLabel,
        public array $widgets,
        public bool $isFromFirstPage,
        public string $requestId,
    )
    {
    }
}
