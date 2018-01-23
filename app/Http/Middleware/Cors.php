<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Logging\Log;

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
        if($request->method() != 'GET') {
            header('Access-Control-Allow-Credentials: true');
            //header('Content-Type: *');
            //header('Access-Control-Allow-Origin: *');
            //header('Access-Control-Allow-Headers: Content-Type');
            header('Access-Control-Allow-Methods: *');
        }
        return $next($request);
            /*->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Content-Type', '*')
            ->header('Access-Control-Allow-Headers', 'Content-Type')
            ->header('Access-Control-Allow-Methods', '*');*/
    }
}
