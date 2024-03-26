<?php

namespace App\Providers;

use App\Models\Payment as PaymentModel;
use Core\Domain\Application\Payments\PaymentFactory;
use Core\Domain\Enterprise\Entities\BoletoPayment;
use Core\Domain\Enterprise\Entities\CreditCardPayment;
use Core\Domain\Enterprise\Entities\PixPayment;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        PaymentFactory::registerPaymentMethod(PaymentMethods::CREDIT_CARD->value, function ($data) {
            if (! $data->creditCard || ! $data->remoteIp || ! $data->creditCardHolderInfo) {
                throw new InvalidArgumentException('Credit card data, holder info and remote IP are required');
            }

            new CreditCardPayment(
                payment: $data->payment,
                creditCard: $data->creditCard,
                creditCardHolderInfo: $data->creditCardHolderInfo,
                remoteIp: $data->remoteIp,
            );

            PaymentModel::create([
                'customerId' => $data->payment->customerId,
                // 'billing_type' => PaymentMethods::CREDIT_CARD->value,
                'value' => number_format($data->payment->value, 2, '.', ''),
                'dueDate' => $data->payment->dueDate,
                'description' => 'Pagamento de fatura',
                'status' => $data->payment->status,
                'invoiceUrl' => $data->payment->invoiceUrl,
                'transactionReceiptUrl' => $data->payment->transactionReceiptUrl,
                'remoteIp' => $data->remoteIp,
                'creditCardNumber' => $data->creditCard->number,
                'creditCardHolderName' => $data->creditCardHolderInfo->name,
                'creditCardHolderEmail' => $data->creditCardHolderInfo->email,
                'creditCardHolderPhone' => $data->creditCardHolderInfo->phone,
                'creditCardHolderCpfCnpj' => $data->creditCardHolderInfo->cpfCnpj,
                'creditCardHolderPostalCode' => $data->creditCardHolderInfo->postalCode,
                'creditCardHolderAddressNumber' => $data->creditCardHolderInfo->addressNumber,
                'creditCardHolderAddressComplement' => $data->creditCardHolderInfo->addressComplement,
                'boletoUrl' => null,
            ]);

        });

        PaymentFactory::registerPaymentMethod(PaymentMethods::BOLETO->value, function ($data) {
            $payment = new BoletoPayment(
                payment: $data->payment,
            );

            $paymentData = $payment->getData();
            // dd($paymentData);
            $persistedPayment = PaymentModel::create([
                'customerId' => $paymentData['customerId'],
                // 'billing_type' => PaymentMethods::CREDIT_CARD->value,
                'value' => number_format($paymentData['value'], 2, '.', ''),
                'dueDate' => $paymentData['dueDate'],
                'description' => 'Pagamento de fatura por boleto',
                'status' => $paymentData['status'],
                'invoiceUrl' => $paymentData['invoiceUrl'],
                'transactionReceiptUrl' => $paymentData['transactionReceiptUrl'],
                'remoteIp' => null,
                'boletoUrl' => null,
            ]);

            $payment->setId($persistedPayment->id);

            //dd($payment);
            return $payment;
        });

        PaymentFactory::registerPaymentMethod(PaymentMethods::PIX->value, function ($data) {
            new PixPayment(
                payment: $data->payment,
            );

            PaymentModel::create([
                'customerId' => $data->payment->customerId,
                // 'billing_type' => PaymentMethods::CREDIT_CARD->value,
                'value' => number_format($data->payment->value, 2, '.', ''),
                'dueDate' => $data->payment->dueDate,
                'description' => 'Pagamento de fatura',
                'status' => $data->payment->status,
                'invoiceUrl' => $data->payment->invoiceUrl,
                'transactionReceiptUrl' => $data->payment->transactionReceiptUrl,
                'remoteIp' => $data->remoteIp,
                'boletoUrl' => null,
            ]);
        });
    }
}
