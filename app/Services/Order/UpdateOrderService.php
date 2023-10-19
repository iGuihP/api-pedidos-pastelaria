<?php

namespace App\Services\Order;

use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductsOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateOrderService
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

    public function update(int $orderId, array $newProductsId): void {
        Log::info("Running the service to update a order.", ['newProductsId' => $newProductsId]);
        
        $this->findOrderById($orderId);
        $formattedProductsOrderData = $this->formatProductsOrderData($orderId, $newProductsId);
        $this->updateProductsOrder($orderId, $formattedProductsOrderData);
    }

    private function findOrderById(int $id) {
        $orderFound = $this->orderRepository->findSingleOrderById($id);
        if (!$orderFound) {
            throw new Exception("Order not found.", 404);
        }
        return $orderFound;
    }

    private function formatProductsOrderData(int $orderId, array $productsId) {
        $formattedData = [];

        foreach ($productsId as $productId) {
            $formattedData[] = [
                'product_id' => $productId,
                'order_id' => $orderId
            ];
        }

        return $formattedData;
    } 

    private function updateProductsOrder(int $orderId, array $newProductsId): void {
        $this->productsOrderRepository->deleteByOrderId($orderId);
        $this->productsOrderRepository->create($newProductsId);
    }
}
