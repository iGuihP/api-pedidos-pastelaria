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

        return $orderModel->exists();
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
            'orders.created_at'
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

    public function listAll() {
        Log::info("Listing all products registered.");
        // return ProductModel::get([
        //     'id',
        //     'name',
        //     'price',
        //     'image',
        // ]);
        throw new Exception('Not implemented yet.', 501);
    }

    public function update($product, array $data) {
        // Log::info("Updating a product.");
        // return $product->update($data);
        throw new Exception('Not implemented yet.', 501);
    }

    public function delete($product) {
        // Log::info("Deleting a product.");
        // return $product->delete();
        throw new Exception('Not implemented yet.', 501);
    }
}
