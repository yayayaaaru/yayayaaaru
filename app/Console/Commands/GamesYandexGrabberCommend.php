<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Integrations\YandexGames\Requests\GetFeedRequest;
use App\Http\Integrations\YandexGames\YandexGamesConnector;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('games:yandex:grabber')]
#[Description('Command description')]
class GamesYandexGrabberCommend extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var YandexGamesConnector $connector */
        $connector = app(YandexGamesConnector::class);

        $res = $connector->send(new GetFeedRequest);

        if (! $res->ok()) {
            throw new \HttpRuntimeException();
        }

        $payload = $res->dto();

        dd($payload);
    }
}
