<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Dealer;
use App\Models\Block;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reg_no', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('tracking_code', 'like', "%{$search}%")
                    ->orWhere('client_cnic', 'like', "%{$search}%");
            });
        }

        if ($request->filled('plot_type')) {
            $query->where('plot_type', $request->plot_type);
        }
        $query->with('block', 'user');
        $invoices = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'filters' => $request->only(['search', 'plot_type'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boxNo = now()->format('dmy');
        $maxSrNo = Invoice::where('box_no', $boxNo)->max('sr_no');
        $nextSrNo = $maxSrNo ? $maxSrNo + 1 : 1;

        $dealers = Dealer::orderBy('name')->get(['id', 'name']);
        $blocks = Block::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Invoices/Create', [
            'box_no' => $boxNo,
            'next_sr_no' => $nextSrNo,
            'dealers' => $dealers,
            'blocks' => $blocks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'security_code' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'reg_no' => 'nullable|string|max:255',
            'plot_type' => 'nullable|string|max:255',
            'downpayment' => 'nullable|numeric',
            'plot_price' => 'nullable|numeric',
            'client_name' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'client_cnic' => 'nullable|string|max:255',
            'box_no' => 'required|numeric',
            'sr_no' => 'required|max:255',
            'tracking_code' => 'required|string|max:255',
            'received_by' => 'required|numeric',
            'dealer_phone' => 'required|string|max:255',
            'submitter_cnic' => 'nullable|string|max:255',
            'dealer_id' => 'nullable|numeric',
            'society_id' => 'nullable|numeric|exists:blocks,id',
            'file_id' => 'nullable|numeric', // Just in case, to prevent DB error since file_id is required in DB but they didn't ask for it
        ]);

        if (!isset($validated['file_id'])) {
            $validated['file_id'] = 0; // Temporary default since it's an integer column with no default in DB
        }
        if (!isset($validated['dealer_name'])) {
            $validated['dealer_name'] = '';
        }

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }
}
