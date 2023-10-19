<?php

namespace App\Product\Services;

use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductsOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class FindOrderByIdService
{
    private $orderRepository;
    public function __construct(
        OrderRepositoryInterface $orderRepository,
    ) {
        $this->orderRepository = $orderRepository;
    }

    public function find(int $id) {
        Log::info("Running the service to find order by ID: ".$id);
        return $this->findById($id);
    }

    private function findById(int $id) {
        $orderFound = $this->orderRepository->findById($id);
        if (!$orderFound) {
            throw new Exception("Order not found.", 404);
        }

        return $orderFound;
    }
}
