<?php

namespace App\Product\Services;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CreateProductService
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(array $params): int {
        Log::info("Running the service to create a new product.", $params);
        
        $createdProduct = $this->createProduct($params);

        return $createdProduct->id;
    }

    private function createProduct(array $params) {
        $createdProduct = $this->productRepository->create(
            $params['name'],
            $params['price'],
            $params['image'],
        );

        return $createdProduct;
    }
}
