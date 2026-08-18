<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class YandexGamesConnector extends Connector
{
    use AcceptsJson;

    public function resolveBaseUrl(): string
    {
        return config('noilty.services.yagames.endpoint');
    }
}
