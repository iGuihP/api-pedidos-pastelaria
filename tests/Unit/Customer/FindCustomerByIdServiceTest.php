<?php

use PHPUnit\Framework\TestCase;
use App\Services\Customer\FindCustomerByIdService;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FindCustomerByIdServiceTest extends TestCase
{
    protected $customerRepository;
    protected $findCustomerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->findCustomerService = new FindCustomerByIdService($this->customerRepository);

        Log::shouldReceive('info');
    }

    public function testFindCustomerById()
    {
        $customerId = 1;

        $customer = (object)['id' => $customerId];
        $this->customerRepository->expects($this->once())
            ->method('findById')
            ->with($customerId)
            ->willReturn($customer);

        $result = $this->findCustomerService->find($customerId);

        $this->assertEquals($customer, $result);
    }

    public function testFindCustomerByIdNotFound()
    {
        $customerId = 1;

        $this->customerRepository->expects($this->once())
            ->method('findById')
            ->with($customerId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Customer not found.");
        $this->expectExceptionCode(404);

        $this->findCustomerService->find($customerId);
    }
}
