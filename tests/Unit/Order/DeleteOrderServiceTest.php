<?php

use App\Models\CustomerModel;
use App\Models\OrderModel;
use Tests\TestCase;
use App\Traits\ClientRequestTrait;
use App\Services\Order\DeleteOrderService;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductsOrderRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DeleteOrderServiceTest extends TestCase
{
    use ClientRequestTrait;
    protected $orderRepository;
    protected $productsOrderRepository;
    protected $deleteOrderService;
    protected $requestClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->productsOrderRepository = $this->createMock(ProductsOrderRepositoryInterface::class);
        $this->deleteOrderService = new DeleteOrderService($this->orderRepository, $this->productsOrderRepository);
        
        Log::shouldReceive('info');
    }

    public function testDeleteOrder()
    {
        $orderId = 1;

        $order = (object)['id' => $orderId];
        $this->orderRepository->expects($this->once())
            ->method('findSingleOrderById')
            ->with($orderId)
            ->willReturn($order);

        $this->productsOrderRepository->expects($this->once())
            ->method('deleteByOrderId')
            ->with($orderId);

        $this->orderRepository->expects($this->once())
            ->method('delete')
            ->with($order);

        $this->deleteOrderService->delete($orderId);
    }

    public function testDeleteOrderNotFound()
    {
        $orderId = 1;

        $this->orderRepository->expects($this->once())
            ->method('findSingleOrderById')
            ->with($orderId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Order not found.");
        $this->expectExceptionCode(404);

        $this->deleteOrderService->delete($orderId);
    }

    public function testDeleteOrderEndpoint() {
        $order = OrderModel::factory()->create();
        $response = $this->requestClient()->delete('/api/order/' . $order->id);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
