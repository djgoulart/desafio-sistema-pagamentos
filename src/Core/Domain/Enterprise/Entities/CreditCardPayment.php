<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Entities\Traits\EntityTrait;
use Core\Domain\Enterprise\ValueObjects\Uuid;
use Core\Domain\Enterprise\Validation\EntityValidation;
use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\ValueObjects\CreditCard;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Dtos\CreditCardDto;
use Core\Domain\Enterprise\Dtos\CreditCardHolderInfoDto;
use Core\Domain\Enterprise\Entities\CreditCardHolder;
use Datetime;

class CreditCardPayment extends Payment
{
    public function __construct(
        protected PaymentDto $payment,
        protected CreditCard | CreditCardDto $creditCard,
        protected CreditCardHolderInfoDto | CreditCardHolder $creditCardHolderInfo,
        protected string $remoteIp = '',
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

        if($creditCardHolderInfo instanceof CreditCardHolderInfoDto) {
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


    protected function validate()
    {
        parent::validate();

        EntityValidation::notNull($this->remoteIp, 'Remote IP should not be null');
    }
}
