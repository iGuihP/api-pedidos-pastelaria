<?php

use PHPUnit\Framework\TestCase;
use App\Services\Order\FindOrderByIdService;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FindOrderByIdServiceTest extends TestCase
{
    protected $orderRepository;
    protected $findOrderByIdService;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->findOrderByIdService = new FindOrderByIdService($this->orderRepository);

        Log::shouldReceive('info');
    }

    public function testFindOrderById()
    {
        $orderId = 1;

        $order = (object)[
            'order_id' => $orderId,
            'customer_id' => 1,
            'customer_name' => 'John Doe',
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
            'customer_name' => 'John Doe',
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
}
