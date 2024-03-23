<?php

namespace App\Services;

use Core\Domain\Application\Payments\PaymentFactory;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Entities\Payment;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Core\Domain\Enterprise\Dtos\PaymentUpdateDto;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class PaymentService implements PaymentProcessor
{
    public function processPayment(string $method, PaymentDetailsDto $details)
    {

        if (!PaymentMethods::tryFrom($method)) {
            return response()->json(['error' => 'Invalid payment method'], 400);
        }

        $paymentMethod = PaymentMethods::from($method);

        $payment = PaymentFactory::createPayment($paymentMethod, $details);

        $requestAttributes = [
            'customer' => $payment->customerId,
            'billingType' => $paymentMethod->value,
            'value' => $payment->value,
            'dueDate' => $payment->dueDate,
            'description' => 'Pagamento de fatura',
        ];

        if($paymentMethod->value == PaymentMethods::CREDIT_CARD->value) {
            $requestAttributes['creditCard'] = $payment->getCreditCard();
            $requestAttributes['creditCardHolderInfo'] = $payment->getHolderInfo();
            $requestAttributes['remoteIp'] = $payment->remoteIp;
        }

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
       // dd($responseData);
        $updateData = new PaymentUpdateDto(
            status: $responseData['status'],
            externalId: $responseData['id'],
            invoiceUrl: $responseData['invoiceUrl'],
            transactionReceiptUrl: $responseData['transactionReceiptUrl']
        );

        $payment->updatePaymentDetails(
            updateData: $updateData
        );

        if($paymentMethod->value == PaymentMethods::BOLETO->value) {
            $payment->setBoletoUrl($responseData['bankSlipUrl']);
        }

        return $payment;

        // Aqui, você pode adicionar mais lógica como salvar o pagamento no banco de dados,
        // enviar notificações, etc., dependendo dos requisitos da sua aplicação.

    }
}
