<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }
        
        return $next($request);
    }
}
