import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';

export default function PixResult({ qrCode, payload }) {

    return (
        <GuestLayout className="w-[1240px]">
            <Head title="Payment Done!" />

            <div className='w-full flex flex-col gap-4'>
                <h1 className='text-lg font-semibold'>QRCode para pagamento</h1>

                <img src={`data:image/png;base64,${qrCode}`} alt="QR Code" className='w-[400px] mx-auto' />
                <div className='flex flex-col max-w-[700px] mx-auto'>
                    <p className='text-md font-medium text-ellipsis'>Pix copia e cola:</p>
                    <p className='bg-gray-100 rounded-md p-2 text-sm text-center text-ellipsis max-w-[700px] mx-auto'>{payload}</p>
                </div>


                <div className='flex items-center gap-4'>
                    <Link
                        href={route('payment.register', { paymentMethod: 'PIX' })}
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
