import React, { useEffect } from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import TextInput from '@/Components/TextInput';

export default function PixForm() {
    const paymentMethod = route().params.paymentMethod;
    const { data, setData, post, processing, errors, reset } = useForm({
        customerName: '',
        customerCpfCnpj: '',
        paymentMethod,
        value: '',
        dueDate: new Date().toISOString().split('T')[0]
    });


    const submit = (e) => {
        e.preventDefault();

        setData('paymentMethod', paymentMethod);

        post(route('payment.pay.pix'));
    };

    return (
        <form onSubmit={submit} className='flex flex-col gap-4'>
            <TextInput name={"paymentMethod"} type={"hidden"} value={data.paymentMethod} />

            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="customerName" value="Nome" />
                <TextInput
                    id="customerName"
                    name="customerName"
                    type={"text"}
                    value={data.customerName}
                    onChange={(e) => setData('customerName', e.target.value)} />

                <InputError message={errors.customerName} className="mt-2" />
            </div>

            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="customerCpfCnpj" value="Cpf/Cnpj" />
                <TextInput
                    id="customerCpfCnpj"
                    name="customerCpfCnpj"
                    type={"text"}
                    value={data.customerCpfCnpj}
                    onChange={(e) => setData('customerCpfCnpj', e.target.value)} />

                <InputError message={errors.customerCpfCnpj} className="mt-2" />
            </div>

            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="value" value="Value" />
                <TextInput
                    id="value"
                    name="value"
                    type={"text"}
                    value={data.value}
                    onChange={(e) => setData('value', e.target.value)} />

                <InputError message={errors.value} className="mt-2" />
            </div>
            <div className='flex flex-col gap-2'>
                <InputLabel htmlFor="dueDate" value="Data de Vencimento" />
                <TextInput
                    id="dueDate"
                    name="dueDate"
                    type={"date"}
                    value={data.dueDate}
                    onChange={(e) => setData('dueDate', e.target.value)}
                    disabled
                    className={"disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"}
                />

                <InputError message={errors.dueDate} className="mt-2" />
            </div>


            <div className="flex items-center justify-end mt-4">

                <PrimaryButton className="ms-4" disabled={processing}>
                    Pagar com Pix
                </PrimaryButton>
            </div>
            <InputError message={errors.paymentMethod} className="mt-2" />
        </form>
    );
}
