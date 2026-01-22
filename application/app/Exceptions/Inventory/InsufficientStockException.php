<?php

namespace App\Exceptions\Inventory;

use Exception;

class InsufficientStockException extends Exception
{
    protected $message = 'موجودی کافی نیست';
    protected $code = 422;

    public function __construct($message = null, $code = 422, Exception $previous = null)
    {
        $message = $message ?? $this->message;
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'error' => 'insufficient_stock'
            ], $this->code);
        }

        return back()->withErrors(['error' => $this->getMessage()]);
    }
}

