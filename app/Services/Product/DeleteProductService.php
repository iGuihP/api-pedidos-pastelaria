<?php

namespace App\Services\Product;

use App\Repositories\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DeleteProductService
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function delete(int $productId): void {
        Log::info("Running the service to delete a product ID: ". $productId);
        
        $productFound = $this->findProductById($productId);
        // $productData = $productFound->first();
        // File::delete(storage_path('app/public/' . $productData->image));
        $this->deleteProduct($productFound);
    }

    private function findProductById(int $id) {
        $productFound = $this->productRepository->findById($id);
        if (!$productFound) {
            throw new Exception("Product not found.", 404);
        }
        return $productFound;
    }

    private function deleteProduct($product): void {
        $this->productRepository->delete($product);
    }
}
