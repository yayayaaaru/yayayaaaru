<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\SourceDto;
use App\Enums\SourceName;
use App\Http\Integrations\YandexGames\DTOs\TagDto\TagDto;
use App\Http\Integrations\YandexGames\Requests\GetTagsRequest;
use App\Http\Integrations\YandexGames\Responses\TagsResponse;
use App\Http\Integrations\YandexGames\YandexGamesConnector;
use App\Models\Tag;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('tags:yandex:grabber')]
#[Description('Command description')]
class TagsYandexGrabberCommend extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /** @var YandexGamesConnector $connector */
        $connector = app(YandexGamesConnector::class);

        $res = $connector->send(new GetTagsRequest);

        if (! $res->ok()) {
            throw new \HttpRuntimeException();
        }

        /** @var TagsResponse $payload */
        $payload = $res->dto();

        $this->withProgressBar(
            $payload->tags,
            function (TagDto $tagDto) {

                $targetSource = new SourceDto(
                    SourceName::YANDEXGAMES->value,
                    (string) $tagDto->id,
                );

                Tag::query()
                    ->whereHasSources([$targetSource])
                    ->firstOr(function () use ($tagDto, $targetSource) {

                        $newTag = Tag::create([
                            'slug' => uniqid(),
                            'title' => $tagDto->title,
                        ]);

                        $newTag->sources()->create([
                            'name' => $targetSource->name,
                            'external_id' => $targetSource->external_id,
                        ]);

                        return $newTag;
                    });
            }
        );

        $this->info(' success');

        return self::SUCCESS;
    }
}
