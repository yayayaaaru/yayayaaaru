<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Enums;

use App\Http\Integrations\YandexGames\Contracts\FeedStrategy;
use App\Http\Integrations\YandexGames\FeedAdvStrategy;
use App\Http\Integrations\YandexGames\FeedGridLayoutStrategy;

enum FeedType: string
{
    case ADV = 'adv';
    case GRID_LAYOUT = 'grid_layout';
    case FOUND = 'found';

    public function getStrategy(): FeedStrategy
    {
        return match($this) {
            self::ADV => new FeedAdvStrategy,
            self::GRID_LAYOUT => new FeedGridLayoutStrategy,
        };
    }

    public static function fromYandex(array $feedItem): self
    {
        if (self::tryFrom($feedItem['type']) === null) {
            throw new \InvalidArgumentException('Invalid type feed.');
        }

        return self::from($feedItem['type']);
    }
}
