<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'to_account_iban' => 'required|string|exists:accounts,iban',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
        ];
    }

    public function messages(): array
    {
        return [
            'to_account_id.exists' => 'The recipient account does not exist.',
            'amount.min' => 'The transfer amount must be at least 0.01.',
        ];
    }
}
