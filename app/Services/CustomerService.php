<?php

namespace App\Services;

use Core\Domain\Enterprise\Entities\Customer;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Application\Contracts\CustomerContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use App\Models\Customer as CustomerModel;

class CustomerService implements CustomerContract
{
    private function findCustomerByCpfCnpj($cpfCnpj)
    {
        $customerPersisted = CustomerModel::where('cpfCnpj', preg_replace('/\D/', '', $cpfCnpj))->first();

        if($customerPersisted) {
            $customerData = new CustomerDto(
                id: $customerPersisted->id,
                externalId: $customerPersisted->externalId,
                name: $customerPersisted->name,
                cpfCnpj: $customerPersisted->cpfCnpj,
                createdAt: $customerPersisted->created_at,
            );

            $customer = new Customer(customerData: $customerData);
            return $customer->getData();
        }

        return null;
    }

    public function createCustomer(CustomerDto $customer)
    {
        $customerAlreadyExists = $this->findCustomerByCpfCnpj($customer->cpfCnpj);

        if($customerAlreadyExists) {
            return $customerAlreadyExists;
        }

        $apiUrl = Config::get('services.asaas.url');
        $accessToken = Config::get('services.asaas.token');

        if ($apiUrl === null || $accessToken === null) {
            throw new \Exception('API configurations are not set');
        }

        $requestData = [
            'name' => $customer->name,
            'cpfCnpj' => $customer->cpfCnpj,
        ];

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'access_token' => $accessToken
        ])->post("$apiUrl/customers", $requestData);

        if($response->successful()) {
            $respData = $response->json();
            $customerData = new CustomerDto(
                externalId: $respData['id'],
                name: $respData['name'],
                cpfCnpj: $respData['cpfCnpj'],
            );

            $customerFromDomain = new Customer(customerData: $customerData);

            $persistedCustomer = CustomerModel::create($customerFromDomain->getData());

            $customerFromDomain->setExternalId($persistedCustomer->externalId);
            return $customerFromDomain;
        }

        if($response->failed()) {
            $respData = $response->json();
            throw new \Exception($respData['errors'][0]['description']);
        }

    }
}
