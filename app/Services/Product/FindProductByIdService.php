<?php

namespace App\Product\Services;

use App\Repositories\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class FindProductByIdService
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function find(int $id) {
        Log::info("Running the service to find product by ID: ".$id);
        return $this->findById($id);
    }

    private function findById(int $id) {
        $productFound = $this->productRepository->findById($id);
        if (!$productFound) {
            throw new Exception("Product not found.", 404);
        }

        return $productFound;
    }
}
