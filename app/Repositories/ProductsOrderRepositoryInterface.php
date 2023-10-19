<?php

namespace App\Repositories;

interface ProductsOrderRepositoryInterface
{
    public function create(array $productsOrder);
    public function findByOrderId(int $orderId);
    public function deleteByOrderId(int $orderId);
}