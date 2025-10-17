<?php

// Live Debug Routes - برای مشاهده خطاها به صورت زنده
Route::get('/debug-live', function() {
    try {
        // Test inventory route
        $response = app('Illuminate\Http\Request')->create('/inventory', 'GET');
        $result = app('Illuminate\Routing\Router')->dispatch($response);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Inventory route is working',
            'timestamp' => now()
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'timestamp' => now()
        ], 500);
    }
});

// Test specific middleware
Route::get('/debug-middleware', function() {
    $middleware = [
        'inventoryMiddlewareIndex' => class_exists('App\Http\Middleware\Inventory\Index'),
        'inventoryMiddlewareCreate' => class_exists('App\Http\Middleware\Inventory\Create'),
        'inventoryMiddlewareEdit' => class_exists('App\Http\Middleware\Inventory\Edit'),
        'inventoryMiddlewareShow' => class_exists('App\Http\Middleware\Inventory\Show'),
        'inventoryMiddlewareDestroy' => class_exists('App\Http\Middleware\Inventory\Destroy'),
    ];
    
    return response()->json([
        'middleware_status' => $middleware,
        'timestamp' => now()
    ]);
});

// Test route registration
Route::get('/debug-routes', function() {
    $routes = [];
    foreach (app('router')->getRoutes() as $route) {
        if (strpos($route->uri(), 'inventory') !== false || strpos($route->uri(), 'sales') !== false) {
            $routes[] = [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'middleware' => $route->gatherMiddleware(),
                'name' => $route->getName()
            ];
        }
    }
    
    return response()->json([
        'routes' => $routes,
        'timestamp' => now()
    ]);
});

