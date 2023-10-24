
<?php

use App\Models\CustomerModel;
use Tests\TestCase;
use App\Services\Customer\UpdateCustomerService;
use App\Repositories\CustomerRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UpdateCustomerServiceTest extends TestCase
{
    protected $customerRepository;
    protected $updateCustomerService;
    protected $requestClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->updateCustomerService = new UpdateCustomerService($this->customerRepository);

        $this->requestClient = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

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

    public function testDeleteCustomerEndpoint() {
        $customer = CustomerModel::factory()->create();
        $response = $this->requestClient->put('/api/customer/' . $customer->id, [
            'json' => [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'telephone' => fake('pt_BR')->cellphone(),
                'birth' => fake()->date(),
                'address' => fake()->streetAddress(),
                'complement' => fake()->secondaryAddress(),
                'neighborhood' => fake()->streetSuffix(),
                'zipcode' => str_replace("-", "", fake('pt_BR')->postcode()),
            ]
        ]);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
