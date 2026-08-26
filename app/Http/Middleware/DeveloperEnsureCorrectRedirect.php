<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Developer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeveloperEnsureCorrectRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var ?Developer $developer */
        $developer = $request->route('developer');

        if ($developer && $developer->slug !== $request->slug) {
            return redirect()->back();
        }

        return $next($request);
    }
}
