<?php

namespace App\Services\Order;

use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductsOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class DeleteOrderService
{
    private $orderRepository;
    private $productsOrderRepository;
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductsOrderRepositoryInterface $productsOrderRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productsOrderRepository = $productsOrderRepository;
    }

    public function delete(int $orderId): void {
        Log::info("Running the service to delete a order ID: " . $orderId);

        $this->deleteProductsOrder($orderId);
        $orderFound = $this->findOrderById($orderId);
        $this->deleteOrder($orderFound);
    }

    private function deleteProductsOrder(int $orderId): void {
        $this->productsOrderRepository->deleteByOrderId($orderId);
    }

    private function findOrderById(int $orderId) {
        $orderFound = $this->orderRepository->findById($orderId);
        if (!$orderFound) {
            throw new Exception("Order not found.", 404);
        }

        return $orderFound;
    }

    private function deleteOrder($order): void {
        $this->orderRepository->delete($order);
    }
}
