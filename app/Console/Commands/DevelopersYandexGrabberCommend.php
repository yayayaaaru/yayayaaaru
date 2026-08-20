<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\SourceDto;
use App\Enums\SourceName;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\WidgetDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;
use App\Http\Integrations\YandexGames\Requests\GetFeedRequest;
use App\Http\Integrations\YandexGames\Responses\FeedResponse;
use App\Http\Integrations\YandexGames\YandexGamesConnector;
use App\Models\Developer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('developers:yandex:grabber')]
#[Description('Command description')]
class DevelopersYandexGrabberCommend extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(YandexGamesConnector $connector): int
    {
        do
        {
            $res = $connector->send(new GetFeedRequest);

            if (! $res->ok()) {
                break;
            }

            /** @var FeedResponse $payload */
            $payload = $res->dto();

            $feed = $payload->feed;
            $pageInfoDto = $payload->pageInfo;

            foreach ($feed as $feedDto) {

                if ($feedDto->type !== FeedType::GRID_LAYOUT) {
                    continue;
                }

                $widgets = $feedDto->widgets;

                $this->withProgressBar(
                    $widgets,
                    function (WidgetDto $widgetDto) {

                        if ($widgetDto->type !== 'game') {
                            return;
                        }

                        $gameDto = $widgetDto->data;

                        $targetSource = new SourceDto(
                            SourceName::YANDEXGAMES->value,
                            (string)$gameDto->developer->id,
                        );

                        Developer::query()
                            ->whereHasSources([$targetSource])
                            ->firstOr(function () use ($gameDto, $targetSource) {

                                $newDeveloper = Developer::create([
                                    'slug' => uniqid(),
                                    'name' => $gameDto->developer->name,
                                ]);

                                $newDeveloper->sources()->create([
                                    'name' => $targetSource->name,
                                    'external_id' => $targetSource->external_id,
                                ]);

                                return $newDeveloper;
                            });
                    }
                );

                $this->info(' success');
            }

            $connector->query()->add('page_id', $pageInfoDto->nextPageId);
            $connector->query()->add('rtx-reqid', $pageInfoDto->requestId);
        }
        while ($pageInfoDto->hasNextPage);

        return self::SUCCESS;
    }
}
