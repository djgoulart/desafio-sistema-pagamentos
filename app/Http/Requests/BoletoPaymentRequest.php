<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Core\Domain\Enterprise\Enums\PaymentMethods;

class BoletoPaymentRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customerName' => [
                'required',
                'string',
                'min:3',
                'max:100'
            ],
            'customerCpfCnpj' => [
                'required',
                'string',
                'min:11',
                'max:14'
            ],
            'paymentMethod' => [
                'required',
                Rule::enum(PaymentMethods::class)
                    ->only([PaymentMethods::BOLETO]),
            ],
            'value' => ['required', 'numeric']
        ];
    }
}
