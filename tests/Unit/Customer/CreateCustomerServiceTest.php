<?php

use PHPUnit\Framework\TestCase;
use App\Services\Customer\CreateCustomerService;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CreateCustomerServiceTest extends TestCase
{
    protected $customerRepository;
    protected $createCustomerService;

    public function setUp(): void
    {
        parent::setUp();

        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->createCustomerService = new CreateCustomerService($this->customerRepository);

        Log::shouldReceive('info');
    }

    public function testCreateCustomer()
    {
        $params = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'telephone' => '123-456-7890',
            'birth' => '1990-01-01',
            'address' => '123 Main St',
            'complement' => 'Apt 4B',
            'neighborhood' => 'Downtown',
            'zipcode' => '12345678',
        ];

        $createdCustomer = (object)['id' => 1];
        $this->customerRepository->expects($this->once())
            ->method('create')
            ->with(
                $params['name'],
                $params['email'],
                $params['telephone'],
                $params['birth'],
                $params['address'],
                $params['complement'],
                $params['neighborhood'],
                $params['zipcode']
            )
            ->willReturn($createdCustomer);

        $customerId = $this->createCustomerService->create($params);

        $this->assertEquals($createdCustomer->id, $customerId);
    }
}
