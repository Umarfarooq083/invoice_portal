<?php

namespace App\Services;

use App\Models\Dealer;
use Illuminate\Pagination\LengthAwarePaginator;

class DealerService
{
    /**
     * Get all dealers with optional sorting, paginated.
     */
    public function getAllDealers(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $sortField = $filters['sort'] ?? 'id';
        $sortDirection = $filters['direction'] ?? 'desc';

        return Dealer::orderBy($sortField, $sortDirection)->paginate($perPage)->appends(request()->query());
    }

    /**
     * Create a new dealer record.
     */
    public function createDealer(array $data): Dealer
    {
        $data['created_by'] = auth()->id();
        return Dealer::create($data);
    }

    /**
     * Update an existing dealer record.
     */
    public function updateDealer(Dealer $dealer, array $data): Dealer
    {
        $dealer->update($data);
        return $dealer->fresh();
    }

    /**
     * Delete a dealer record.
     */
    public function deleteDealer(Dealer $dealer): bool
    {
        return (bool) $dealer->delete();
    }
}
