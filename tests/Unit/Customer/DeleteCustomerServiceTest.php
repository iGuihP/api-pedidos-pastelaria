<?php

use App\Models\CustomerModel;
use Tests\TestCase;
use App\Services\Customer\DeleteCustomerService;
use App\Repositories\CustomerRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class DeleteCustomerServiceTest extends TestCase
{
    protected $customerRepository;
    protected $deleteCustomerService;
    protected $requestClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->deleteCustomerService = new DeleteCustomerService($this->customerRepository);
        $this->requestClient = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

        Log::shouldReceive('info');
    }

    public function testDeleteCustomer()
    {
        $customerId = 1;

        $customer = (object)['id' => $customerId];
        $this->customerRepository->expects($this->once())
            ->method('findById')
            ->with($customerId)
            ->willReturn($customer);

        $this->customerRepository->expects($this->once())
            ->method('delete')
            ->with($customer);

        $this->deleteCustomerService->delete($customerId);
    }

    public function testDeleteCustomerNotFound()
    {
        $customerId = 1;

        $this->customerRepository->expects($this->once())
            ->method('findById')
            ->with($customerId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Customer not found.");
        $this->expectExceptionCode(404);

        $this->deleteCustomerService->delete($customerId);
    }

    public function testDeleteCustomerEndpoint() {
        $customer = CustomerModel::factory()->create();
        $response = $this->requestClient->delete('/api/customer/' . $customer->id);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
