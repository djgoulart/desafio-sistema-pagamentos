<?php

namespace App\Services;

use Core\Domain\Application\Payments\PaymentFactory;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Entities\CreditCardPayment;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Enterprise\Dtos\PaymentUpdateDto;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CreditCardPaymentService
{

    public function processPayment(CreditCardPayment $payment)
    {
        //dd($payment);
        $requestAttributes = [
            'customer' => $payment->customerId,
            'billingType' => PaymentMethods::CREDIT_CARD->value,
            'value' => $payment->value,
            'dueDate' => $payment->dueDate,
            'description' => 'Pagamento via cartão de crédito',
            'creditCard' => $payment->getCreditCard(),
            'creditCardHolderInfo' => $payment->getHolderInfo(),
            'remoteIp' => $payment->remoteIp,
        ];

        $apiUrl = Config::get('services.asaas.url');

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => Config::get('services.asaas.token')
        ])->post("$apiUrl/payments", $requestAttributes);

        if($response->failed()) {
            return response()->json(['error' => 'Failed to process payment'], 500);
        }

        $responseData = $response->json();

        return $responseData;

    }
}
