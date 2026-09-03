<?php

namespace App\Http\Controllers;

use App\Models\Apr;
use App\Models\Dealer;
use App\Models\MergeMultipleFile;
use Illuminate\Http\Request;
use App\Services\AprService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AprController extends Controller
{
    public function __construct(
        private AprService $aprService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['sort', 'direction']);
        $aprs = $this->aprService->getAllAprs($filters);

        return Inertia::render('Aprs/Index', [
            'aprs' => $aprs,
            'filters' => $filters
        ]);
    }

    public function create()
    {
        $data = $this->aprService->getCreateData();
        return Inertia::render('Aprs/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'society_id' => 'required',
        ], [
            'from_app_no.unique' => 'This Form No has already been used in the selected block.',
        ]);

        try {
            DB::beginTransaction();

            $dealer = Dealer::find($request->dealer_id);

            $apr = Apr::create([
                'file_id' => $request->file_id ?? 0,
                'society_id' => $request->society_id,
                'is_open' => $request->sub_option_1 ?? 0,
                'from_reg_no' => $request->from_app_no ?? $request->registration_no,
                'from_security_code' => $request->from_security_code,
                'from_size' => $request->from_size,
                'client_name' => $request->client_name,
                'client_cnic' => $request->client_cnic,
                'plot_type' => $request->app_type,
                'payment_plan_plot_price' => $request->payment_plan_plot_price ?? 0,
                'payment_plan_live_id' => $request->payment_plan_live_id ?? 0,
                'payment_plan_down_payment' => $request->payment_plan_down_payment ?? 0,
                'legder_down_payment' => $request->ledger_down_payment ?? 0,
                'legder_plot_price' => $request->ledger_plot_price ?? 0,
                'sum_payment' => $request->sum_payment ?? 0,
                'received_downpayment' => $request->received_downpayment ?? 0,

                'box_no' => $request->box_no ?? 0,
                'sr_no' => $request->sr_no ?? 0,
                'tracking_code' => $request->tracking_code,
                'dealer_id' => $request->dealer_id,
                'dealer_name' => $dealer ? $dealer->name : ($request->dealer_name ?? ''),
                'dealer_phone' => $request->dealer_phone ?? '',
                'submitter_cnic' => $request->submitter_cnic,
                'status' => 'In-Process',
                'time' => now()->format('H:i:s'),
                'received_by' => auth()->id() ?? 1,
            ]);

            // Store in merge_multiple_files if details exist
            if ($request->has('merge_to_details') && is_array($request->merge_to_details)) {
                foreach ($request->merge_to_details as $detail) {
                    MergeMultipleFile::create([
                        'invoice_merge_id' => $apr->id,
                        'merge_to' => $detail['merge_to'] ?? 0,
                        'reg_no' => $detail['merge_to_no'] ?? null,
                        'security_code' => $detail['to_security_code'] ?? null,
                        'size' => $detail['to_size'] ?? null,
                        'merge_app_type' => $detail['merge_app_type'] ?? null,
                        'ledger_amount' => $detail['ledger_amount'] ?? null,
                        'merging_fee' => $detail['merging_fee'] ?? null,
                        'payment_plan_plot_price' => $detail['to_payment_plan_plot_price'] ?? 0,
                        'payment_plan_live_id' => $detail['to_payment_plan_live_id'] ?? null,
                        'payment_plan_down_payment' => $detail['to_payment_plan_down_payment'] ?? 0,
                        'created_by' => auth()->id() ?? 1,
                        'box_no' => $request->box_no ?? 0,
                        'tracking_code' => $request->tracking_code,
                        'merge_society_id' => $request->society_id,
                        'office_id' => 0,
                        'sr_no' => 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('aprs.index')->with('success', 'APR created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while saving APR: ' . $e->getMessage()]);
        }
    }

    public function fetchMainAppData(Request $request)
    {
        $request->validate([
            'reg_no' => 'required',
            'society_id' => 'required'
        ]);

        if ($request->is_open == '3') {
            // Form Search
            $form = \App\Models\Form::where('inv_reg_no', $request->reg_no)
                ->where('society_id', $request->society_id)
                ->first();

            if ($form) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'reg_no' => $form->inv_reg_no,
                        'security_code' => null,
                        'plot_size_title' => $form->size,
                        'member_name' => $form->client_name,
                        'client_cnic' => $form->client_cnic,
                        'plot_type_title' => $form->reg_type ?? $form->form_type,
                        'payment_plan_plot_price' => $form->plot_price,
                        'payment_plan_id' => $form->payment_plan_id,
                        'payment_plan_down_payment' => $form->down_payment,
                        'legder_down_payment' => $form->down_payment,
                        'legder_plot_price' => $form->plot_price,
                        'sum_payment' => 0,
                        'received_downpayment' => 0,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data not found in Form. Please check the App No.'
            ]);
        }

        if ($request->is_open == '2') {
            // Invoice/Open Search
            $invoice = \App\Models\Invoice::where('reg_no', $request->reg_no)
                ->where('society_id', $request->society_id)
                ->first();

            if ($invoice) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'reg_no' => $invoice->reg_no,
                        'security_code' => $invoice->security_code,
                        'plot_size_title' => $invoice->size,
                        'member_name' => $invoice->client_name,
                        'client_cnic' => $invoice->client_cnic,
                        'plot_type_title' => $invoice->plot_type,
                        'payment_plan_plot_price' => $invoice->payment_plan_plot_price,
                        'payment_plan_id' => $invoice->payment_plan_live_id,
                        'payment_plan_down_payment' => $invoice->payment_plan_down_payment,
                        'legder_down_payment' => $invoice->legder_down_payment,
                        'legder_plot_price' => $invoice->legder_plot_price,
                        'sum_payment' => $invoice->sum_payment,
                        'received_downpayment' => $invoice->received_downpayment,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data not found in Invoice. Please check the App No.'
            ]);
        }

        // By-Name Search (Third Party API)
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'token' => config('services.awamigreen.token')
        ])->get(config('services.awamigreen.base_url') . '/mergerinvnew/merger-open-byname-file', [
            'reg_no' => $request->reg_no,
            'society_id' => $request->society_id,
            'is_open' => $request->is_open
        ]);

        return $response->json();
    }

    public function fetchMergeToData(Request $request)
    {
        $request->validate([
            'reg_no' => 'required'
        ]);

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'token' => config('services.awamigreen.token', '')
        ])->get(config('services.awamigreen.base_url') . '/mergerinvnew/get-open-data-reg', [
            'reg_no' => $request->reg_no
        ]);

        return $response->json();
    }

    public function destroy(Apr $apr)
    {
        $apr->delete();
        return redirect()->route('aprs.index')->with('success', 'APR deleted successfully.');
    }
}
