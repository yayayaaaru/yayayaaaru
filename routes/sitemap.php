<?php

declare(strict_types=1);

use App\Models\Developer;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap;

const SITEMAP_CHUNK_SIZE = 5000;

// ---------- INDEX ----------

Route::get('/sitemap.xml', function () {

    $sitemapIndex = Sitemap\SitemapIndex::create();

    $sitemapIndex->add(Sitemap\Tags\Sitemap::create('/sitemap.main.xml')
        ->setLastModificationDate(
            Carbon::yesterday()
        ));

    $sitemapIndex->add(Sitemap\Tags\Sitemap::create('/sitemap.games.xml')
        ->setLastModificationDate(
            sitemapEntityLastMod(Game::class)
        ));

    $sitemapIndex->add(Sitemap\Tags\Sitemap::create('/sitemap.developers.xml')
        ->setLastModificationDate(
            sitemapEntityLastMod(Developer::class)
        ));

    return response($sitemapIndex->render())
        ->header('Content-Type', 'text/xml')
        ->header('Cache-Control', 'public, max-age=3600');
});

Route::get('/sitemap.main.xml', function () {

    $sitemap = Sitemap\Sitemap::create();

    $sitemap->add(Sitemap\Tags\Url::create('/')
        ->setLastModificationDate(Carbon::yesterday())
        ->setPriority(1));

    $sitemap->add(Sitemap\Tags\Url::create(route('games.showcase'))
        ->setLastModificationDate(Carbon::yesterday())
        ->setPriority(0.8));

    $sitemap->add(Sitemap\Tags\Url::create(route('developers.showcase'))
        ->setLastModificationDate(Carbon::yesterday())
        ->setPriority(0.8));

    $sitemap->add(Sitemap\Tags\Url::create(route('categories'))
        ->setLastModificationDate(Carbon::yesterday())
        ->setPriority(0.6));

    $sitemap->add(Sitemap\Tags\Url::create(route('tags'))
        ->setLastModificationDate(Carbon::yesterday())
        ->setPriority(0.6));

    return response($sitemap->render())
        ->header('Content-Type', 'text/xml')
        ->header('Cache-Control', 'public, max-age=3600');
});

// ---------- Хелперы ----------

function sitemapEntityLastMod(string $modelClass): Carbon
{
    /** @var Model $model */
    $model = app($modelClass);

    $key = sprintf('sitemap:%ss:lastmod', strtolower(class_basename($modelClass)));

    $lastModified = Cache::remember(
        cache_key($key),
        now()->addMinutes(30),
        static fn() => $model::query()->whereNull('removed_at')->max('updated_at')
    );

    return $lastModified ? Carbon::parse($lastModified) : Carbon::yesterday();
}

function sitemapEntityIndex(string $modelClass, string $prefix): \Illuminate\Http\Response
{
    /** @var Model $model */
    $model = app($modelClass);

    $countKey = sprintf('sitemap:%s:count', $prefix);

    $count = Cache::remember(
        cache_key($countKey),
        now()->addMinutes(30),
        static fn() =>$model::query()->whereNull('removed_at')->count()
    );

    $max = max(1, (int)ceil($count / SITEMAP_CHUNK_SIZE));

    $sitemapIndex = Sitemap\SitemapIndex::create();

    for ($num = 1; $num <= $max; $num++) {
        $sitemapIndex->add(Sitemap\Tags\Sitemap::create(
            sprintf('/sitemap.%s.chunk_%d.xml', $prefix, $num)
        ));
    }

    return response($sitemapIndex->render())
        ->header('Content-Type', 'text/xml')
        ->header('Cache-Control', 'public, max-age=1800');
}

/**
 * Keyset-пагинация вместо skip()/take() — не деградирует на поздних чанках
 * при больших таблицах (OFFSET в MySQL/Postgres сканирует и отбрасывает
 * пропущенные строки, keyset идёт сразу по индексу id).
 */
function sitemapChunkGenericQuery(string $modelClass, int $num): Builder
{
    /** @var Model $model */
    $model = app($modelClass);

    $lastId = 0;
    $skip = ($num - 1) * SITEMAP_CHUNK_SIZE;

    if ($skip > 0) $lastId = $model::query()
        ->whereNull('removed_at')
        ->orderBy('id')
        ->skip($skip - 1)
        ->take(1)
        ->value('id') ?? 0;

    return $model::query()
        ->whereNull('removed_at')
        ->where('id', '>', $lastId)
        ->orderBy('id')
        ->take(SITEMAP_CHUNK_SIZE);
}

// ---------- GAMES ----------

Route::get('/sitemap.games.xml', fn() => sitemapEntityIndex(Game::class, 'games'));

Route::get('/sitemap.games.chunk_{num}.xml', function (int $num) {

    $sitemap = Sitemap\Sitemap::create();

    sitemapChunkGenericQuery(Game::class, $num)
        ->select(['id', 'slug', 'updated_at', 'cis_score'])
        ->each(static fn(Game $g) => $sitemap->add(
            Sitemap\Tags\Url::create(route('games.show', [$g, $g->slug]))
                ->setLastModificationDate(Carbon::parse($g->updated_at))
                ->setPriority(min(1.0, 0.4 + ($g->cis_score ?? 0) / 100 * 0.6))
        ));

    return response($sitemap->render())
        ->header('Content-Type', 'text/xml')
        ->header('Cache-Control', 'public, max-age=1800');
})->where('num', '[0-9]+');

// ---------- DEVELOPERS ----------

Route::get('/sitemap.developers.xml', fn() => sitemapEntityIndex(Developer::class, 'developers'));

Route::get('/sitemap.developers.chunk_{num}.xml', function (int $num) {

    $sitemap = Sitemap\Sitemap::create();

    sitemapChunkGenericQuery(Developer::class, $num)
        ->select(['id', 'slug', 'updated_at'])
        ->each(static fn(Developer $d) => $sitemap->add(
            Sitemap\Tags\Url::create(route('developers.show', [$d, $d->slug]))
                ->setLastModificationDate(Carbon::parse($d->updated_at))
                ->setPriority(0.6)
        ));

    return response($sitemap->render())
        ->header('Content-Type', 'text/xml')
        ->header('Cache-Control', 'public, max-age=1800');
})->where('num', '[0-9]+');
