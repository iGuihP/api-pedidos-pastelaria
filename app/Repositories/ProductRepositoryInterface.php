<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function create(
        string $name,
        int $price,
        string $image,
    );
    public function findByFilters(string $name);
    public function findById(int $id);
    public function listAll();
    public function update($product, array $data);
    public function delete($product);
}