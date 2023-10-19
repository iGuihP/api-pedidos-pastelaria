<?php

namespace App\Repositories;

use App\Models\ProductModel;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(
        string $name,
        float $price,
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

        $product = ProductModel::select([
            'id',
            'name',
            'price',
            'image',
            'created_at',
            'updated_at'
        ])->find($id);

        return $product;
    }

    public function listAll() {
        Log::info("Listing all products registered.");
        return ProductModel::get([
            'id',
            'name',
            'price',
            'image',
            'created_at',
            'updated_at'
        ]);
    }

    public function findByIds(array $ids) {
        Log::info("Listing products by IDs.");
        return ProductModel::whereIn('id', $ids)->get();
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
