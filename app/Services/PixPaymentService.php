<?php

namespace App\Services;

use Core\Domain\Application\Payments\PaymentFactory;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Entities\PixPayment;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Enterprise\Dtos\PaymentUpdateDto;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class PixPaymentService
{
    private $httpHeaders;

    public function __construct()
    {
        $this->httpHeaders = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => Config::get('services.asaas.token')
        ];
    }

    public function processPayment(PixPayment $payment)
    {
        $requestAttributes = [
            'customer' => $payment->customerId,
            'billingType' => PaymentMethods::PIX->value,
            'value' => $payment->value,
            'dueDate' => $payment->dueDate,
            'description' => 'Pagamento via pix',
        ];

        $apiUrl = Config::get('services.asaas.url');

        $response = Http::withHeaders($this->httpHeaders)
            ->post("$apiUrl/payments", $requestAttributes);

        if($response->failed()) {
            return response()->json(['error' => 'Failed to process payment'], 500);
        }

        $responseData = $response->json();

        return $responseData;

    }

    public function getPixData(string $externalId)
    {
        $apiUrl = Config::get('services.asaas.url');

        $response = Http::withHeaders($this->httpHeaders)
            ->post("$apiUrl/payments/{$externalId}/pixQrCode");

        if($response->failed()) {
            return response()->json(['error' => 'Failed to get pixQrCode'], 500);
        }

        $pixData = $response->json();

        if($pixData['success'] === false) {
            return response()->json(['error' => 'Failed to get pixQrCode'], 500);
        }

        return $pixData;
    }
}
