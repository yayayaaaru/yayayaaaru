<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
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
                'games as period_games_count' => function ($query) {
                    $query->whereDate('released_at', today());
                },
            ])
            ->orderByDesc('period_games_count')
            ->orderByDesc('games_count')
            ->get();

        return view('web.categories.index', compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $games = $category->games()->paginate(30);

        return view('web.categories.card.index', compact('category', 'games'));
    }
}
