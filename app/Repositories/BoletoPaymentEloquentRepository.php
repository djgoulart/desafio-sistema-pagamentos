<?php

namespace App\Repositories;

use App\Models\Payment as PaymentModel;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Entities\BoletoPayment;

class BoletoPaymentEloquentRepository
{
    public function create(BoletoPayment $payment)
    {
        $persisted = PaymentModel::create([
            'customerId' => $payment->customerId,
            'value' => number_format($payment->value, 2, '.', ''),
            'dueDate' => $payment->dueDate,
            'description' => 'Pagamento de fatura',
            'status' => $payment->status,
            'boletoUrl' => null,
        ]);

        $payment->setId($persisted->id);

        return $payment;
    }

    public function update(BoletoPayment $payment, array $data)
    {
        $updated = PaymentModel::where('id', $payment->id)->update($data);

        if ($updated) {
            return true;
        }

        return false;
    }

    public function findById(string $id): BoletoPayment
    {
        $persisted = PaymentModel::find($id)->attributesToArray();

        if (! $persisted) {
            throw new \Exception('Payment not found');
        }

        $data = new PaymentDto(
            id: $persisted['id'],
            externalId: $persisted['externalId'],
            customerId: $persisted['customerId'],
            value: $persisted['value'],
            dueDate: $persisted['dueDate'],
            invoiceUrl: $persisted['invoiceUrl'],
            remoteIp: $persisted['remoteIp'],
        );

        return new BoletoPayment(payment: $data, boletoUrl: $persisted['boletoUrl']);

    }
}
