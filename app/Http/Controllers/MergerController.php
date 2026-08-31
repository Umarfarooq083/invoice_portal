<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Merger;
use App\Services\MergerService;
use Inertia\Inertia;

class MergerController extends Controller
{
    public function __construct(
        private MergerService $mergerService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['sort', 'direction']);
        $mergers = $this->mergerService->getAllMergers($filters);

        return Inertia::render('Mergers/Index', [
            'mergers' => $mergers,
            'filters' => $filters
        ]);
    }

    public function create()
    {
        $data = $this->mergerService->getCreateData();
        return Inertia::render('Mergers/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'society_id' => 'required',
            'from_app_no' => [
                'required',
                \Illuminate\Validation\Rule::unique('invoice_merge', 'from_reg_no')->where(function ($query) use ($request) {
                    return $query->where('society_id', $request->society_id);
                })
            ]
        ], [
            'from_app_no.unique' => 'This Form No has already been used in the selected block.',
        ]);

        // Add store logic later
    }

    public function fetchMainAppData(Request $request)
    {
        $request->validate([
            'reg_no' => 'required',
            'society_id' => 'required'
        ]);

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'token' => config('services.awamigreen.token')
        ])->get(config('services.awamigreen.base_url') . '/mergerinvnew/merger-open-byname-file', [
            'reg_no' => $request->reg_no,
            'society_id' => $request->society_id,
            // 'society_id' => 14,
            'is_open' => $request->is_open
        ]);

        return $response->json();
    }

    public function fetchMergeToData(Request $request)
    {
        $request->validate([
            'reg_no' => 'required'
        ]);

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'token' => config('services.awamigreen.token', '')
        ])->get(config('services.awamigreen.base_url') . '/mergerinvnew/get-open-data-reg', [
            'reg_no' => $request->reg_no
        ]);

        return $response->json();
    }
}
