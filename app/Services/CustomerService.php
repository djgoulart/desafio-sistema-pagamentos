<?php

namespace App\Services;

use Core\Domain\Enterprise\Entities\Customer;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Application\Contracts\CustomerContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CustomerService implements CustomerContract
{
    public function createCustomer(CustomerDto $customer)
    {
        $apiUrl = Config::get('services.asaas.url');

        //$customerData = $customer->getData();

        $requestData = [
            'name' => $customer->name,
            'cpfCnpj' => $customer->cpfCnpj,
        ];

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => Config::get('services.asaas.token')
        ])->post("$apiUrl/customers", $requestData);

        if($response->successful()) {
            $respData = $response->json();
            $customerData = new CustomerDto(
                externalId: $respData['id'],
                name: $respData['name'],
                cpfCnpj: $respData['cpfCnpj'],
            );

            $customerFromDomain = new Customer(customerData: $customerData);
            return $customerFromDomain->getData();
        }

        if($response->failed()) {
            $respData = $response->json();
            throw new \Exception($respData['errors'][0]['description']);
        }


        // Aqui, você pode adicionar mais lógica como salvar o pagamento no banco de dados,
        // enviar notificações, etc., dependendo dos requisitos da sua aplicação.

    }
}
