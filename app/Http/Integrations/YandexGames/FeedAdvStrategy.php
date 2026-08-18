<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames;

use App\Http\Integrations\YandexGames\Contracts\FeedStrategy;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\FeedAdvDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;

class FeedAdvStrategy implements FeedStrategy
{
    public function dto(array $context): FeedAdvDto
    {
        return new FeedAdvDto(
            FeedType::fromYandex($context),
            $context['items'],
            $context['pageNumber'],
            $context['isFromFirstPage'],
            $context['requestId'],
        );
    }
}
