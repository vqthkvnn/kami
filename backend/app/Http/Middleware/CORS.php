<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        header('Access-Control-Allow-Header: Content-type, X-Auth-Token, Authorization, Origin,X-CSRF-Token');
        header('Access-Control-Allow-Origin', ['http://127.0.0.1:8000', '*','http://127.0.0.1:8080' ]);
        header('Access-Control-Allow-Credentials', 'true');
        header('Access-Control-Allow-Methods', '*');
        return $next($request);
    }
}
