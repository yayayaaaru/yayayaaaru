<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SourceName as Source;
use App\Models\Game;
use App\Services\HistoryService;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function __construct(
        public readonly HistoryService $historyService,
    )
    {
    }

    public function showcase()
    {
        $ttl = now()->addHours(3);

        /** @var array $statsBySource */
        $statsBySource = Cache::remember(cache_key('games_showcase'), $ttl, function () {

            $stats = [];

            foreach (Source::cases() as $source) {

                $q = Game::whereHasSourceNamed($source);

                $stats[$source->value]['total'] = $q->count();
                $stats[$source->value]['today'] = $q->whereDate('released_at', today())->count();
            }

            return $stats;
        });

        $stats = Cache::remember(cache_key('games_showcase_all'), $ttl, function () {

            $muted = [
                'title' => 'Всего приложений',
                'count' => Game::count(),
                'today' => Game::whereDate('released_at', today())->count(),
                'search' => '/games/search',
            ];

            $green = [
                'title' => 'Опубликовано',
                'count' => Game::whereNull('removed_at')->count(),
                'today' => Game::whereNull('removed_at')->whereDate('released_at', today())->count(),
                'search' => '/games/search?is_removed=false',
            ];

            $yellow = [
                'title' => 'В новинках',
                'count' => Game::whereJsonContains('category_ids', [12])->count(),
                'today' => Game::whereJsonContains('category_ids', [12])->whereDate('released_at', today())->count(),
                'search' => '/games/search?category_ids=12'
            ];

            $red = [
                'title' => 'Удалено',
                'count' => Game::whereNotNull('removed_at')->count(),
                'today' => Game::whereNotNull('removed_at')->whereDate('removed_at', today())->count(),
                'search' => '/games/search?is_removed=true',
            ];

            return [
                'bg-muted-lt' => $muted,
                'bg-green-lt' => $green,
                'bg-yellow-lt' => $yellow,
                'bg-red-lt' => $red,
            ];
        });

        // --- Базовые мета-теги ---
        SEOMeta::setTitle('Игры — Витрина', false)->setDescription('Витрина');

        return view('web.games.showcase', compact('statsBySource', 'stats'));
    }

    public function latest(Source $source)
    {
        $q = Game::query();

        $games = $q
            ->with('developer')
            ->whereHasSourceNamed($source)
            ->whereDate('released_at', today())
            ->latest('released_at')
            ->get();

        // --- Базовые мета-теги ---
        SEOMeta::setTitle(sprintf('%s — новые за сегодня, Игры', $source->name), false)->setDescription('Свежие релизы сегодняшнего дня — играйте в новинки прямо сейчас.');

        return view('web.games.latest', compact('source', 'games'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Source $source)
    {
        $q = Game::query();

        $games = $q
            ->whereNotNull('synced_at')
            ->whereHasSourceNamed($source)
            ->orderByDesc('released_at')
            ->orderByDesc('id')
            ->paginate(30);

        // --- Базовые мета-теги ---
        SEOMeta::setTitle(sprintf('Игры — %s', $source->label()), false)->setDescription($source->label());

        return view('web.games.index', compact('games', 'source'));
    }

    /**
     * Display the specified resource.
     * @todo какашка - переделать
     */
    public function show(Game $game)
    {
        views($game)->cooldown((int)config('viewable.cooldown_minutes'))->record();
        $views_count = views($game)->count(); // @todo

        $source = $game->sources->first(static fn($s) => $s->name === Source::YANDEXGAMES);

        $developer = $game->developer;
        $categories = $game->categories;
        $tags = $game->tags;

        $timelines = $this->historyService->getFieldsTimeline($game, [
            'cis_score',
            'min_load_time_seconds',
            'reviews_count',
            'reviews_scores_avg',
            'reviews_scores_stat',
        ]);

        $historyCisScore = $timelines->get('cis_score');
        $historyMinLoadTime = $timelines->get('min_load_time_seconds');

        $historyReviews = [
            'count' => $timelines->get('reviews_count'),
            'scores_avg' => $timelines->get('reviews_scores_avg'),
            'scores_stat' => $timelines->get('reviews_scores_stat'),
        ];

        $title = sprintf('%s (%d) от %s', $game->title, $game->released_at->year, $source->name->label());
        $description = Str::limit($game->description, 160, '');
        $canonical = route('games.show', [$game, $game->slug]);
        $image = asset('static/media/not-found.png'); // @todo

        // --- Базовые мета-теги ---
        SEOMeta::setTitle($title, false)
            ->setDescription($description)
            ->setCanonical($canonical)
            ->addKeyword($tags->pluck('title')->toArray());

        // --- Open Graph (для соцсетей, но также влияет на карточки в поиске) ---
        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setUrl($canonical)
            ->setType('website')
            ->addImage($image)
            ->addProperty('locale', 'ru_RU')
            ->addProperty('article:author', $developer->name);

        // --- Twitter Card ---
        TwitterCard::setType('summary_large_image')
            ->setTitle($title)
            ->setDescription($description)
            ->setImage($image);

        // --- JSON-LD: schema.org/VideoGame ---
        JsonLd::setType('VideoGame');
        JsonLd::setTitle($game->title);
        JsonLd::setDescription($description);
        JsonLd::addImage($image);
        JsonLd::addValue('url', $canonical);
        JsonLd::addValue('genre', $categories->pluck('title')->toArray());
        JsonLd::addValue('applicationCategory', 'Game');
        JsonLd::addValue('operatingSystem', 'Web Browser');
        JsonLd::addValue('author', ['@type' => 'Organization', 'name' => $developer->name]);
        JsonLd::addValue('datePublished', $game->released_at->toDateString());
        JsonLd::addValue('aggregateRating', [
            '@type' => 'AggregateRating',
            'ratingValue' => round($game->reviews_scores_avg, 1),
            'ratingCount' => $game->reviews_count,
            'bestRating' => 5,
            'worstRating' => 1,
        ]);

        return view('web.games.card.index', compact([
            'game',
            'developer',
            'source',
            'historyCisScore',
            'historyMinLoadTime',
            'historyReviews',
            'categories',
            'tags',
            'views_count',
        ]));
    }
}
