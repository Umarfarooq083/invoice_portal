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
            'form_no'           => ['required', 'string', 'max:255'],
            'client_name'       => ['required', 'string', 'max:255'],
            'client_cnic'       => ['required', 'string', 'max:20'],
            'tracking_code'     => ['nullable', 'string', 'max:255', 'unique:forms,tracking_code'],
            'size'              => ['required', 'string', 'max:100'],
            'reg_type'          => ['required', 'string', 'max:100'],
            'address'           => ['required', 'string'],
            'office_id'         => ['required', 'integer'],
            'box_no'            => ['required', 'integer'],
            'sr_no'             => ['required', 'integer'],
            'user_id'           => ['required', 'integer', 'exists:users,id'],
            'down_payment'      => ['required', 'integer', 'min:0'],
            'society_id'        => ['required', 'integer'],
            'contact'           => ['required', 'string', 'max:20'],
            'deposite_slip_no'  => ['required', 'string', 'max:255'],
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

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'form_no.required'          => 'Form number is required.',
            'client_name.required'      => 'Client name is required.',
            'client_cnic.required'      => 'Client CNIC is required.',
            'tracking_code.unique'      => 'This tracking code already exists.',
            'size.required'             => 'Plot size is required.',
            'reg_type.required'         => 'Registration type is required.',
            'address.required'          => 'Address is required.',
            'office_id.required'        => 'Please select an office.',
            'down_payment.required'     => 'Down payment amount is required.',
            'society_id.required'       => 'Please select a society.',
            'contact.required'          => 'Contact number is required.',
            'deposite_slip_no.required' => 'Deposit slip number is required.',
            'user_id.exists'            => 'The selected user does not exist.',
        ];
    }
}
