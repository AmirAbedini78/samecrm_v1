<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Helpers\DebugHelper;

class DebugSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:system {--module=} {--test-routes} {--test-database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug system components and generate reports';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Starting System Debug...');
        
        if ($this->option('test-routes')) {
            $this->testRoutes();
        }
        
        if ($this->option('test-database')) {
            $this->testDatabase();
        }
        
        if ($module = $this->option('module')) {
            $this->testModule($module);
        }
        
        // Create comprehensive debug report
        $reportFile = DebugHelper::createDebugReport();
        $this->info("📄 Debug report created: {$reportFile}");
        
        return 0;
    }

    /**
     * Test routes for the system
     */
    private function testRoutes()
    {
        $this->info('🛣️  Testing Routes...');
        
        $routes = Route::getRoutes();
        $inventoryRoutes = [];
        $salesRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            if (str_contains($uri, 'inventory')) {
                $inventoryRoutes[] = $uri;
            }
            if (str_contains($uri, 'sales')) {
                $salesRoutes[] = $uri;
            }
        }
        
        $this->table(['Inventory Routes'], array_map(fn($route) => [$route], $inventoryRoutes));
        $this->table(['Sales Routes'], array_map(fn($route) => [$route], $salesRoutes));
    }

    /**
     * Test database connections and tables
     */
    private function testDatabase()
    {
        $this->info('🗄️  Testing Database...');
        
        try {
            // Test basic connection
            DB::connection()->getPdo();
            $this->info('✅ Database connection successful');
            
            // Test specific tables
            $tables = ['inventory', 'sales', 'accounting'];
            foreach ($tables as $table) {
                try {
                    $count = DB::table($table)->count();
                    $this->info("✅ Table '{$table}' exists with {$count} records");
                } catch (\Exception $e) {
                    $this->error("❌ Table '{$table}' error: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Test specific module
     */
    private function testModule($module)
    {
        $this->info("🧩 Testing Module: {$module}");
        
        // Test if middleware exists
        $middlewarePath = app_path("Http/Middleware/{$module}");
        if (is_dir($middlewarePath)) {
            $this->info("✅ Middleware directory exists for {$module}");
        } else {
            $this->error("❌ Middleware directory missing for {$module}");
        }
        
        // Test if controller exists
        $controllerPath = app_path("Http/Controllers/{$module}.php");
        if (file_exists($controllerPath)) {
            $this->info("✅ Controller exists for {$module}");
        } else {
            $this->error("❌ Controller missing for {$module}");
        }
        
        // Test if model exists
        $modelPath = app_path("Models/{$module}.php");
        if (file_exists($modelPath)) {
            $this->info("✅ Model exists for {$module}");
        } else {
            $this->error("❌ Model missing for {$module}");
        }
    }
}

