<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\SourceDto;
use App\Enums\SourceName;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto\CategoryDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto\ItemDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;
use App\Http\Integrations\YandexGames\Requests\GetGamesByDeveloperRequest;
use App\Http\Integrations\YandexGames\Responses\GamesByDeveloperResponse;
use App\Http\Integrations\YandexGames\YandexGamesConnector;
use App\Models\Category;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Source;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

#[Signature('games:yandex:grabber')]
#[Description('Command description')]
class GamesYandexGrabberCommend extends Command
{
    private const int CHUNK_SIZE = 24;

    /**
     * Execute the console command.
     */
    public function handle(YandexGamesConnector $connector): int
    {
        $connector->query()->add('games_count', 24);

        Developer::query()
            ->with('sources')
            ->orderByDesc('created_at')
            ->orderBy('id')
            ->chunk(
                self::CHUNK_SIZE,
                function (Collection $developers) use ($connector) {

                    /** @var Developer $developer */
                    foreach ($developers as $developer) {

                        $source = $developer->sources->first(
                            static fn(Source $source) => $source->name === SourceName::YANDEXGAMES
                        );

                        if (is_null($source)) {
                            continue;
                        }

                        do
                        {
                            $externalId = (int)$source->external_id;

                            $res = $connector->send(
                                new GetGamesByDeveloperRequest($externalId)
                            );

                            if (! $res->ok()) {
                                $format = ' 0/0 | developer EID: %d | status CODE: %d';

                                $this->error(sprintf(
                                    $format,
                                    $externalId,
                                    $res->status(),
                                ));

                                break;
                            }

                            /** @var GamesByDeveloperResponse $payload */
                            $payload = $res->dto();

                            $feed = $payload->feed;
                            $pageInfoDto = $payload->pageInfo;

                            foreach ($feed as $feedDto) {

                                if ($feedDto->type !== FeedType::FOUND) {
                                    continue;
                                }

                                $this->withProgressBar(
                                    $feedDto->items,
                                    function (ItemDto $itemDto) use ($developer) {

                                        if ($itemDto->type !== 'game') {
                                            return;
                                        }

                                        $targetSource = new SourceDto(
                                            SourceName::YANDEXGAMES,
                                            (string) $itemDto->appId->value,
                                        );

                                        Game::query()
                                            ->whereHasSources([$targetSource])
                                            ->firstOr(function () use ($itemDto, $targetSource, $developer) {

                                                $newGame = $developer->games()->create([
                                                    'slug' => uniqid(),
                                                    'title' => $itemDto->title,
                                                    'age_rating' => $itemDto->features->ageRating->value,
                                                ]);

                                                $newGame->sources()->create([
                                                    'name' => $targetSource->name->value,
                                                    'external_id' => $targetSource->externalId,
                                                ]);

                                                return $newGame;
                                            });

                                        /** @var CategoryDto $categoryDto */
                                        foreach ($itemDto->categories as $categoryDto) {

                                            $targetSource = new SourceDto(
                                                SourceName::YANDEXGAMES,
                                                (string)$categoryDto->id,
                                            );

                                            Category::query()
                                                ->whereHasSources([$targetSource])
                                                ->firstOr(function () use ($categoryDto, $targetSource) {

                                                    $newCategory = Category::create([
                                                        'slug' => uniqid(),
                                                        'title' => $categoryDto->title,
                                                    ]);

                                                    $newCategory->sources()->create([
                                                        'name' => $targetSource->name->value,
                                                        'external_id' => $targetSource->externalId,
                                                    ]);
                                                });
                                        }
                                    }
                                );

                                $this->info(' success');
                            }

                            $connector->query()->add('page_id', $pageInfoDto->nextPageId);
                            $connector->query()->add('rtx-reqid', $pageInfoDto->requestId);
                        }
                        while ($pageInfoDto->hasNextPage);
                    }
                }
        );

        return self::SUCCESS;
    }
}
