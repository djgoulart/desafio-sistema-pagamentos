<?php

namespace App\Repositories;

use App\Models\Payment as PaymentModel;
use Core\Domain\Enterprise\Entities\BoletoPayment;
use Core\Domain\Application\Contracts\PaymentRepository;
use Core\Domain\Enterprise\Entities\Payment;
use Core\Domain\Enterprise\Dtos\PaymentDto;

class BoletoPaymentEloquentRepository
{
    public function create(BoletoPayment $payment)
    {
       // dd($payment);
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
       // dd($payment, $data);
        $updated = PaymentModel::where('id', $payment->id)->update($data);

        if($updated) {
            return true;
        }

        return false;
    }

    public function findById(string $id): BoletoPayment
    {
        $persisted = PaymentModel::find($id)->attributesToArray();

        if(!$persisted) {
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
        //dd($data);

        return new BoletoPayment(payment: $data, boletoUrl: $persisted['boletoUrl']);

    }

    public function findByExternalId(string $externalId): BoletoPayment
    {}
}

