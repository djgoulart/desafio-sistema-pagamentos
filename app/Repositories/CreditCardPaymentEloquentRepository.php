<?php

namespace App\Repositories;

use App\Models\Payment as PaymentModel;
use Core\Domain\Enterprise\Entities\CreditCardPayment;

class CreditCardPaymentEloquentRepository
{
    public function create(CreditCardPayment $payment)
    {
        $persisted = PaymentModel::create([
            'customerId' => $payment->customerId,
            'value' => number_format($payment->value, 2, '.', ''),
            'dueDate' => $payment->dueDate,
            'description' => 'Pagamento com cartão de crédito',
            'status' => $payment->status,
        ]);

        $payment->setId($persisted->id);

        return $payment;
    }

    public function update(CreditCardPayment $payment, array $data)
    {
        $updated = PaymentModel::where('id', $payment->id)->update($data);

        if ($updated) {
            return true;
        }

        return false;
    }

    public function findById(string $id)
    {
        $persisted = PaymentModel::find($id)->attributesToArray();

        if (! $persisted) {
            throw new \Exception('Payment not found');
        }

        return $persisted;
    }
}
