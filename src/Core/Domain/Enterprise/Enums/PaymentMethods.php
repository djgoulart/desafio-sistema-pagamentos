<?php

namespace Core\Domain\Enterprise\Enums;

enum PaymentMethods: string
{
    case CREDIT_CARD = 'CREDIT_CARD';
    case BOLETO = 'BOLETO';
    case PIX = 'PIX';
}
