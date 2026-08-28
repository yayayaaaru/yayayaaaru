<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Middleware\Contracts\HasRoutableSlug;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCorrectSlugRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $routeParam): Response
    {
        $model = $request->route($routeParam);

        if ($model instanceof HasRoutableSlug && $model->getRouteSlug() !== $request->route('slug')) {
            return redirect()->back();
        }

        return $next($request);
    }
}
