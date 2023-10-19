<?php

namespace App\Services\Customer;

use App\Repositories\CustomerRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class FindCustomerService
{
    private $customerRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function find(array $params) {
        Log::info("Running the service to find a customer by filters.", $params);
        
        $customerFound = $this->findCustomerByFilters(
            $params['email'] ?? null,
            $params['name'] ?? null
        );

        return $customerFound;
    }

    private function findCustomerByFilters(
        string $email = null,
        string $name = null
    ) {
        $customerFound = $this->customerRepository->findByFilters($email, $name);
        if (!$customerFound) {
            throw new Exception("Customer not found.", 404);
        }

        return $customerFound;
    }
}
