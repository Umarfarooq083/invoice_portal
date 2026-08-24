<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Merger;
use Inertia\Inertia;

class MergerController extends Controller
{
    public function index()
    {
        $mergers = Merger::latest()->paginate(10);
        return Inertia::render('Mergers/Index', ['mergers' => $mergers]);
    }

    public function create()
    {
        $boxNo = now()->format('dmy');
        $blocks = \App\Models\Block::orderBy('name')->get(['id', 'name']);
        return Inertia::render('Mergers/Create', [
            'blocks' => $blocks,
            'box_no' => $boxNo
        ]);
    }

    public function store(Request $request)
    {
        // Add validation and store logic later
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
}
