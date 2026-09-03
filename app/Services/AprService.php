<?php

namespace App\Services;

use App\Models\Apr;
use App\Models\Block;
use App\Models\Dealer;
use Illuminate\Pagination\LengthAwarePaginator;

class AprService
{
    /**
     * Get all APR records with optional sorting, paginated.
     */
    public function getAllAprs(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $sortField = $filters['sort'] ?? 'id';
        $sortDirection = $filters['direction'] ?? 'desc';
        $query = Apr::with('block', 'dealer');

        return $query->orderBy($sortField, $sortDirection)->paginate($perPage)->appends(request()->query());
    }

    /**
     * Get data required for the create APR view.
     */
    public function getCreateData(): array
    {
        $boxNo = now()->format('dmy');
        $blocks = Block::whereHas('modules', function ($query) {
            $query->whereIn('module_name', ['merger', 'apr']);
        })->where(function ($query) {
            $query->whereDoesntHave('blockRoles')
                ->orWhereHas('blockRoles', function ($roleQuery) {
                    $roleQuery->whereIn('block_roles.id', auth()->check() ? auth()->user()->blockRoles->pluck('id') : []);
                });
        })->orderBy('name')->get(['id', 'name']);

        return [
            'blocks' => $blocks,
            'box_no' => $boxNo,
            'dealers' => Dealer::all(['id', 'name'])->toArray()
        ];
    }
}
