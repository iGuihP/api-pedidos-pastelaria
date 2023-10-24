<?php

use App\Models\CustomerModel;
use Tests\TestCase;
use App\Services\Customer\FindCustomerService;
use App\Repositories\CustomerRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class FindCustomerByFiltersServiceTest extends TestCase
{
    
    protected $customerRepository;
    protected $findCustomerService;
    protected $clientRequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->findCustomerService = new FindCustomerService($this->customerRepository);
        $this->clientRequest = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

        Log::shouldReceive('info');
    }

    public function testFindCustomerByFilterName()
    {
        $params = [
            'email' => '',
            'name' => 'name',
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
            'email' => 'email@example.com',
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

    public function testFindCustomerByFiltersEndpoint() {
        $customer = CustomerModel::factory()->create();
        $response = $this->clientRequest->get('/api/customer/filters', [
            'query' => [
                'name' => $customer->name,
                'email' => $customer->email,
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertGreaterThan(0, count($data['data']), 'A resposta deve conter mais de um elemento no array');
    }

    public function testFindCustomerNotFoundByFiltersEndpoint() {
        $response = $this->clientRequest->get('/api/customer/filters', [
            'query' => [
                'name' => 'user_not_found',
                'email' => 'email_not_found@example.com',
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertCount(0, $data['data'], 'A resposta do data deve estar vazia');
    }
}
