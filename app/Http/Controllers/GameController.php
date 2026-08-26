<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SourceName as Source;
use App\Models\Game;
use App\Services\HistoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct(
        public readonly HistoryService $historyService,
    )
    {
    }

    public function showcase()
    {
        return view('web.games.showcase');
    }

    public function latest(Source $source)
    {
        $q = Game::query();

        $games = $q
            ->with('developer')
            ->whereSourceFor($source)
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
            ->whereSourceFor($source)
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

        $historyCisScore = $this->historyService->getFieldTimeline($game, 'cis_score');

        $historyMinLoadTime = $this->historyService->getFieldTimeline($game, 'min_load_time_seconds');

        $historyReviewsCount = $this->historyService->getFieldTimeline($game, 'reviews_count');
        $historyReviewsScoresAvg = $this->historyService->getFieldTimeline($game, 'reviews_scores_avg');
        $historyReviewsScoresStat = $this->historyService->getFieldTimeline($game, 'reviews_scores_stat');

        $historyReviews = [
            'count' => $historyReviewsCount,
            'scores_avg' => $historyReviewsScoresAvg,
            'scores_stat' => $historyReviewsScoresStat,
        ];

        // @todo какашка - переделать

        return view('web.games.card.index', compact(
            'game', 'developer', 'source', 'historyCisScore', 'historyMinLoadTime', 'historyReviews'
        ));
    }
}
