<?php

namespace App\Services\Product;

use App\Repositories\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateProductService
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function update(int $productId, array $data, $image): void {
        Log::info("Running the service to update a product.", $data);
        
        $productFound = $this->findProductById($productId);
        if($image) {
            $uploadPath = $image->store('/', 'local');
            $data['image'] = $uploadPath;
        }

        $this->updateProduct($productFound, $data);
    }

    private function findProductById(int $id) {
        $productFound = $this->productRepository->findById($id);
        if (!$productFound) {
            throw new Exception("Product not found.", 404);
        }
        return $productFound;
    }

    private function updateProduct($product, array $data) {
        $updatedProduct = $this->productRepository->update(
            $product,
            $data
        );

        return $updatedProduct;
    }
}
