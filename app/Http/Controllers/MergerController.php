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
        $blocks = \App\Models\Block::orderBy('name')->get(['id', 'name']);
        return Inertia::render('Mergers/Create', [
            'blocks' => $blocks
        ]);
    }

    public function store(Request $request)
    {
        // Add validation and store logic later
    }
}
