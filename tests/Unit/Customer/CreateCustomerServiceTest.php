<?php

use Tests\TestCase;
use App\Traits\ClientRequestTrait;
use App\Services\Customer\CreateCustomerService;
use App\Repositories\CustomerRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CreateCustomerServiceTest extends TestCase
{
    use ClientRequestTrait;
    protected $customerRepository;
    protected $createCustomerService;
    protected $requestClient;

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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake('pt_BR')->cellphone(),
            'birth' => fake()->date(),
            'address' => fake()->streetAddress(),
            'complement' => fake()->secondaryAddress(),
            'neighborhood' => fake()->streetSuffix(),
            'zipcode' => str_replace("-", "", fake('pt_BR')->postcode()),
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

    public function testCreateCustomerEndpoint()
    {
        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake('pt_BR')->cellphone(),
            'birth' => fake()->date(),
            'address' => fake()->streetAddress(),
            'complement' => fake()->secondaryAddress(),
            'neighborhood' => fake()->streetSuffix(),
            'zipcode' => str_replace("-", "", fake('pt_BR')->postcode()),
        ];

        $response = $this->requestClient()->post('/api/customer', [
            'json' => $data,
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('newCustomerId', $responseData);
    }
}
