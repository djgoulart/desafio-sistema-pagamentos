<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\PixPaymentRequest;
use App\Repositories\PixPaymentEloquentRepository;
use App\Services\PixPaymentService;
use Core\Domain\Application\Contracts\CustomerContract;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Entities\PixPayment;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PayWithPixController extends Controller
{
    protected $customerService;

    protected $paymentProcessor;

    protected $paymentRepository;

    public function __construct(
        CustomerContract $customerService,
        PixPaymentService $paymentProcessor,
        PixPaymentEloquentRepository $paymentRepository
    ) {
        $this->customerService = $customerService;
        $this->paymentProcessor = $paymentProcessor;
        $this->paymentRepository = $paymentRepository;
    }

    public function handle(PixPaymentRequest $request): RedirectResponse
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
            paymentMethod: PaymentMethods::PIX->value,
        );

        $paymentToPersist = new PixPayment(payment: $paymentData);

        $persistedPayment = $this->paymentRepository->create(payment: $paymentToPersist);

        $serviceResponse = $this->paymentProcessor->processPayment($persistedPayment);

        $pixDetails = $this->paymentProcessor->getPixData(externalId: $serviceResponse['id']);

        $this->paymentRepository->update(
            payment: $persistedPayment,
            data: [
                'status' => $serviceResponse['status'],
                'externalId' => $serviceResponse['id'],
                'invoiceUrl' => $serviceResponse['invoiceUrl'],
                'transactionReceiptUrl' => $serviceResponse['transactionReceiptUrl'],
                'pixQrCode' => $pixDetails['encodedImage'],
                'pixPayload' => $pixDetails['payload'],
            ]
        );

        $payment = $this->paymentRepository->findById($persistedPayment->id);

        return redirect(route('payment.pay.pix.result', ['paymentId' => $payment->id]));

    }

    public function result($paymentId): Response
    {
        $payment = $this->paymentRepository->findById($paymentId);

        return Inertia::render('Payment/PixResult', [
            'qrCode' => $payment->qrCode,
            'payload' => $payment->payload,
        ]);
    }
}
