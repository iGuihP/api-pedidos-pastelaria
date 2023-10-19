<?php

use PHPUnit\Framework\TestCase;
use App\Services\Customer\FindCustomerService;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FindCustomerByFilterServiceTest extends TestCase
{
    protected $customerRepository;
    protected $findCustomerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->findCustomerService = new FindCustomerService($this->customerRepository);

        Log::shouldReceive('info');
    }

    public function testFindCustomerByFilterName()
    {
        $params = [
            'email' => '',
            'name' => 'John Doe',
        ];

        $customer = (object)['id' => 1];
        $this->customerRepository->expects($this->once())
            ->method('findByFilters')
            ->with($params['email'], $params['name'])
            ->willReturn($customer);

        $result = $this->findCustomerService->find($params);

        $this->assertEquals($customer, $result);
    }

    public function testFindCustomerByFilterEmail()
    {
        $params = [
            'email' => 'john@example.com',
            'name' => '',
        ];

        $customer = (object)['id' => 1];
        $this->customerRepository->expects($this->once())
            ->method('findByFilters')
            ->with($params['email'], $params['name'])
            ->willReturn($customer);

        $result = $this->findCustomerService->find($params);

        $this->assertEquals($customer, $result);
    }

    public function testFindCustomerByFiltersNotFound()
    {
        $params = [
            'email' => 'jane@example.com',
            'name' => 'Jane Smith',
        ];

        $this->customerRepository->expects($this->once())
            ->method('findByFilters')
            ->with($params['email'], $params['name'])
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Customer not found.");
        $this->expectExceptionCode(404);

        $this->findCustomerService->find($params);
    }

    public function testFindCustomerByFiltersWithMissingParameters()
    {
        $params = [];

        $customer = (object)['id' => 1];
        $this->customerRepository->expects($this->once())
            ->method('findByFilters')
            ->with(null, null)
            ->willReturn($customer);

        $result = $this->findCustomerService->find($params);

        $this->assertEquals($customer, $result);
    }
}
