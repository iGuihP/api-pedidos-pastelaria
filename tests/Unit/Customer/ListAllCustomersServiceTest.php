<?php

use App\Models\CustomerModel;
use Tests\TestCase;
use App\Services\Customer\ListAllCustomersService;
use App\Repositories\CustomerRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ListAllCustomersServiceTest extends TestCase
{
    protected $customerRepository;
    protected $listAllCustomersService;
    protected $clientRequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->listAllCustomersService = new ListAllCustomersService($this->customerRepository);

        $this->clientRequest = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

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

    public function testListAllCustomersEndpoint() {
        CustomerModel::factory()->create();
        $response = $this->clientRequest->get('/api/customer');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertGreaterThan(0, count($data['data']), 'A resposta deve conter mais de um elemento no array');
    }
}
