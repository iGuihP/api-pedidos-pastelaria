<?php

namespace App\Repositories;

use App\Models\ProductModel;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(
        string $name,
        int $price,
        string $image,
    ) {
        Log::info("Inserting a new product into the database, product name: " . $name);

        return ProductModel::create([
            'name' => $name,
            'price' => $price,
            'image' => $image
        ]);
    }

    public function findByFilters(string $name) {
        Log::info("Searching products by filters.", ['name' => $name]);

        $productModel = ProductModel::select([
            'id',
            'name',
            'price',
            'image',
        ]);

        if($name) {
            $productModel->where('name', 'like', '%' . $name . '%');
        }

        return $productModel->get();
    }

    public function findById(int $id) {
        Log::info("Searching product by ID: ". $id);

        $productModel = ProductModel::select([
            'id',
            'name',
            'price',
            'image',
        ])->where('id', $id);

        return $productModel->first();
    }

    public function listAll() {
        Log::info("Listing all products registered.");
        return ProductModel::get([
            'id',
            'name',
            'price',
            'image',
        ]);
    }

    public function update($product, array $data) {
        Log::info("Updating a product.");
        return $product->update($data);
    }

    public function delete($product) {
        Log::info("Deleting a product.");
        return $product->delete();
    }
}
