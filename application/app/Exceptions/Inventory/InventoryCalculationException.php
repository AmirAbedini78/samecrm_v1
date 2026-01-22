<?php

namespace App\Exceptions\Inventory;

use Exception;

class InventoryCalculationException extends Exception
{
    protected $message = 'خطا در محاسبه موجودی';
    protected $code = 500;

    public function __construct($message = null, $code = 500, Exception $previous = null)
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
                'error' => 'calculation_error'
            ], $this->code);
        }

        return back()->withErrors(['error' => $this->getMessage()]);
    }
}

