<?php

namespace App\Services;

use Core\Domain\Enterprise\Entities\BoletoPayment;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class BoletoPaymentService
{
    public function processPayment(BoletoPayment $payment)
    {
        $requestAttributes = [
            'customer' => $payment->customerId,
            'billingType' => PaymentMethods::BOLETO->value,
            'value' => $payment->value,
            'dueDate' => $payment->dueDate,
            'description' => 'Pagamento via boleto',
        ];

        $apiUrl = Config::get('services.asaas.url');

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => Config::get('services.asaas.token'),
        ])->post("$apiUrl/payments", $requestAttributes);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to process payment'], 500);
        }

        $responseData = $response->json();

        return $responseData;

    }
}
