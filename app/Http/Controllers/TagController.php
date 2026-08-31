<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tag;
use Artesaos\SEOTools\Facades\SEOMeta;
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
                'games as period_games_count' => fn($query) => $query->whereBetween('released_at', [
                    today()->startOfDay(),
                    today()->endOfDay(),
                ]),
            ])
            ->orderByDesc('period_games_count')
            ->orderByDesc('games_count')
            ->get();

        // --- Базовые мета-теги ---
        SEOMeta::setTitle('Все теги', false)->setDescription('Игры по тегам: без скачивания, десктоп и бесплатные.');

        return view('web.tags.index', compact('tags'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        views($tag)->cooldown((int)config('viewable.cooldown_minutes'))->record();
        $views_count = views($tag)->count(); // @todo

        $games = $tag->games()->orderByDesc('id')->paginate(30);

        // --- Базовые мета-теги ---
        SEOMeta::setTitle(sprintf('Тег — %s', $tag->title), false)->setDescription($tag->title);

        return view('web.tags.card.index', compact('tag', 'games'));
    }
}
