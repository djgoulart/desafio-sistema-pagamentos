import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';

export default function BoletoResult({ invoiceUrl, boletoUrl }) {

    function handlePrintBoleto() {
        return window.location.href = boletoUrl;
    }

    function handleViewInvoice() {
        return window.location.href = invoiceUrl;
    }

    return (
        <GuestLayout>
            <Head title="Payment Done!" />

            <div className='flex flex-col gap-4'>
                <h1 className='text-lg font-semibold'>Pagamento Finalizado!</h1>

                Clique no botão abaixo para visualizar o boleto, ou no link para realizar um novo pagamento.

                <div className='flex items-center gap-4'>
                    <PrimaryButton onClick={handlePrintBoleto}>Imprimir Boleto</PrimaryButton>
                    <Link
                        href={route('payment.register', { paymentMethod: 'BOLETO' })}
                        as={"button"}
                        className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Fazer novo pagamento
                    </Link>
                </div>
            </div>

        </GuestLayout>
    );
}
