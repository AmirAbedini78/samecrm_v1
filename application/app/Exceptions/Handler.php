<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Helpers\DebugHelper;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Enhanced error reporting for debugging
            DebugHelper::logError($e, [
                'request_data' => request()->all(),
                'headers' => request()->headers->all(),
                'session_data' => session()->all(),
            ]);
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Create debug report on critical errors
        if ($this->isCriticalError($exception)) {
            $reportFile = DebugHelper::createDebugReport();
            \Log::info("Debug report created: {$reportFile}");
        }

        return parent::render($request, $exception);
    }

    /**
     * Check if the exception is critical
     */
    private function isCriticalError(Throwable $exception): bool
    {
        $criticalTypes = [
            \Illuminate\Database\QueryException::class,
            \Illuminate\Contracts\Container\BindingResolutionException::class,
            \Symfony\Component\ErrorHandler\Error\FatalError::class,
        ];

        return in_array(get_class($exception), $criticalTypes);
    }
}