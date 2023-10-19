<?php

namespace App\Services\Customer;

use App\Repositories\CustomerRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class FindCustomerByIdService
{
    private $customerRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function find(int $id) {
        Log::info("Running the service to find customer by ID: ". $id);
        return $this->findById($id);
    }

    private function findById(int $id) {
        $customersFound = $this->customerRepository->findById($id);
        if (!$customersFound) {
            throw new Exception("Customer not found.", 404);
        }

        return $customersFound;
    }
}
