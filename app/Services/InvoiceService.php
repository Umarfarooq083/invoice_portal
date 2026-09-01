<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Dealer;
use App\Models\Block;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceService
{
    public function getAllInvoices(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Invoice::query();
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('reg_no', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('tracking_code', 'like', "%{$search}%")
                    ->orWhere('client_cnic', 'like', "%{$search}%");
            });
        }
        if (!empty($filters['plot_type'])) {
            $query->where('plot_type', $filters['plot_type']);
        }
        $query->with('block', 'user');
        $sortField = $filters['sort'] ?? 'id';
        $sortDirection = $filters['direction'] ?? 'desc';
        $query->whereHas('block', function ($q) {
            $q->whereDoesntHave('blockRoles')
              ->orWhereHas('blockRoles', function ($roleQuery) {
                  $roleQuery->whereIn('block_roles.id', auth()->check() ? auth()->user()->blockRoles->pluck('id') : []);
              });
        });

        return $query->orderBy($sortField, $sortDirection)->paginate($perPage)->appends(request()->query());
    }
   
    public function getCreateData(): array
    {
        $boxNo = now()->format('dmy');
        $maxSrNo = Invoice::where('box_no', $boxNo)->max('sr_no');
        $nextSrNo = $maxSrNo ? $maxSrNo + 1 : 1;

        $dealers = Dealer::orderBy('name')->get(['id', 'name']);
        $blocks = Block::whereHas('modules', function ($query) {
            $query->where('module_name', 'invoice');
        })->where(function ($query) {
            $query->whereDoesntHave('blockRoles')
                  ->orWhereHas('blockRoles', function ($roleQuery) {
                      $roleQuery->whereIn('block_roles.id', auth()->check() ? auth()->user()->blockRoles->pluck('id') : []);
                  });
        })->orderBy('name')->get(['id', 'name']);

        return [
            'box_no' => $boxNo,
            'next_sr_no' => $nextSrNo,
            'dealers' => $dealers,
            'blocks' => $blocks,
        ];
    }

    public function createInvoice(array $data): Invoice
    {
        if (!isset($data['file_id'])) {
            $data['file_id'] = 0; 
        }
        if (!isset($data['dealer_name'])) {
            $data['dealer_name'] = '';
        }

        return Invoice::create($data);
    }
}
