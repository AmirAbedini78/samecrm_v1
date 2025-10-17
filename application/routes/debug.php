<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\DebugHelper;

// Debug routes (only in development)
if (app()->environment('local', 'development')) {
    
    Route::get('/debug/system', function () {
        $report = [
            'timestamp' => now(),
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'routes_count' => \Route::getRoutes()->count(),
            'middleware_count' => count(request()->route() ? request()->route()->middleware() : []),
            'user_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'current_url' => request()->fullUrl(),
            'request_method' => request()->method(),
        ];
        
        return response()->json($report, 200, [], JSON_PRETTY_PRINT);
    });
    
    Route::get('/debug/routes', function () {
        $routes = [];
        foreach (\Route::getRoutes() as $route) {
            if (str_contains($route->uri(), 'inventory') || str_contains($route->uri(), 'sales')) {
                $routes[] = [
                    'uri' => $route->uri(),
                    'methods' => $route->methods(),
                    'name' => $route->getName(),
                    'middleware' => $route->middleware(),
                ];
            }
        }
        
        return response()->json($routes, 200, [], JSON_PRETTY_PRINT);
    });
    
    Route::get('/debug/middleware', function () {
        $middleware = [];
        $middlewarePath = app_path('Http/Middleware');
        
        if (is_dir($middlewarePath)) {
            $directories = glob($middlewarePath . '/*', GLOB_ONLYDIR);
            foreach ($directories as $dir) {
                $middleware[] = basename($dir);
            }
        }
        
        return response()->json($middleware, 200, [], JSON_PRETTY_PRINT);
    });
}

