<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreditCardPaymentRequest;
use App\Repositories\CreditCardPaymentEloquentRepository;
use App\Services\CreditCardPaymentService;
use Core\Domain\Application\Contracts\CustomerContract;
use Core\Domain\Enterprise\Dtos\CreditCardDto;
use Core\Domain\Enterprise\Dtos\CreditCardHolderInfoDto;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Entities\CreditCardPayment;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PayWithCreditCardController extends Controller
{
    protected $paymentProcessor;

    protected $customerService;

    protected $paymentRepository;

    public function __construct(
        CreditCardPaymentService $paymentProcessor,
        CustomerContract $customerService,
        CreditCardPaymentEloquentRepository $paymentRepository
    ) {
        $this->paymentProcessor = $paymentProcessor;
        $this->customerService = $customerService;
        $this->paymentRepository = $paymentRepository;
    }

    public function handle(CreditCardPaymentRequest $request): RedirectResponse
    {
        $customerData = new CustomerDto(
            name: $request->input('customerName'),
            cpfCnpj: $request->input('customerCpfCnpj'),
        );

        $customer = $this->customerService->createCustomer($customerData);

        if (! $customer) {
            return redirect(route('payment.register'))->with('error', 'Failed to create customer');
        }

        $paymentData = new PaymentDto(
            customerId: $customer['externalId'],
            value: $request->input('value'),
            dueDate: $request->input('dueDate'),
            remoteIp: $request->ip(),
            paymentMethod: PaymentMethods::CREDIT_CARD->value,
        );

        $creditCard = new CreditCardDto(
            holderName: $request->input('cardHolderName'),
            number: str_replace(' ', '', $request->input('cardNumber')),
            expiryMonth: $request->input('cardExpiryMonth'),
            expiryYear: $request->input('cardExpiryYear'),
            ccv: $request->input('cardCvv'),
        );

        $creditCardHolderInfo = new CreditCardHolderInfoDto(
            name: $request->input('cardHolderName'),
            cpfCnpj: $request->input('holderInfoCpfCnpj'),
            postalCode: $request->input('holderInfoPostalCode'),
            addressComplement: $request->input('holderInfoAddressComplement'),
            addressNumber: $request->input('holderInfoAddressNumber'),
            email: $request->input('holderInfoEmail'),
            phone: $request->input('holderInfoPhone'),
        );

        $paymentToPersist = new CreditCardPayment(
            payment: $paymentData,
            creditCard: $creditCard,
            creditCardHolderInfo: $creditCardHolderInfo,
        );

        $persistedPayment = $this->paymentRepository->create(payment: $paymentToPersist);

        $serviceResponse = $this->paymentProcessor->processPayment(
            payment: $persistedPayment
        );

        $this->paymentRepository->update(
            payment: $persistedPayment,
            data: [
                'status' => $serviceResponse['status'],
                'externalId' => $serviceResponse['id'],
                'invoiceUrl' => $serviceResponse['invoiceUrl'],
                'transactionReceiptUrl' => $serviceResponse['transactionReceiptUrl'],
            ]
        );

        return redirect()->route('payment.pay.credit-card.result', [
            'paymentId' => $persistedPayment->id,
        ]);

    }

    public function result($paymentId): Response
    {
        $payment = $this->paymentRepository->findById($paymentId);

        return Inertia::render('Payment/CreditCardResult', [
            'invoiceUrl' => $payment['invoiceUrl'],
            'status' => $payment['status'],
        ]);
    }
}
