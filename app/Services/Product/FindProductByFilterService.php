<?php

namespace App\Services\Product;

use App\Repositories\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class FindProductByFilterService
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function find(array $params) {
        Log::info("Running the service to find a products by filters.", $params);
        
        $productFound = $this->findProductsByFilters(
            $params['name']
        );

        return $productFound;
    }

    private function findProductsByFilters(
        string $name
    ) {
        $productFound = $this->productRepository->findByFilters($name);
        if (!$productFound) {
            throw new Exception("Product not found.", 404);
        }

        return $productFound;
    }
}
