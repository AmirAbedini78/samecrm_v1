<?php

namespace App\Enums;

enum TransactionType: string
{
    case INPUT = 'input';
    case OUTPUT = 'output';

    /**
     * Get label for transaction type
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::INPUT => 'ورود',
            self::OUTPUT => 'خروج',
        };
    }

    /**
     * Get all values as array
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

