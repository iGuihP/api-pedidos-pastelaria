<?php

use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\ProductsOrderModel;
use Tests\TestCase;
use App\Services\Order\FindOrderByCustomerIdService;
use App\Repositories\OrderRepositoryInterface;
use Database\Factories\OrderModelFactory;
use Database\Factories\ProductOrderModelFactory;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FindOrderByCustomerIdServiceTest extends TestCase
{
    protected $orderRepository;
    protected $findOrderByCustomerIdService;
    protected $clientRequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->findOrderByCustomerIdService = new FindOrderByCustomerIdService($this->orderRepository);

        $this->clientRequest = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

        Log::shouldReceive('info');
    }

    public function testFindOrderByCustomerId()
    {
        $customerId = 1;

        $orders = [
            (object)[
                'order_id' => 1,
                'customer_id' => $customerId,
                'customer_name' => 'name',
                'product_name' => 'Product A',
                'product_price' => 10.0,
                'product_image' => 'product_a.jpg',
            ],
        ];

        $this->orderRepository->expects($this->once())
            ->method('findByCustomerId')
            ->with($customerId)
            ->willReturn($orders);

        $expectedResult = [
            [
                'order_id' => 1,
                'customer_id' => $customerId,
                'customer_name' => 'name',
                'products' => [
                    [
                        'name' => 'Product A',
                        'price' => 10.0,
                        'image' => 'product_a.jpg',
                    ],
                ],
            ],
        ];

        $result = $this->findOrderByCustomerIdService->find($customerId);

        $this->assertEquals($expectedResult, $result);
    }

    public function testFindOrderByCustomerIdNotFound()
    {
        $customerId = 1;

        $this->orderRepository->expects($this->once())
            ->method('findByCustomerId')
            ->with($customerId)
            ->willReturn([]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Order not found.");
        $this->expectExceptionCode(404);

        $this->findOrderByCustomerIdService->find($customerId);
    }

    public function testFindOrderByCustomerIdEndpoint() {
        $customer = CustomerModel::factory()->create();
        $order = OrderModel::factory()->create([
            'customer_id' => $customer->id,
        ]);
        $productOrder = ProductModel::factory()->create();
        ProductsOrderModel::factory()->create([
            'order_id' => $order->id,
            'product_id' => $productOrder->id
        ]);

        $response = $this->clientRequest->get('/api/order/customer/' . $order->customer_id);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertGreaterThan(0, count($data), 'A resposta deve conter mais de um elemento no array');
    }
}
