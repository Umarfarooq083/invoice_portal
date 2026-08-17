<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for update — tracking_code unique ignore karo current record
     */
    public function rules(): array
    {
        $formId = $this->route('form')?->id ?? $this->route('form');

        return [
            'form_no'           => ['sometimes', 'required', 'string', 'max:255'],
            'client_name'       => ['sometimes', 'required', 'string', 'max:255'],
            'client_cnic'       => ['sometimes', 'required', 'string', 'max:20'],
            'tracking_code'     => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('forms', 'tracking_code')->ignore($formId),
            ],
            'size'              => ['sometimes', 'required', 'string', 'max:100'],
            'reg_type'          => ['sometimes', 'required', 'string', 'max:100'],
            'address'           => ['sometimes', 'required', 'string'],
            'office_id'         => ['sometimes', 'required', 'integer'],
            'box_no'            => ['sometimes', 'required', 'integer'],
            'sr_no'             => ['sometimes', 'required', 'integer'],
            'user_id'           => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'down_payment'      => ['sometimes', 'required', 'integer', 'min:0'],
            'society_id'        => ['sometimes', 'required', 'integer'],
            'contact'           => ['sometimes', 'required', 'string', 'max:20'],
            'deposite_slip_no'  => ['sometimes', 'required', 'string', 'max:255'],
            'rebate_id'         => ['nullable', 'integer'],
            'inv_reg_no'        => ['nullable', 'string', 'max:255'],
            'form_type'         => ['nullable', 'integer'],
            'plot_price'        => ['nullable', 'integer', 'min:0'],
            'dealer_name'       => ['nullable', 'string', 'max:255'],
            'villa_no'          => ['nullable', 'string', 'max:100'],
            'payment_plan_id'   => ['nullable', 'integer'],
            'dealer_id'         => ['nullable', 'integer'],
            'submitted_by'      => ['nullable', 'string', 'max:255'],
            'submitter_cnic'    => ['nullable', 'string', 'max:20'],
            'phase_id'          => ['nullable', 'integer'],
            'return_issue_id'   => ['nullable', 'integer'],
            'submitter_contact' => ['nullable', 'string', 'max:20'],
        ];
    }
}
