<?php

namespace App\Product\Services;

use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductsOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateOrderService
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

    public function create(array $params): int {
        Log::info("Running the service to create a new product order.", $params);

        $createdOrder = $this->createOrder($params['customerId']);
        $productsOrderFormatted = $this->formatProductsOrderData($createdOrder->id, $params['productsId']);
        $this->createProductOrder($productsOrderFormatted);

        return $createdOrder->id;
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

    private function createOrder(int $customerId) {
        return $this->orderRepository->create($customerId);
    }

    private function createProductOrder(array $productsOrder) {
        $createdProduct = $this->productsOrderRepository->create($productsOrder);

        if(!$createdProduct) {
            throw new Exception("Houve uma falha ao inserir produtos no pedido.", 500);
        }

        return $createdProduct;
    }
}
