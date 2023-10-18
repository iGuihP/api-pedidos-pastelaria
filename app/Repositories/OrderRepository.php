<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Repositories\OrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(
        int $customerId
    ) {
        Log::info("Inserting a new order.", ['customer_id' => $customerId]);

        return OrderModel::create([
            'customer_id' => $customerId
        ]);
    }

    public function findByFilters(int $customerId) {
        Log::info("Searching products by filters.", ['customer_id' => $customerId]);

        // $productModel = OrderModel::select([
        //     'id',
        //     'name',
        //     'price',
        //     'image',
        // ]);

        // if($name) {
        //     $productModel->where('name', 'like', '%' . $name . '%');
        // }

        // return $productModel->get();

        throw new Exception('Not implemented yet.', 501);
    }

    public function findById(int $id) {
        Log::info("Searching product by ID: ". $id);

        // $productModel = ProductModel::select([
        //     'id',
        //     'name',
        //     'price',
        //     'image',
        // ])->where('id', $id);

        // return $productModel->first();
        throw new Exception('Not implemented yet.', 501);
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
