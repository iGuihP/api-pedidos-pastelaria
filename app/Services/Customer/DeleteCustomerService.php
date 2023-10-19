<?php

namespace App\Services\Customer;

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

    /**
     * Deletes a customer by their ID.
     *
     * @param int $customerId The ID of the customer to be deleted.
     * @return void
     */
    public function delete(int $customerId): void {
        Log::info("Running the service to delete a customer ID: ", ['customer_id' => $customerId]);
        
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

    private function deleteCustomer($customer): void {
        $this->customerRepository->delete($customer);
    }
}
