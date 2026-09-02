<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = Category::query();

        $categories = $q
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
        SEOMeta::setTitle('Все категории', false)->setDescription('Все категории в одном месте: от свежих релизов до проверенной классики.');

        return view('web.categories.index', compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        views($category)->cooldown((int)config('viewable.cooldown_minutes'))->record();
        $views_count = views($category)->count(); // @todo

        $games = $category->games()->orderByDesc('id')->paginate(30);

        // --- Базовые мета-теги ---
        SEOMeta::setTitle(sprintf('Категория — %s', $category->title), false)->setDescription($category->title);

        return view('web.categories.card.index', compact('category', 'games'));
    }
}
