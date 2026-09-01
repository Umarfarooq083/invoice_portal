<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlockController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');
        $blocks = Block::with('modules', 'blockRoles')->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();
        return Inertia::render('Blocks/Index', [
            'blocks' => $blocks,
            'filters' => $request->only(['sort', 'direction'])
        ]);
    }

    private function getAvailableModules()
    {
        return [
            ['value' => 'forms', 'label' => 'Forms'],
            ['value' => 'invoice', 'label' => 'Invoice'],
            ['value' => 'merger', 'label' => 'Merger'],
            ['value' => 'dealer', 'label' => 'Dealer'],
        ];
    }

    public function create()
    {
        return Inertia::render('Blocks/Create', [
            'availableModules' => $this->getAvailableModules(),
            'availableRoles' => \App\Models\BlockRole::orderBy('name')->get(['id', 'name'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'modules' => 'nullable|array',
            'modules.*' => 'string',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:block_roles,id'
        ]);

        $block = Block::create(['name' => $validated['name']]);
        if (isset($validated['roles'])) {
            $block->blockRoles()->sync($validated['roles']);
        }
        if (isset($validated['modules'])) {
            foreach ($validated['modules'] as $module) {
                $block->modules()->create(['module_name' => $module]);
            }
        }
        return redirect()->route('blocks.index')->with('success', 'Block created successfully.');
    }

    public function show(Block $block)
    {
        return Inertia::render('Blocks/Show', ['block' => $block]);
    }

    public function edit(Block $block)
    {
        $block->load(['modules', 'blockRoles']);
        return Inertia::render('Blocks/Edit', [
            'block' => $block,
            'availableModules' => $this->getAvailableModules(),
            'availableRoles' => \App\Models\BlockRole::orderBy('name')->get(['id', 'name'])
        ]);
    }

    public function update(Request $request, Block $block)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'modules' => 'nullable|array',
            'modules.*' => 'string',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:block_roles,id'
        ]);

        $block->update(['name' => $validated['name']]);

        if (isset($validated['roles'])) {
            $block->blockRoles()->sync($validated['roles']);
        } else {
            $block->blockRoles()->detach();
        }

        $block->modules()->delete(); 
        if (isset($validated['modules'])) {
            foreach ($validated['modules'] as $module) {
                $block->modules()->create(['module_name' => $module]);
            }
        }
        return redirect()->route('blocks.index')->with('success', 'Block updated successfully.');
    }

    public function destroy(Block $block)
    {
        $block->delete();
        return redirect()->route('blocks.index')->with('success', 'Block deleted successfully.');
    }
}
