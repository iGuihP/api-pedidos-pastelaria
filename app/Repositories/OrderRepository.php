<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\ProductModel;
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

    public function findById(int $orderId) {
        Log::info("Searching order by ID: ". $orderId);

        $orderModel = OrderModel::select([
            'order.customer_id',
            'po.order_id',
            'po.product_id',
            'p.name',
            'p.price',
            'order.created_at'
        ]);
        $orderModel->join('products_order', 'order.id', '=', 'products_order.order_id');
        $orderModel->join('products', 'products.id', '=', 'products_order.product_id');
        $orderModel->where('order.id', $orderId);

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
