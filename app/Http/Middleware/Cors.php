<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header_remove('Access-Control-Allow-Credentials');
        header_remove('Access-Control-Allow-Origin');
        return $next($request)
            ->header('Access-Control-Allow-Origin', 'http://localhost:8100')
            // Depending of your application you can't use '*'
            // Some security CORS concerns
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods: GET, POST, OPTIONS')
            //->header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With')
            ->header('Access-Control-Allow-Headers','X-Requested-With,content-type')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Max-Age', '10000');

    }
}
