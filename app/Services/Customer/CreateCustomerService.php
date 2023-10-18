<?php

namespace App\Customer\Services;

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
        
        $this->checkIfCustomerExistsByEmail($params['email']);
        $createdCustomer = $this->createCustomer($params);

        return $createdCustomer->id;
    }

    /**
     * Checks if a customer exists in the database by email.
     *
     * @param string $email The email of the customer to check.
     * @throws Exception If the customer already exists in the database.
     * @return void
     */
    private function checkIfCustomerExistsByEmail(string $email): void {
        $customerFound = $this->customerRepository->checkIfAlreadyExistsByEmail($email);
        if ($customerFound) {
            throw new Exception("Customer already existing in the database", 409);
        }
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
