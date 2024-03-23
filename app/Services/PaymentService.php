<?php

namespace App\Services;

use Core\Domain\Application\Payments\PaymentFactory;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Entities\Payment;
use Core\Domain\Application\Contracts\PaymentProcessor;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class PaymentService implements PaymentProcessor
{
    public function processPayment(string $method, PaymentDetailsDto $details)
    {
        $apiUrl = Config::get('services.asaas.url');

        if (!PaymentMethods::tryFrom($method)) {
            return response()->json(['error' => 'Invalid payment method'], 400);
        }

        $paymentMethod = PaymentMethods::from($method);

        $payment = PaymentFactory::createPayment($paymentMethod, $details);
       // dd($payment->getHolderInfo());
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => Config::get('services.asaas.token')
        ])->post("$apiUrl/payments", [
            'customer' => $payment->customerId,
            'billingType' => $paymentMethod->value,
            'value' => $payment->value,
            'dueDate' => $payment->dueDate,
            'description' => 'Pagamento de fatura',
            'remoteIp' => $payment->remoteIp,
            'creditCard' => $payment->getCreditCard(),
            'creditCardHolderInfo' => $payment->getHolderInfo()
        ]);

        return $response->object();

        // Aqui, você pode adicionar mais lógica como salvar o pagamento no banco de dados,
        // enviar notificações, etc., dependendo dos requisitos da sua aplicação.

    }
}
