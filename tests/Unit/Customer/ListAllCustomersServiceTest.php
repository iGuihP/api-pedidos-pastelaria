<?php

use PHPUnit\Framework\TestCase;
use App\Services\Customer\ListAllCustomersService;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ListAllCustomersServiceTest extends TestCase
{
    protected $customerRepository;
    protected $listAllCustomersService;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->listAllCustomersService = new ListAllCustomersService($this->customerRepository);

        Log::shouldReceive('info');
    }

    public function testListAllCustomers()
    {
        $customers = [(object)['id' => 1], (object)['id' => 2]];
        $this->customerRepository->expects($this->once())
            ->method('listAll')
            ->willReturn($customers);

        $result = $this->listAllCustomersService->list();

        $this->assertEquals($customers, $result);
    }

    public function testListAllCustomersNotFound()
    {
        $this->customerRepository->expects($this->once())
            ->method('listAll')
            ->willReturn([]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Customer not found.");
        $this->expectExceptionCode(404);

        $this->listAllCustomersService->list();
    }
}
