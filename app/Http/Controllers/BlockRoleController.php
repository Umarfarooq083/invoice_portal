<?php

namespace App\Http\Controllers;

use App\Models\BlockRole;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlockRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $blockRoles = BlockRole::orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();

        return Inertia::render('BlockRoles/Index', [
            'blockRoles' => $blockRoles,
            'filters' => $request->only(['sort', 'direction'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('BlockRoles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:block_roles,name',
        ]);

        BlockRole::create($validated);

        return redirect()->route('block-roles.index')->with('success', 'Block Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlockRole $blockRole)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlockRole $blockRole)
    {
        return Inertia::render('BlockRoles/Edit', [
            'blockRole' => $blockRole
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlockRole $blockRole)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:block_roles,name,' . $blockRole->id,
        ]);

        $blockRole->update($validated);

        return redirect()->route('block-roles.index')->with('success', 'Block Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlockRole $blockRole)
    {
        $blockRole->delete();
        return redirect()->route('block-roles.index')->with('success', 'Block Role deleted successfully.');
    }
}
