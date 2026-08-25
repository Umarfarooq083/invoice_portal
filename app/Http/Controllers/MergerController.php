<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Merger;
use Inertia\Inertia;

class MergerController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $mergers = Merger::orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();
        return Inertia::render('Mergers/Index', [
            'mergers' => $mergers,
            'filters' => $request->only(['sort', 'direction'])
        ]);
    }

    public function create()
    {
        $boxNo = now()->format('dmy');
        $blocks = \App\Models\Block::whereHas('modules', function ($query) {
            $query->where('module_name', 'merger');
        })->orderBy('name')->get(['id', 'name']);
        return Inertia::render('Mergers/Create', [
            'blocks' => $blocks,
            'box_no' => $boxNo
        ]);
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
            'token' => env('API_TOKEN')
        ])->get(env('AWAMI_GREEN_API_BASE_URL') . '/mergerinvnew/merger-open-byname-file', [
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
            'token' => env('API_TOKEN', '')
        ])->get('http://mi.blueworldcity.com/frontend/web/api/mergerinvnew/get-open-data-reg', [
            'reg_no' => $request->reg_no
        ]);

        return $response->json();
    }
}
