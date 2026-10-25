<?php

namespace RobertDavidDoll\LaravelWorkbench\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Authorize
{
    /**
     * Handle the incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('local') || Gate::allows('viewWorkbench')) {
            return $next($request);
        }

        abort(403);
    }
}
