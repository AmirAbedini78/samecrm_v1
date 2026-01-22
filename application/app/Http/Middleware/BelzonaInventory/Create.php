<?php

namespace App\Http\Middleware\BelzonaInventory;

use Closure;
use Illuminate\Http\Request;

class Create
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
        // Basic middleware for BelzonaInventory create
        // You can add specific logic here if needed
        
        return $next($request);
    }
}

