<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\SourceDto;
use App\Enums\SourceName;
use App\Http\Integrations\YandexGames\DTOs\GameDto\GameDto;
use App\Http\Integrations\YandexGames\Requests\GetGamesByIdsRequest;
use App\Http\Integrations\YandexGames\Responses\GamesByIdsResponse;
use App\Http\Integrations\YandexGames\YandexGamesConnector;
use App\Models\Game;
use App\Models\Tag;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

#[Signature('tags:yandex:games:sync')]
#[Description('Command description')]
class TagsYandexGamesSyncCommend extends Command
{
    private const int CHUNK_SIZE = 24;

    /**
     * Execute the console command.
     */
    public function handle(YandexGamesConnector $connector): int
    {
        Game::query()
            ->with('sources')
            ->orderByDesc('created_at')
            ->orderBy('id')
            ->chunk(
                self::CHUNK_SIZE,
                function (Collection $games) use ($connector) {

                    /** @var string[] $ids */
                    $ids = $games
                        ->map(
                            static fn(Game $game) => $game
                                ->sources
                                ->firstWhere('name', SourceName::YANDEXGAMES)
                                ->external_id
                        )
                        ->all();

                    $res = $connector->send(new GetGamesByIdsRequest($ids));

                    if (!$res->ok()) {
                        return;
                    }

                    /** @var GamesByIdsResponse $payload */
                    $payload = $res->dto();

                    /** @var Collection<int> $allTagIds */
                    $allTagIds = collect($payload->games)
                        ->flatMap(static fn(GameDto $dto) => $dto->tagIds)
                        ->unique()
                        ->values();

                    $tags = Tag::query()
                        ->with('sources')
                        ->whereHasSources(
                            $allTagIds->map(
                                static fn(int $tagId) => new SourceDto(
                                    SourceName::YANDEXGAMES->value,
                                    (string)$tagId,
                                )
                            )
                        )
                        ->get();

                    $this->withProgressBar(
                        $payload->games,
                        static function (GameDto $gameDto) use ($games, $tags) {

                            /** @var Game|null $game */
                            $game = $games->first(
                                static fn(Game $game) => $game
                                        ->sources
                                        ->firstWhere('name', SourceName::YANDEXGAMES)
                                        ->external_id === (string)$gameDto->id->value
                            );

                            if (is_null($game)) {
                                return;
                            }

                            $gameTagIds = collect($gameDto->tagIds)->map(static fn(int $id) => (string)$id);

                            $gameTags = $tags->filter(
                                static fn(Tag $tag) => $gameTagIds->contains(
                                    $tag->sources
                                        ->firstWhere('name', SourceName::YANDEXGAMES)
                                        ->external_id
                                )
                            );

                            $game->tags()->sync($gameTags);
                        }
                    );

                    $this->info(' success');
                }
            );

        return self::SUCCESS;
    }
}
