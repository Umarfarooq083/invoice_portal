<?php

namespace App\Services;

use App\Models\InvoiceMergerType;

class InvoiceMergerTypeService
{
    /**
     * Get paginated invoice merger types with optional sorting.
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = InvoiceMergerType::query();
        
        if (isset($filters['sort'])) {
            $query->orderBy($filters['sort'], $filters['direction'] ?? 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new invoice merger type.
     */
    public function create(array $data)
    {
        return InvoiceMergerType::create($data);
    }

    /**
     * Update an existing invoice merger type.
     */
    public function update(InvoiceMergerType $invoiceMergerType, array $data)
    {
        $invoiceMergerType->update($data);
        return $invoiceMergerType;
    }

    /**
     * Delete an invoice merger type.
     */
    public function delete(InvoiceMergerType $invoiceMergerType)
    {
        return $invoiceMergerType->delete();
    }
}
