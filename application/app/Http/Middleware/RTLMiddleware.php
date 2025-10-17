<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\RTLHelper;

class RTLMiddleware
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
        $locale = app()->getLocale();
        
        // Add RTL variables to view
        view()->share('rtl', RTLHelper::getRTLJSVariables($locale));
        view()->share('isRTL', RTLHelper::isRTL($locale));
        view()->share('direction', RTLHelper::getDirection($locale));
        view()->share('rtlClass', RTLHelper::getRTLClass($locale));
        
        return $next($request);
    }
}

