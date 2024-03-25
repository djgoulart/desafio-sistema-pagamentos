<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoletoPaymentRequest;
use Core\Domain\Application\Contracts\CustomerContract;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Enums\PaymentMethods;
use Illuminate\Http\RedirectResponse;
use Core\Domain\Enterprise\Entities\BoletoPayment;
use App\Services\BoletoPaymentService;
use App\Repositories\BoletoPaymentEloquentRepository;
use Inertia\Inertia;
use Inertia\Response;

class PayWithBoletoController extends Controller
{
    public function __construct(
        protected BoletoPaymentService $paymentProcessor,
        protected CustomerContract $customerService,
        protected BoletoPaymentEloquentRepository $paymentRepository
    )
    {
        $this->paymentProcessor = $paymentProcessor;
        $this->customerService = $customerService;
        $this->paymentRepository = $paymentRepository;
    }

    public function handle(BoletoPaymentRequest $request): RedirectResponse
    {
        $customerData = new CustomerDto(
            name: $request->input('customerName'),
            cpfCnpj: $request->input('customerCpfCnpj'),
        );

        $customer = $this->customerService->createCustomer($customerData);

        if(!$customer) {
            return redirect(route('payment.register'))->with('error', 'Failed to create customer');
        }

        $paymentMethod = PaymentMethods::from($request->input('paymentMethod'));

        $paymentData = new PaymentDto(
            customerId: $customer['externalId'],
            value: $request->input('value'),
            dueDate: $request->input('dueDate'),
            remoteIp: $request->ip(),
        );

        $paymentToPersist = new BoletoPayment(payment: $paymentData);

        $persistedPayment = $this->paymentRepository->create(payment: $paymentToPersist);

        $serviceResponse = $this->paymentProcessor->processPayment($persistedPayment);

        $this->paymentRepository->update(
            payment: $persistedPayment,
            data: [
                'status' => $serviceResponse['status'],
                'externalId' => $serviceResponse['id'],
                'invoiceUrl' => $serviceResponse['invoiceUrl'],
                'transactionReceiptUrl' => $serviceResponse['transactionReceiptUrl'],
                'boletoUrl' => $serviceResponse['bankSlipUrl'],
            ]
        );

        $payment = $this->paymentRepository->findById($persistedPayment->id);

        return redirect(route('payment.pay.boleto.result', ['paymentId' => $payment->id]));
    }

    public function result($paymentId): Response
    {
        $payment = $this->paymentRepository->findById($paymentId);

        return Inertia::render('Payment/BoletoResult', [
            'invoiceUrl' => $payment->invoiceUrl,
            'boletoUrl' => $payment->boletoUrl,
        ]);
    }
}
