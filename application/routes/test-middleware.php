<?php

// Test middleware registration
Route::get('/test-middleware', function () {
    $results = [];
    
    // Test Inventory middleware
    try {
        $inventoryIndex = app('App\Http\Middleware\Inventory\Index');
        $results['inventory_index'] = '✅ Inventory Index middleware exists';
    } catch (Exception $e) {
        $results['inventory_index'] = '❌ Error: ' . $e->getMessage();
    }
    
    try {
        $inventoryCreate = app('App\Http\Middleware\Inventory\Create');
        $results['inventory_create'] = '✅ Inventory Create middleware exists';
    } catch (Exception $e) {
        $results['inventory_create'] = '❌ Error: ' . $e->getMessage();
    }
    
    // Test Sales middleware
    try {
        $salesIndex = app('App\Http\Middleware\Sales\Index');
        $results['sales_index'] = '✅ Sales Index middleware exists';
    } catch (Exception $e) {
        $results['sales_index'] = '❌ Error: ' . $e->getMessage();
    }
    
    // Test routes
    $routes = [];
    foreach (Route::getRoutes() as $route) {
        if (str_contains($route->uri(), 'inventory') || str_contains($route->uri(), 'sales')) {
            $routes[] = [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'middleware' => $route->middleware(),
            ];
        }
    }
    
    return response()->json([
        'middleware_tests' => $results,
        'routes' => $routes,
        'timestamp' => now(),
    ], 200, [], JSON_PRETTY_PRINT);
});

