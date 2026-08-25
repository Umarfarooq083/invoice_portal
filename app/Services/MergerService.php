<?php

namespace App\Services;

use App\Models\Merger;
use App\Models\Block;
use Illuminate\Pagination\LengthAwarePaginator;

class MergerService
{
    /**
     * Get all mergers with optional sorting, paginated.
     */
    public function getAllMergers(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $sortField = $filters['sort'] ?? 'id';
        $sortDirection = $filters['direction'] ?? 'desc';

        return Merger::orderBy($sortField, $sortDirection)->paginate($perPage)->appends(request()->query());
    }

    /**
     * Get data required for the create merger view.
     */
    public function getCreateData(): array
    {
        $boxNo = now()->format('dmy');
        $blocks = Block::whereHas('modules', function ($query) {
            $query->where('module_name', 'merger');
        })->orderBy('name')->get(['id', 'name']);

        return [
            'blocks' => $blocks,
            'box_no' => $boxNo
        ];
    }
}
