<?php

namespace App\Repositories;

use App\Models\OrderModel;
use App\Repositories\OrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(int $customerId) {
        Log::info("Inserting a new order.", ['customer_id' => $customerId]);

        return OrderModel::create([
            'customer_id' => $customerId
        ]);
    }

    public function findSingleOrderById(int $orderId) {
        Log::info("Searching single order by ID: ". $orderId);

        $orderModel = OrderModel::select([
            'orders.*',
        ]);
        $orderModel->where('orders.id', $orderId);

        return $orderModel->first();
    }

    public function findById(int $orderId) {
        Log::info("Searching order by ID: ". $orderId);

        $orderModel = OrderModel::select([
            'orders.customer_id',
            'customers.name as customer_name',
            'products_order.order_id',
            'products_order.product_id',
            'products.name as product_name',
            'products.price as product_price',
            'products.image as product_image',
            'orders.created_at',
        ]);
        $orderModel->join('products_order', 'orders.id', '=', 'products_order.order_id');
        $orderModel->join('products', 'products.id', '=', 'products_order.product_id');
        $orderModel->join('customers', 'customers.id', '=', 'orders.customer_id');
        $orderModel->where('orders.id', $orderId);

        return $orderModel->get();
    }

    public function findByCustomerId(int $customerId) {
        Log::info("Searching order by Customer ID: ". $customerId);

        $orderModel = OrderModel::select([
            'orders.customer_id',
            'customers.name as customer_name',
            'products_order.order_id',
            'products_order.product_id',
            'products.name as product_name',
            'products.price as product_price',
            'products.image as product_image',
            'orders.created_at'
        ]);
        $orderModel->join('products_order', 'orders.id', '=', 'products_order.order_id');
        $orderModel->join('products', 'products.id', '=', 'products_order.product_id');
        $orderModel->join('customers', 'customers.id', '=', 'orders.customer_id');
        $orderModel->where('orders.customer_id', $customerId);

        return $orderModel->get();
    }

    public function update($order, array $data) {
        Log::info("Updating a order.");
        return $order->update($data);
    }

    public function delete($order) {
        Log::info("Deleting a order.");
        return $order->delete();
    }
}
