<?php

namespace App\Services\Customer;

use App\Repositories\CustomerRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateCustomerService
{
    private $customerRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Creates a new customer.
     *
     * @param array $params The parameters for creating a new customer.
     * @return int The ID of the newly created customer.
     */
    public function create(array $params): int {
        Log::info("Running the service to create a new customer.", $params);
        
        $createdCustomer = $this->createCustomer($params);

        return $createdCustomer->id;
    }

    /**
     * Creates a new customer.
     *
     * @param array $params An array containing the customer's information:
     * @return mixed The created customer.
     */
    private function createCustomer(array $params) {
        $createdCustomer = $this->customerRepository->create(
            $params['name'],
            $params['email'],
            $params['telephone'],
            $params['birth'],
            $params['address'],
            $params['complement'],
            $params['neighborhood'],
            $params['zipcode']
        );

        return $createdCustomer;
    }
}
