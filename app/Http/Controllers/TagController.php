<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = Tag::query();

        $tags = $q
            ->withCount([
                'games',
                'games as period_games_count' => function ($query) {
                    $query->whereDate('released_at', today());
                },
            ])
            ->orderByDesc('period_games_count')
            ->orderByDesc('games_count')
            ->get();

        return view('web.tags.index', compact('tags'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        $games = $tag->games()->paginate(30);

        return view('web.tags.card.index', compact('tag', 'games'));
    }
}
