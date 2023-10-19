<?php

use PHPUnit\Framework\TestCase;
use App\Services\Order\DeleteOrderService;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductsOrderRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class DeleteOrderServiceTest extends TestCase
{
    protected $orderRepository;
    protected $productsOrderRepository;
    protected $deleteOrderService;

    public function setUp(): void
    {
        parent::setUp();
        // Mock the OrderRepositoryInterface and ProductsOrderRepositoryInterface for testing
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->productsOrderRepository = $this->createMock(ProductsOrderRepositoryInterface::class);
        $this->deleteOrderService = new DeleteOrderService($this->orderRepository, $this->productsOrderRepository);

        // Suppress logging during tests
        Log::shouldReceive('info');
    }

    public function testDeleteOrder()
    {
        $orderId = 1;

        // Mock the findSingleOrderById method in the order repository to return an order
        $order = (object)['id' => $orderId];
        $this->orderRepository->expects($this->once())
            ->method('findSingleOrderById')
            ->with($orderId)
            ->willReturn($order);

        // Mock the deleteByOrderId method in the products order repository
        $this->productsOrderRepository->expects($this->once())
            ->method('deleteByOrderId')
            ->with($orderId);

        // Mock the delete method in the order repository
        $this->orderRepository->expects($this->once())
            ->method('delete')
            ->with($order);

        $this->deleteOrderService->delete($orderId);
    }

    public function testDeleteOrderNotFound()
    {
        $orderId = 1;

        // Mock the findSingleOrderById method in the order repository to return null (order not found)
        $this->orderRepository->expects($this->once())
            ->method('findSingleOrderById')
            ->with($orderId)
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Order not found.");
        $this->expectExceptionCode(404);

        $this->deleteOrderService->delete($orderId);
    }
}
