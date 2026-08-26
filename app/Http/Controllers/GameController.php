<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SourceName as Source;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
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
            ->latest()
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
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('web.games.index', compact('games', 'source'));
    }
}
