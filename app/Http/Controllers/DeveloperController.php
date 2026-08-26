<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SourceName as Source;
use App\Models\Developer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function showcase()
    {
        return view('web.developers.showcase');
    }

    public function latest(Source $source)
    {
        $q = Developer::query();

        $developers = $q
            ->whereSourceFor($source)
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('web.developers.latest', compact('source', 'developers'));
    }

    public function games(Developer $developer)
    {
        $source = $developer->sources->first(static fn($s) => $s->name === Source::YANDEXGAMES);

        $games = $developer->games()->paginate(30);

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
