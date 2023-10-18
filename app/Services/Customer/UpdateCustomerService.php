<?php

namespace App\Customer\Services;

use App\Repositories\CustomerRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateCustomerService
{
    private $customerRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function update(int $customerId, array $data): void {
        Log::info("Running the service to update a customer.", $data);
        
        $customerFound = $this->findCustomerById($customerId);
        $this->updateCustomer($customerFound, $data);
    }

    private function findCustomerById(int $id) {
        $customerFound = $this->customerRepository->findById($id);
        if (!$customerFound) {
            throw new Exception("Customer not found.", 404);
        }
        return $customerFound;
    }

    private function updateCustomer($customer, array $data) {
        $updatedCustomer = $this->customerRepository->update(
            $customer,
            $data
        );

        return $updatedCustomer;
    }
}
