<?php

namespace App\Repositories;

interface CustomerRepositoryInterface
{
    public function create(
        string $name,
        string $email,
        string $telephone,
        string $birth,
        string $address,
        string $complement,
        string $neighborhood,
        string $zipcode
    );
    public function findByFilters(string $email = null, string $name = null);
    public function findById(int $id);
    public function checkIfAlreadyExistsByEmail(string $email);
    public function listAll();
    public function update($customer, array $dados);
    public function delete($customer);
}