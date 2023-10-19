<?php

namespace App\Repositories;

interface OrderRepositoryInterface
{
    public function create(int $customerId);
    public function findByFilters(int $customerId);
    public function findById(int $id);
    public function listAll();
    public function update($product, array $data);
    public function delete($product);
}