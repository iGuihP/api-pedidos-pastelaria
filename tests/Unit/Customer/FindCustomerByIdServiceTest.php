<?php

use App\Models\CustomerModel;
use Tests\TestCase;
use App\Traits\ClientRequestTrait;
use App\Services\Customer\FindCustomerByIdService;
use App\Repositories\CustomerRepositoryInterface;
use Database\Factories\CustomerModelFactory;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class FindCustomerByIdServiceTest extends TestCase
{
    use ClientRequestTrait;
    protected $customerRepository;
    protected $findCustomerService;
    protected $clientRequest;

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

    public function testFindCustomerByIdEndpoint() {
        $customer = CustomerModel::factory()->create();
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
        $response = $this->requestClient()->get('/api/customer/' . $customer->id);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('name', $data, 'A chave "name" deve estar na resposta');
    }
}
