<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Services\DealerService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DealerController extends Controller
{
    public function __construct(
        private DealerService $dealerService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['sort', 'direction']);
        $dealers = $this->dealerService->getAllDealers($filters);

        return Inertia::render('Dealers/Index', [
            'dealers' => $dealers,
            'filters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Dealers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dealer_phone' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|max:255',
            'dealer_state' => 'required|string|max:255',
            'is_parse_data' => 'boolean',
            'Authorize' => 'boolean',
            'dealer_relation' => 'nullable|string|max:255',
            'dealer_father' => 'nullable|string|max:255',
        ]);

        $this->dealerService->createDealer($validated);

        return redirect()->route('dealers.index')->with('success', 'Dealer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dealer $dealer)
    {
        // Typically not needed for simple CRUDs if Edit covers it, but added for completeness
        return Inertia::render('Dealers/Show', ['dealer' => $dealer]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dealer $dealer)
    {
        return Inertia::render('Dealers/Edit', ['dealer' => $dealer]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dealer $dealer)
    {
        $validated = $request->validate([
            'dealer_phone' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|max:255',
            'dealer_state' => 'required|string|max:255',
            'is_parse_data' => 'boolean',
            'Authorize' => 'boolean',
            'dealer_relation' => 'nullable|string|max:255',
            'dealer_father' => 'nullable|string|max:255',
        ]);

        $this->dealerService->updateDealer($dealer, $validated);

        return redirect()->route('dealers.index')->with('success', 'Dealer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dealer $dealer)
    {
        $this->dealerService->deleteDealer($dealer);
        return redirect()->route('dealers.index')->with('success', 'Dealer deleted successfully.');
    }
}
