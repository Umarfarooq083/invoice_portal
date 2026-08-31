<?php

namespace App\Http\Controllers;

use App\Models\InvoiceMergerType;
use App\Services\InvoiceMergerTypeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceMergerTypeController extends Controller
{
    public function __construct(
        private InvoiceMergerTypeService $invoiceMergerTypeService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['sort', 'direction']);
        $mergerTypes = $this->invoiceMergerTypeService->getAll($filters);

        return Inertia::render('InvoiceMergerTypes/Index', [
            'mergerTypes' => $mergerTypes,
            'filters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('InvoiceMergerTypes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        $this->invoiceMergerTypeService->create($validated);

        return redirect()->route('invoice-merger-types.index')->with('success', 'Invoice Merger Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceMergerType $invoiceMergerType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceMergerType $invoiceMergerType)
    {
        return Inertia::render('InvoiceMergerTypes/Edit', ['mergerType' => $invoiceMergerType]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceMergerType $invoiceMergerType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        $this->invoiceMergerTypeService->update($invoiceMergerType, $validated);

        return redirect()->route('invoice-merger-types.index')->with('success', 'Invoice Merger Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceMergerType $invoiceMergerType)
    {
        $this->invoiceMergerTypeService->delete($invoiceMergerType);
        
        return redirect()->route('invoice-merger-types.index')->with('success', 'Invoice Merger Type deleted successfully.');
    }
}
