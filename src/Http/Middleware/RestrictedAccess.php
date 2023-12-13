<?php

namespace Acdphp\ScheduleControl\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class RestrictedAccess
{
    public function handle($request, Closure $next)
    {
        if (app()->environment('local')) {
            return $next($request);
        }

        if (Gate::allows('viewScheduleControl')) {
            return $next($request);
        }

        abort(403);
    }
}
