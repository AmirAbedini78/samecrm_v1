<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DebugHelper
{
    /**
     * Log detailed error information for debugging
     */
    public static function logError($exception, $context = [])
    {
        $errorData = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context' => $context,
            'timestamp' => now(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'user_id' => auth()->id(),
        ];

        Log::error('Debug Error', $errorData);
        
        // Also save to a dedicated debug file
        $debugFile = storage_path('logs/debug_' . date('Y-m-d') . '.log');
        File::append($debugFile, json_encode($errorData, JSON_PRETTY_PRINT) . "\n\n");
    }

    /**
     * Log application state for debugging
     */
    public static function logState($message, $data = [])
    {
        $stateData = [
            'message' => $message,
            'data' => $data,
            'timestamp' => now(),
            'url' => request()->fullUrl(),
            'user_id' => auth()->id(),
        ];

        Log::info('Debug State', $stateData);
    }

    /**
     * Create a debug report file
     */
    public static function createDebugReport()
    {
        $report = [
            'timestamp' => now(),
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'routes' => \Route::getRoutes()->count(),
            'middleware' => request()->route() ? request()->route()->middleware() : [],
            'user_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
        ];

        $reportFile = storage_path('logs/debug_report_' . date('Y-m-d_H-i-s') . '.json');
        File::put($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        
        return $reportFile;
    }
}

