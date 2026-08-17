<?php

namespace App\Services;

use App\Models\Form;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class FormService
{
    /**
     * Get all forms with optional filters, paginated.
     */
    public function getAllForms(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Form::query();

        // Search by client name, CNIC, form number, or tracking code
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('client_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('client_cnic', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('form_no', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('tracking_code', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filter by society
        if (!empty($filters['society_id'])) {
            $query->where('society_id', $filters['society_id']);
        }

        // Filter by office
        if (!empty($filters['office_id'])) {
            $query->where('office_id', $filters['office_id']);
        }

        // Filter by form type
        if (isset($filters['form_type'])) {
            $query->where('form_type', $filters['form_type']);
        }

        return $query->with('user')->latest()->paginate($perPage);
    }

    /**
     * Find a single form by ID with related user, or throw 404.
     */
    public function findForm(int $id): Form
    {
        return Form::with('user')->findOrFail($id);
    }

    /**
     * Create a new form record.
     * Auto-generates tracking code and QR code value if not provided.
     */
    public function createForm(array $data): Form
    {
        // Generate a unique tracking code if not supplied
        if (empty($data['tracking_code'])) {
            $data['tracking_code'] = $this->generateTrackingCode();
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
            'is_create_live'    => 1,
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
            'cron_updated_at'    => now(),
        ]);

        return $form->fresh();
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
}
