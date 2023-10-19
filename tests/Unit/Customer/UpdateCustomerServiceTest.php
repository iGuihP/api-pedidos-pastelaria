
<?php

use PHPUnit\Framework\TestCase;
use App\Services\Customer\UpdateCustomerService;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UpdateCustomerServiceTest extends TestCase
{
    protected $customerRepository;
    protected $updateCustomerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->updateCustomerService = new UpdateCustomerService($this->customerRepository);

        // Suppress logging during tests
        Log::shouldReceive('info');
    }

    public function testUpdateCustomer()
    {
        $customerId = 1;
        $customerData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $customer = (object)['id' => $customerId];
        $this->customerRepository->expects($this->once())
            ->method('findById')
            ->with($customerId)
            ->willReturn($customer);

        $this->customerRepository->expects($this->once())
            ->method('update')
            ->with($customer, $customerData);

        $this->updateCustomerService->update($customerId, $customerData);
    }

    public function testUpdateCustomerNotFound()
    {
        $customerId = 1;
        $customerData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $this->customerRepository->expects($this->once())
            ->method('findById')
            ->with($customerId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Customer not found.");
        $this->expectExceptionCode(404);

        $this->updateCustomerService->update($customerId, $customerData);
    }
}
