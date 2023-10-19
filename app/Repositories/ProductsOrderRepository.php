<?php

namespace App\Repositories;

use App\Models\OrderModel;
use App\Models\ProductsOrderModel;
use App\Repositories\ProductsOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductsOrderRepository implements ProductsOrderRepositoryInterface
{
    public function create(array $productsOrder) {
        Log::info("Inserting a new products order.", $productsOrder);

        return ProductsOrderModel::insert($productsOrder);
    }

    public function findByOrderId(int $orderId) {
        Log::info("Searching products order by Order ID: ". $orderId);

        $productsOrderModel = ProductsOrderModel::select([
            'id',
            'order_id',
            'product_id',
        ])->where('order_id', $orderId);

        return $productsOrderModel->get();
    }

    public function deleteByOrderId(int $orderId) {
        Log::info("Deleting products order by Order ID: ". $orderId);

        $productsOrderModel = ProductsOrderModel::where('order_id', $orderId);

        return $productsOrderModel->delete();
    }

    public function delete($product) {
        // Log::info("Deleting a product.");
        // return $product->delete();
        throw new Exception('Not implemented yet.', 501);
    }
}
