<?php

namespace Acdphp\SchedulePolice\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class RestrictedAccess
{
    public function handle(mixed $request, Closure $next): mixed
    {
        if (app()->environment('local')) {
            return $next($request);
        }

        if (Gate::allows('viewSchedulePolice')) {
            return $next($request);
        }

        abort(403);
    }
}
