<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto;

use App\Http\Integrations\YandexGames\DTOs\Values\AgeRating;

final readonly class GameFeaturesDto
{
    public function __construct(
        public AgeRating $ageRating,
        public bool $userDataRequired,
    )
    {
    }
}
