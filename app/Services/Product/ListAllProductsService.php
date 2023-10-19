<?php

namespace App\Services\Product;

use App\Repositories\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class ListAllProductsService
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function list() {
        Log::info("Running the service to list all products.");
        return $this->listAll();
    }

    private function listAll() {
        $productsFound = $this->productRepository->listAll();
        if (!$productsFound) {
            throw new Exception("Product not found.", 404);
        }

        return $productsFound;
    }
}
