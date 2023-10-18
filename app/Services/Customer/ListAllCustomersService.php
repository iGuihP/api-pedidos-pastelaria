<?php

namespace App\Customer\Services;

use App\Repositories\CustomerRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class ListAllCustomersService
{
    private $customerRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function list() {
        Log::info("Running the service to list all customers.");
        return $this->listAll();
    }

    private function listAll() {
        $customersFound = $this->customerRepository->listAll();
        if (!$customersFound) {
            throw new Exception("Customer not found.", 404);
        }

        return $customersFound;
    }
}
