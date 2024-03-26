<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Dtos\CreditCardDto;
use Core\Domain\Enterprise\Dtos\CreditCardHolderInfoDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\ValueObjects\CreditCard;

class CreditCardPayment extends Payment
{
    public function __construct(
        protected PaymentDto $payment,
        protected CreditCard|CreditCardDto $creditCard,
        protected CreditCardHolderInfoDto|CreditCardHolder $creditCardHolderInfo,
    ) {
        parent::__construct(paymentAttributes: $payment);

        if ($creditCard instanceof CreditCardDto) {
            $this->creditCard = new CreditCard(
                holderName: $creditCard->holderName,
                number: $creditCard->number,
                expiryMonth: $creditCard->expiryMonth,
                expiryYear: $creditCard->expiryYear,
                ccv: $creditCard->ccv,
            );
        } else {
            $this->creditCard = $creditCard;
        }

        if ($creditCardHolderInfo instanceof CreditCardHolderInfoDto) {
            $this->creditCardHolderInfo = new CreditCardHolder(
                name: $creditCardHolderInfo->name,
                email: $creditCardHolderInfo->email,
                cpfCnpj: $creditCardHolderInfo->cpfCnpj,
                postalCode: $creditCardHolderInfo->postalCode,
                addressNumber: $creditCardHolderInfo->addressNumber,
                addressComplement: $creditCardHolderInfo->addressComplement,
                phone: $creditCardHolderInfo->phone,
            );
        } else {
            $this->creditCardHolderInfo = $creditCardHolderInfo;
        }

        $this->validate();
    }

    public function getCreditCard()
    {
        return $this->creditCard->getData();
    }

    public function getHolderInfo()
    {
        return $this->creditCardHolderInfo->getData();
    }

    protected function validate()
    {
        parent::validate();
    }
}
