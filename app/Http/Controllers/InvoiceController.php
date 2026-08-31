<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Dealer;
use App\Models\Block;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'plot_type', 'sort', 'direction']);
        $invoices = $this->invoiceService->getAllInvoices($filters);

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'filters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->invoiceService->getCreateData();
        return Inertia::render('Invoices/Create', $data);
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

        $this->invoiceService->createInvoice($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Fetch file data from external API
     */
    public function fetchFileData(Request $request)
    {
        $regNo = $request->query('reg_no');
        $societyId = $request->query('society_id');

        if (empty($regNo) || empty($societyId)) {
            return response()->json(['error' => 'reg_no and society_id are required.'], 422);
        }

        $response = Http::withHeaders([
            'token' => config('services.awamigreen.token')
        ])->timeout(60)->get(
            config('services.awamigreen.base_url') . '/openinvbooking/fetch-file-data',
            [
                'reg_no' => $regNo,
                'society_id' => $societyId
                // 'society_id' => 14
            ]
        );

        if ($response->failed()) {
            return response()->json(
                ['error' => 'External API request failed. Status: ' . $response->status()],
                $response->status()
            );
        }

        return response()->json($response->json());
    }
}
