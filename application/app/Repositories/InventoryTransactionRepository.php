<?php

namespace App\Repositories;

use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryTransactionRepository
{
    protected $transaction;

    public function __construct(InventoryTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get transactions with filters
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search($filters = [])
    {
        $query = $this->transaction->newQuery()->with(['inventory', 'user']);

        // Filter by inventory_id
        if (isset($filters['inventory_id']) && $filters['inventory_id']) {
            $query->where('inventory_id', $filters['inventory_id']);
        }

        // Filter by transaction_type
        if (isset($filters['transaction_type']) && $filters['transaction_type']) {
            $query->where('transaction_type', $filters['transaction_type']);
        }

        // Filter by date range
        if (isset($filters['from_date']) && $filters['from_date']) {
            $query->where('transaction_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date']) && $filters['to_date']) {
            $query->where('transaction_date', '<=', $filters['to_date']);
        }

        // Filter by warehouse
        if (isset($filters['warehouse']) && $filters['warehouse']) {
            $query->where('warehouse', 'LIKE', '%' . $filters['warehouse'] . '%');
        }

        // Filter by user
        if (isset($filters['user_id']) && $filters['user_id']) {
            $query->where('user_id', $filters['user_id']);
        }

        // Search
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('document_number', 'LIKE', '%' . $search . '%')
                  ->orWhere('notes', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('inventory', function($q2) use ($search) {
                      $q2->where('inventory_name', 'LIKE', '%' . $search . '%')
                         ->orWhere('inventory_code', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        // Ordering
        $orderBy = $filters['order_by'] ?? 'transaction_date';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query;
    }

    /**
     * Create a new transaction
     *
     * @param array $data
     * @return InventoryTransaction
     */
    public function create($data)
    {
        return $this->transaction->create($data);
    }

    /**
     * Update transaction
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $transaction = $this->transaction->find($id);
        if (!$transaction) {
            return false;
        }

        return $transaction->update($data);
    }

    /**
     * Delete transaction
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $transaction = $this->transaction->find($id);
        if (!$transaction) {
            return false;
        }

        return $transaction->delete();
    }
}

