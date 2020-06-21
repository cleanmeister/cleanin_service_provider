<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class isDeactivated
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ($user->is_active == 0 || (Auth::user()->role_id == 3 && Auth::user()->cleaner_service_provider->service_provider->owner->is_active == 0))) {

			Auth::logout();
            return redirect('inactive');
        }

        return $next($request);
    }
}
