<?php

namespace App\Http\Requests;

use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'max:100',
            ],
            'customerCpfCnpj' => [
                'required',
                'string',
                'min:11',
                'max:14',
            ],
            'paymentMethod' => [
                'required',
                Rule::enum(PaymentMethods::class)
                    ->only([PaymentMethods::BOLETO]),
            ],
            'value' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'customerName.required' => 'O campo nome é obrigatório',
            'customerName.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'customerName.max' => 'O campo nome deve ter no máximo 100 caracteres',
            'customerCpfCnpj.required' => 'O campo CPF/CNPJ é obrigatório',
            'customerCpfCnpj.min' => 'O campo CPF/CNPJ deve ter no minimo 11 caracteres',
            'customerCpfCnpj.max' => 'O campo CPF/CNPJ deve ter no máximo 14 caracteres',
            'paymentMethod.required' => 'O campo método de pagamento é obrigatório',
            'paymentMethod.enum' => 'O campo método de pagamento deve ser um valor válido',
            'value.required' => 'O campo valor é obrigatório',
            'value.numeric' => 'O campo valor deve ser um número',
        ];
    }
}
