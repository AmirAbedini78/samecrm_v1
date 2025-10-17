<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InventoryMiddlewareIndex
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
        // Basic middleware for inventory index
        // You can add specific logic here if needed
        
        return $next($request);
    }
}

