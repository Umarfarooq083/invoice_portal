<?php

namespace App\Services;

use App\Models\AppType;
use App\Models\Block;
use App\Models\Form;
use App\Models\Phase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class FormService
{
    /**
     * Get all dropdown options needed for create/edit forms.
     */
    public function getDropdownOptions(): array
    {
        return [
            'blocks' => Block::all(['id', 'name'])->toArray(),
            'app_types' => AppType::all(['id', 'name'])->toArray(),
            'app_sizes' => config('form_options.app_sizes', []),
            'reg_types' => config('form_options.residential_options', []),
        ];
    }

    /**
     * Get all forms with optional filters, paginated.
     */
    public function getAllForms(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Form::query();

        // Search by client name, CNIC, form number, or tracking code
        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('client_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('client_cnic', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('form_no', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('tracking_code', 'like', '%' . $filters['search'] . '%');
            });
        }
        // dd($filters);
        // Filter by society
        if (! empty($filters['society_id'])) {
            $query->where('society_id', $filters['society_id']);
        }

        // Filter by office
        if (! empty($filters['office_id'])) {
            $query->where('office_id', $filters['office_id']);
        }

        // Filter by form type
        if (! empty($filters['form_type'])) {
            $query->where('form_type', $filters['form_type']);
        }

        // Filter by app size
        if (! empty($filters['size'])) {
            $query->where('size', $filters['size']);
        }

        return $query->with(['user', 'block', 'appType'])->latest()->paginate($perPage)->appends(request()->query());
    }

    /**
     * Find a single form by ID with related data, or throw 404.
     */
    public function findForm(int $id): Form
    {
        return Form::with(['user', 'block', 'appType'])->findOrFail($id);
    }

    /**
     * Create a new form record.
     * Auto-generates tracking code and QR code value if not provided.
     * Auto-sets the current user as user_id if not provided.
     */
    public function createForm(array $data): Form
    {
        // Auto-set the authenticated user
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        // Default to residential since the checkbox is no longer user-facing
        if (empty($data['reg_type'])) {
            $data['reg_type'] = 'residential';
        }

        // Generate a unique tracking code if not supplied
        if (empty($data['tracking_code'])) {
            $data['tracking_code'] = $this->generateTrackingCode();
        }

        // Auto-generate box_no from the current date (DDMMYY) if not supplied
        if (empty($data['box_no'])) {
            $data['box_no'] = $this->generateBoxNo();
        }

        // Auto-generate sr_no: next sequential number for this box_no
        if (empty($data['sr_no'])) {
            $data['sr_no'] = $this->getNextSrNo($data['box_no']);
        }

        // Derive QR code value from the tracking code
        if (empty($data['qr_code'])) {
            $data['qr_code'] = $this->generateQrCodeValue($data['tracking_code']);
        }

        return Form::create($data);
    }

    /**
     * Update an existing form record and return the refreshed instance.
     */
    public function updateForm(Form $form, array $data): Form
    {
        // Default to residential since the checkbox is no longer user-facing
        if (empty($data['reg_type'])) {
            $data['reg_type'] = 'residential';
        }

        // Ensure box_no is set; preserve existing value on edit
        if (empty($data['box_no'])) {
            $data['box_no'] = $form->box_no;
        }

        $form->update($data);

        return $form->fresh();
    }

    /**
     * Permanently delete a form record.
     */
    public function deleteForm(Form $form): bool
    {
        return (bool) $form->delete();
    }

    /**
     * Mark a form as live and set the system creation timestamp.
     */
    public function markAsLive(Form $form): Form
    {
        $form->update([
            'is_create_live' => 1,
            'system_created_at' => now(),
        ]);

        return $form->fresh();
    }

    /**
     * Mark a form as a member transfer and record the cron update timestamp.
     */
    public function markAsMemberTransfer(Form $form): Form
    {
        $form->update([
            'is_member_transfer' => 1,
            'cron_updated_at' => now(),
        ]);

        return $form->fresh();
    }

    // ─── Public Helpers ───────────────────────────────────────────────────────

    /**
     * Get the next sr_no for the given box_no.
     * Starts at 1 and increments by 1 for each existing entry under the same box_no.
     */
    public function getNextSrNo(string $boxNo): int
    {
        $max = Form::where('box_no', $boxNo)->max('sr_no');

        return $max ? $max + 1 : 1;
    }

    /**
     * Count how many Sr Nos already exist for the given box_no.
     */
    public function getBoxSrNoCount(string $boxNo): int
    {
        return Form::where('box_no', $boxNo)->count();
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    /**
     * Generate a unique, uppercase 10-character tracking code.
     */
    private function generateTrackingCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (Form::where('tracking_code', $code)->exists());

        return $code;
    }

    /**
     * Build the QR code string from a tracking code.
     */
    private function generateQrCodeValue(string $trackingCode): string
    {
        return 'INV-' . strtoupper($trackingCode);
    }

    /**
     * Generate a box number from the current date in DDMMYY format
     * (e.g. 17/08/2026 → 170826).
     */
    private function generateBoxNo(): string
    {
        return now()->format('dmy');
    }
}
