<?php

namespace App\Repositories;

interface OrderRepositoryInterface
{
    public function create(int $customerId);
    public function findSingleOrderById(int $orderId);
    public function findById(int $orderId);
    public function findByCustomerId(int $customerId);
    public function listAll();
    public function update($product, array $data);
    public function delete($product);
}