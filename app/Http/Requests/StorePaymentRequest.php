<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Core\Domain\Enterprise\Enums\PaymentMethods;

class StorePaymentRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'paymentMethod' => [
                'required',
                Rule::enum(PaymentMethods::class),
            ],
            'value' => ['required', 'numeric']
        ];
    }
}
