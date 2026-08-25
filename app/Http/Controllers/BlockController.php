<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $blocks = Block::orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return Inertia::render('Blocks/Index', [
            'blocks' => $blocks,
            'filters' => $request->only(['sort', 'direction'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Blocks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Block::create($validated);

        return redirect()->route('blocks.index')->with('success', 'Block created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block)
    {
        return Inertia::render('Blocks/Show', ['block' => $block]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block)
    {
        return Inertia::render('Blocks/Edit', ['block' => $block]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $block->update($validated);

        return redirect()->route('blocks.index')->with('success', 'Block updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        $block->delete();
        return redirect()->route('blocks.index')->with('success', 'Block deleted successfully.');
    }
}
