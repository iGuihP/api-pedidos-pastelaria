<?php

namespace App\Repositories;

interface ProductsOrderRepositoryInterface
{
    public function create(array $productsOrder);
    public function findByOrderId(int $orderId);
    public function deleteByOrderId(int $orderId);
    public function update($product, array $data);
    public function delete($product);
}