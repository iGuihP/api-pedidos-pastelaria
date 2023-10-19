<?php

namespace App\Services\Order;

use App\Repositories\OrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class FindOrderByCustomerIdService
{
    private $orderRepository;
    public function __construct(
        OrderRepositoryInterface $orderRepository,
    ) {
        $this->orderRepository = $orderRepository;
    }

    public function find(int $customerId) {
        Log::info("Running the service to find order by Customer ID: ".$customerId);
        $ordersFound = $this->findByCustomerId($customerId);
        return $this->formatOrders($ordersFound);
    }

    private function findByCustomerId(int $customerId) {
        $orderFound = $this->orderRepository->findByCustomerId($customerId);
        if (!$orderFound) {
            throw new Exception("Order not found.", 404);
        }

        return $orderFound;
    }

    private function formatOrders($orders) {
        $formattedOrders = [];

        foreach($orders as $order) {
            $orderId = $order->order_id;

            if (!isset($formattedOrders[$orderId])) {
                $formattedOrders[$orderId] = [
                    'order_id' => $orderId,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer_name,
                    'products' => []
                ];
            }
        
            $formattedOrders[$orderId]['products'][] = [
                'name' => $order->product_name,
                'price' => $order->product_price,
                'image' => $order->product_image
            ];
        }

        return array_values($formattedOrders);
    }
}
