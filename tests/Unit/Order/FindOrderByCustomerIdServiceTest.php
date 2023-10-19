<?php

use PHPUnit\Framework\TestCase;
use App\Services\Order\FindOrderByCustomerIdService;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FindOrderByCustomerIdServiceTest extends TestCase
{
    protected $orderRepository;
    protected $findOrderByCustomerIdService;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->findOrderByCustomerIdService = new FindOrderByCustomerIdService($this->orderRepository);

        Log::shouldReceive('info');
    }

    public function testFindOrderByCustomerId()
    {
        $customerId = 1;

        $orders = [
            (object)[
                'order_id' => 1,
                'customer_id' => $customerId,
                'customer_name' => 'John Doe',
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
                'customer_name' => 'John Doe',
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
}
