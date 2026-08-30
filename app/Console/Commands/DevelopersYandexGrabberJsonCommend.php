<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\SourceDto;
use App\Enums\SourceName;
use App\Models\Developer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use JsonMachine\Items;

#[Signature('developers:yandex:grabber:json')]
#[Description('Command description')]
class DevelopersYandexGrabberJsonCommend extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $items = Items::fromFile(
            Storage::path('developers_dump_1787941911.json')
        );

        $progressBar = $this->output->createProgressBar();

        foreach ($items as $developer) {

            if ($developer->is_removed) {
                continue;
            }

            $targetSource = new SourceDto(
                SourceName::YANDEXGAMES,
                (string)$developer->external_id,
            );

            Developer::query()
                ->whereHasSources([$targetSource])
                ->firstOr(function () use ($developer, $targetSource) {

                    $newDeveloper = Developer::create([
                        'slug' => uniqid(),
                        'name' => $developer->name,
                    ]);

                    $newDeveloper->sources()->create([
                        'name' => $targetSource->name->value,
                        'external_id' => $targetSource->externalId,
                    ]);

                    return $newDeveloper;
                });

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->newLine();

        return self::SUCCESS;
    }
}
