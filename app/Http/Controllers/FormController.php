<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use App\Models\Form;
use App\Services\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class FormController extends Controller
{
    /**
     * Inject FormService via constructor (Laravel auto-resolves via DI container).
     */
    public function __construct(
        private FormService $formService
    ) {}

    /**
     * Display a paginated, filterable list of all forms.
     */
    public function index(Request $request): Response
    {
        $filters = $request->only([
            'search',
            'society_id',
            'office_id',
            'reg_type',
            'size',
            'sort',
            'direction',
        ]);

        $forms = $this->formService->getAllForms($filters);
        return Inertia::render('Forms/Index', [
            'forms' => $forms,
            'filters' => $filters,
            'dropdowns' => $this->formService->getDropdownOptions(),
        ]);
    }

    /**
     * Display the detail view of a single form.
     */
    public function show(Form $form): Response
    {
        $form = $this->formService->findForm($form->id);

        return Inertia::render('Forms/Show', [
            'form' => $form,
        ]);
    }

    /**
     * Show the form creation page.
     */
    public function create(): Response
    {
        $boxNo = now()->format('dmy');

        return Inertia::render('Forms/Create', [
            'dropdowns'   => $this->formService->getDropdownOptions(),
            'box_no'      => $boxNo,
            'next_sr_no'  => $this->formService->getNextSrNo($boxNo),
            'sr_no_count' => $this->formService->getBoxSrNoCount($boxNo),
        ]);
    }

    /**
     * Validate and persist a new form record.
     */
    public function store(StoreFormRequest $request): RedirectResponse
    {
        $form = $this->formService->createForm($request->validated());

        return redirect()
            ->route('forms.index', $form)
            ->with('success', 'Form created successfully.');
    }

    /**
     * Show the edit page for an existing form.
     */
    public function edit(Form $form): Response
    {
        return Inertia::render('Forms/Edit', [
            'form' => $form,
            'dropdowns' => $this->formService->getDropdownOptions(),
        ]);
    }

    /**
     * Validate and update an existing form record.
     */
    public function update(UpdateFormRequest $request, Form $form): RedirectResponse
    {
        $this->formService->updateForm($form, $request->validated());

        return redirect()
            ->route('forms.index', $form)
            ->with('success', 'Form updated successfully.');
    }

    /**
     * Delete a form record permanently.
     */
    public function destroy(Form $form): RedirectResponse
    {
        $this->formService->deleteForm($form);

        return redirect()
            ->route('forms.index')
            ->with('success', 'Form deleted successfully.');
    }

    /**
     * Mark a form as live.
     */
    public function markLive(Form $form): RedirectResponse
    {
        $this->formService->markAsLive($form);

        return redirect()
            ->back()
            ->with('success', 'Form marked as live.');
    }

    /**
     * Mark a form as a member transfer.
     */
    public function markTransfer(Form $form): RedirectResponse
    {
        $this->formService->markAsMemberTransfer($form);

        return redirect()
            ->back()
            ->with('success', 'Member transfer marked successfully.');
    }

    /**
     * Proxy: fetch live booking data from the external API by form number.
     * Keeps the external API call server-side to avoid CORS issues.
     */
    public function fetchBookingData(Request $request): JsonResponse
    {
        $formNo = $request->query('form_no');

        if (empty($formNo)) {
            return response()->json(['error' => 'form_no is required.'], 422);
        }

        $response = Http::timeout(60)->get(
            config('services.awamigreen.base_url') . '/awamigreen/get-booking-form-live-data-formno',
            ['form_no' => $formNo]
        );
        // dd($response->json());
        if ($response->failed()) {
            return response()->json(
                ['error' => 'External API request failed. Status: ' . $response->status()],
                $response->status()
            );
        }

        return response()->json($response->json());
    }
}
