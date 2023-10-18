<?php

namespace App\Customer\Services;

use App\Repositories\CustomerRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class DeleteCustomerService
{
    private $customerRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function delete(int $customerId): void {
        Log::info("Running the service to delete a customer ID: ", $customerId);
        
        $customerFound = $this->findCustomerById($customerId);
        $this->deleteCustomer($customerFound);
    }

    private function findCustomerById(int $id) {
        $customerFound = $this->customerRepository->findById($id);
        if (!$customerFound) {
            throw new Exception("Customer not found.", 404);
        }
        return $customerFound;
    }

    private function deleteCustomer($customer) {
        $deletedCustomer = $this->customerRepository->delete(
            $customer,
        );

        return $deletedCustomer;
    }
}
