<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameEnsureCorrectRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var ?Game $game */
        $game = $request->route('game');

        if ($game && $game->slug !== $request->slug) {
            return redirect()->back();
        }

        return $next($request);
    }
}
