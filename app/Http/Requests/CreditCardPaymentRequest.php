<?php

namespace App\Http\Requests;

use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreditCardPaymentRequest extends FormRequest
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
                    ->only([PaymentMethods::CREDIT_CARD]),
            ],
            'value' => ['required', 'numeric'],
            'cardHolderName' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'holderInfoCpfCnpj' => [
                'required',
                'string',
                'min:11',
                'max:14',
            ],
            'holderInfoPostalCode' => [
                'required',
                'string',
                'min:9',
                'max:9',
            ],
            'holderInfoAddressNumber' => [
                'required',
                'numeric',
            ],
            'holderInfoEmail' => [
                'required',
                'string',
                'email',
            ],
            'holderInfoPhone' => [
                'required',
                'string',
                'min:10',
                'max:14',
            ],
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
            'cardHolderName.required' => 'O campo nome do titular do cartão é obrigatório',
            'cardHolderName.min' => 'O campo nome do titular do cartão deve ter no mínimo 3 caracteres',
            'cardHolderName.max' => 'O campo nome do titular do cartão deve ter no máximo 100 caracteres',
            'holderInfoCpfCnpj.required' => 'O campo CPF/CNPJ do titular do cartão é obrigatório',
            'holderInfoCpfCnpj.min' => 'O campo CPF/CNPJ do titular do cartão deve ter no minimo 11 caracteres',
            'holderInfoCpfCnpj.max' => 'O campo CPF/CNPJ do titular do cartão deve ter no máximo 14 caracteres',
            'holderInfoPostalCode.required' => 'O campo CEP do titular do cartão é obrigatório',
            'holderInfoPostalCode.min' => 'O campo CEP do titular do cartão deve ter no minimo 9 caracteres',
            'holderInfoPostalCode.max' => 'O campo CEP do titular do cartão deve ter no máximo 9 caracteres',
            'holderInfoAddressNumber.required' => 'O campo número do endereço do titular do cartão é obrigatório',
            'holderInfoAddressNumber.numeric' => 'O campo número do endereço do titular do cartão deve ser um número',
            'holderInfoEmail.required' => 'O campo email do titular do cartão é obrigatório',
            'holderInfoEmail.email' => 'O campo email do titular do cartão deve ser um email válido',
            'holderInfoPhone.required' => 'O campo telefone do titular do cartão é obrigatório',
            'holderInfoPhone.min' => 'O campo telefone do titular do cartão deve ter no minimo 10 caracteres',
            'holderInfoPhone.max' => 'O campo telefone do titular do cartão deve ter no máximo 14 caracteres',

        ];
    }
}
