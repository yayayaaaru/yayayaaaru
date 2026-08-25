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
        return view('web.developers.latest');
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
}
