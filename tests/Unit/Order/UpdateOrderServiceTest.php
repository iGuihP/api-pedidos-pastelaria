<?php

use PHPUnit\Framework\TestCase;
use App\Services\Order\UpdateOrderService;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductsOrderRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UpdateOrderServiceTest extends TestCase
{
    protected $orderRepository;
    protected $productsOrderRepository;
    protected $updateOrderService;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->productsOrderRepository = $this->createMock(ProductsOrderRepositoryInterface::class);
        $this->updateOrderService = new UpdateOrderService($this->orderRepository, $this->productsOrderRepository);

        // Suppress logging during tests
        Log::shouldReceive('info');
    }

    public function testUpdateOrder()
    {
        $orderId = 1;
        $newProductsId = [101, 102, 103];

        $order = (object)['id' => $orderId];
        $this->orderRepository->expects($this->once())
            ->method('findSingleOrderById')
            ->with($orderId)
            ->willReturn($order);

        $formattedData = [
            ['product_id' => 101, 'order_id' => $orderId],
            ['product_id' => 102, 'order_id' => $orderId],
            ['product_id' => 103, 'order_id' => $orderId],
        ];

        $this->productsOrderRepository->expects($this->once())
            ->method('deleteByOrderId')
            ->with($orderId);

        $this->productsOrderRepository->expects($this->once())
            ->method('create')
            ->with($formattedData);

        $this->updateOrderService->update($orderId, $newProductsId);
    }

    public function testUpdateOrderNotFound()
    {
        $orderId = 1;
        $newProductsId = [101, 102, 103];

        $this->orderRepository->expects($this->once())
            ->method('findSingleOrderById')
            ->with($orderId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Order not found.");
        $this->expectExceptionCode(404);

        $this->updateOrderService->update($orderId, $newProductsId);
    }
}
