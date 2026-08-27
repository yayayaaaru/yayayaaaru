<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SourceName as Source;
use App\Models\Developer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DeveloperController extends Controller
{
    public function showcase()
    {
        $ttl = now()->addHours(6);

        /** @var array $stats */
        $stats = Cache::remember(cache_key('developers_showcase'), $ttl, function () {
            $stats = [];

            foreach (Source::cases() as $source) {
                $stats[$source->value] = Developer::whereSourceFor($source)->count();
            }

            return $stats;
        });

        return view('web.developers.showcase', compact('stats'));
    }

    public function latest(Source $source)
    {
        $q = Developer::query();

        $developers = $q
            ->whereSourceFor($source)
            ->whereDate('created_at', today())
            ->latest('id')
            ->get();

        return view('web.developers.latest', compact('source', 'developers'));
    }

    public function games(Developer $developer)
    {
        $source = $developer->sources->first(static fn($s) => $s->name === Source::YANDEXGAMES);

        $games = $developer->games()->orderByDesc('id')->paginate(30);

        return view('web.developers.card.games', compact('source', 'developer', 'games'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Source $source)
    {
        $q = Developer::query();

        $developers = $q
            ->whereSourceFor($source)
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('web.developers.index', compact('developers', 'source'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Developer $developer)
    {
        $source = $developer->sources->first(static fn($s) => $s->name === Source::YANDEXGAMES);

        return view('web.developers.card.index', compact('developer', 'source'));
    }
}
