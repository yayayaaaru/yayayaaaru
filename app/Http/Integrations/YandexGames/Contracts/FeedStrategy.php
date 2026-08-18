<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Contracts;

interface FeedStrategy
{
    public function dto(array $context): FeedDto;
}
