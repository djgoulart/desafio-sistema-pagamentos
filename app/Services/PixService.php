<?php

namespace App\Services;

use Core\Domain\Application\Contracts\PixProcessor;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Enterprise\Entities\Payment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class PixService implements PixProcessor
{
    public function getPixData(Payment $payment): Payment
    {
        $apiUrl = Config::get('services.asaas.url');

        $paymentData = $payment->getData();

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => Config::get('services.asaas.token'),
        ])->post("$apiUrl/payments/{$paymentData['externalId']}/pixQrCode");

        if($response->failed()) {
            return response()->json(['error' => 'Failed to get pixQrCode'], 500);
        }

        $pixData = $response->json();

        if($pixData['success'] === false) {
            return response()->json(['error' => 'Failed to get pixQrCode'], 500);
        }

        $payment->setPixData(
            qrCode: $pixData['encodedImage'],
            expirationDate: $pixData['expirationDate'],
            payload: $pixData['payload']
        );

        return $payment;
    }
}
