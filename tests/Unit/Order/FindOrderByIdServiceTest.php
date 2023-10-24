<?php

use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\ProductsOrderModel;
use Tests\TestCase;
use App\Services\Order\FindOrderByIdService;
use App\Repositories\OrderRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FindOrderByIdServiceTest extends TestCase
{
    protected $orderRepository;
    protected $findOrderByIdService;
    protected $clientRequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->findOrderByIdService = new FindOrderByIdService($this->orderRepository);

        $this->clientRequest = new Client([
            'base_uri' => 'http://localhost:5000',
        ]);

        Log::shouldReceive('info');
    }

    public function testFindOrderById()
    {
        $orderId = 1;

        $order = (object)[
            'order_id' => $orderId,
            'customer_id' => 1,
            'customer_name' => 'name',
            'product_name' => 'Product 1',
            'product_price' => 10.0,
            'product_image' => 'product.jpg',
        ];
        $this->orderRepository->expects($this->once())
            ->method('findById')
            ->with($orderId)
            ->willReturn([$order]);

        $result = $this->findOrderByIdService->find($orderId);

        $expectedResult = [
            'order_id' => $orderId,
            'customer_id' => 1,
            'customer_name' => 'name',
            'products' => [
                [
                    'name' => 'Product 1',
                    'price' => 10.0,
                    'image' => 'product.jpg',
                ],
            ],
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testFindOrderByIdNotFound()
    {
        $orderId = 1;

        $this->orderRepository->expects($this->once())
            ->method('findById')
            ->with($orderId)
            ->willReturn([]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Order not found.");
        $this->expectExceptionCode(404);

        $this->findOrderByIdService->find($orderId);
    }

    public function testFindCustomerByIdNotFoundEndpoint() {
        $customer = CustomerModel::factory()->create();
        $order = OrderModel::factory()->create([
            'customer_id' => $customer->id,
        ]);
        $productOrder = ProductModel::factory()->create();
        ProductsOrderModel::factory()->create([
            'order_id' => $order->id,
            'product_id' => $productOrder->id
        ]);

        $response = $this->clientRequest->get('/api/order/' . $order->id);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('order_id', $data, 'A chave "order_id" deve estar na resposta');
    }
}
