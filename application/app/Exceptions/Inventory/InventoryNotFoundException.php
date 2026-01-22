<?php

namespace App\Exceptions\Inventory;

use Exception;

class InventoryNotFoundException extends Exception
{
    protected $message = 'کالای مورد نظر یافت نشد';
    protected $code = 404;

    public function __construct($message = null, $code = 404, Exception $previous = null)
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
                'error' => 'inventory_not_found'
            ], $this->code);
        }

        return back()->withErrors(['error' => $this->getMessage()]);
    }
}

