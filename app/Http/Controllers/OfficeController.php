<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Services\OfficeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OfficeController extends Controller
{
    protected $officeService;

    public function __construct(OfficeService $officeService)
    {
        $this->officeService = $officeService;
    }

    public function index()
    {
        $offices = $this->officeService->getPaginatedOffices();
        
        return Inertia::render('Offices/Index', [
            'offices' => $offices
        ]);
    }

    public function create()
    {
        return Inertia::render('Offices/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $this->officeService->createOffice($validated);

        return redirect()->route('offices.index')->with('success', 'Office created successfully.');
    }

    public function edit(Office $office)
    {
        return Inertia::render('Offices/Edit', [
            'office' => $office
        ]);
    }

    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $this->officeService->updateOffice($office, $validated);

        return redirect()->route('offices.index')->with('success', 'Office updated successfully.');
    }

    public function destroy(Office $office)
    {
        $this->officeService->deleteOffice($office);

        return redirect()->route('offices.index')->with('success', 'Office deleted successfully.');
    }
}
