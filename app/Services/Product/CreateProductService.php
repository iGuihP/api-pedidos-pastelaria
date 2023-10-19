<?php

namespace App\Services\Product;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CreateProductService
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(array $params, $image): int {
        Log::info("Running the service to create a new product.", $params);
        $uploadPath = $image->store('/', 'local');
        $createdProduct = $this->createProduct($params, $uploadPath);

        return $createdProduct->id;
    }

    private function createProduct(array $params, string $imagePath) {
        $createdProduct = $this->productRepository->create(
            $params['name'],
            $params['price'],
            $imagePath,
        );

        return $createdProduct;
    }
}
