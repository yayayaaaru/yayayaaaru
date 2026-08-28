<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SourceName as Source;
use App\Models\Game;
use App\Services\HistoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GameController extends Controller
{
    public function __construct(
        public readonly HistoryService $historyService,
    )
    {
    }

    public function showcase()
    {
        $ttl = now()->addHours(6);

        /** @var array $stats */
        $stats = Cache::remember(cache_key('games_showcase'), $ttl, function () {
            $stats = [];

            foreach (Source::cases() as $source) {
                $stats[$source->value] = Game::whereHasSourceNamed($source)->count();
            }

            return $stats;
        });

        return view('web.games.showcase', compact('stats'));
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

        return view('web.games.index', compact('games', 'source'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
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

        return view('web.games.card.index', compact([
            'game',
            'developer',
            'source',
            'historyCisScore',
            'historyMinLoadTime',
            'historyReviews',
            'categories',
            'tags',
        ]));
    }
}
