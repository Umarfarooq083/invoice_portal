<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a new form.
     */
    public function rules(): array
    {
        return [
            'society_id' => ['required', 'integer', 'exists:blocks,id'],
            'form_no' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('forms', 'form_no')->where(function ($query) {
                    return $query->where('society_id', $this->society_id);
                })
            ],
            'client_name' => ['required', 'string', 'max:255'],
            'client_cnic' => ['required', 'string', 'max:20'],
            'tracking_code' => ['nullable', 'string', 'max:255', 'unique:forms,tracking_code'],
            'size' => ['required', 'string', 'max:100'],
            'office_id' => ['nullable', 'integer'],
            'box_no' => ['required', 'max:50'],
            'sr_no' => ['required', 'integer'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'down_payment' => ['required', 'integer', 'min:0'],
            'contact' => ['required', 'string', 'max:20'],
            'deposite_slip_no' => ['nullable', 'string', 'max:255'],
            'rebate_id' => ['nullable', 'integer'],
            'inv_reg_no' => ['nullable', 'string', 'max:255'],
            'reg_type' => ['required', 'integer', 'exists:app_types,id'],
            'plot_price' => ['nullable', 'integer', 'min:0'],
            'dealer_name' => ['nullable', 'string', 'max:255'],
            'villa_no' => ['nullable', 'string', 'max:100'],
            'payment_plan_id' => ['nullable', 'integer'],
            'dealer_id' => ['nullable', 'integer'],
            'submitted_by' => ['nullable', 'string', 'max:255'],
            'submitter_cnic' => ['nullable', 'string', 'max:20'],
            'return_issue_id' => ['nullable', 'unsignedBigInteger'],
            'submitter_contact' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'form_no.required' => 'Form number is required.',
            'form_no.unique' => 'This Form number is already registered for the selected block.',
            'client_name.required' => 'Client name is required.',
            'client_cnic.required' => 'Client CNIC is required.',
            'tracking_code.unique' => 'This tracking code already exists.',
            'size.required' => 'Please select a plot size.',
            'address.required' => 'Address is required.',
            'down_payment.required' => 'Down payment amount is required.',
            'contact.required' => 'Contact number is required.',
            'deposite_slip_no.required' => 'Deposit slip number is required.',
            'box_no.required' => 'Box number is required.',
            'sr_no.required' => 'Serial number is required.',
            'office_id.required' => 'Please select an office.',
            'user_id.exists' => 'The selected user does not exist.',
            'block_id.exists' => 'The selected block does not exist.',
            'phase_id.exists' => 'The selected phase does not exist.',
            'form_type.exists' => 'The selected application type does not exist.',
        ];
    }
}
