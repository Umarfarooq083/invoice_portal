<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Services\FormService;
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

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
        $filters = $request->only(['search', 'society_id', 'office_id', 'form_type']);

        $forms = $this->formService->getAllForms($filters);

        return Inertia::render('Forms/Index', [
            'forms'   => $forms,
            'filters' => $filters,
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
        return Inertia::render('Forms/Create');
    }

    /**
     * Validate and persist a new form record.
     */
    public function store(StoreFormRequest $request): RedirectResponse
    {
        $form = $this->formService->createForm($request->validated());

        return redirect()
            ->route('forms.show', $form)
            ->with('success', 'Form created successfully.');
    }

    /**
     * Show the edit page for an existing form.
     */
    public function edit(Form $form): Response
    {
        return Inertia::render('Forms/Edit', [
            'form' => $form,
        ]);
    }

    /**
     * Validate and update an existing form record.
     */
    public function update(UpdateFormRequest $request, Form $form): RedirectResponse
    {
        $this->formService->updateForm($form, $request->validated());

        return redirect()
            ->route('forms.show', $form)
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
}
