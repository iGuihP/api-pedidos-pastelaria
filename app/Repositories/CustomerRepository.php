<?php

namespace App\Repositories;

use App\Models\CustomerModel;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CustomerRepository implements CustomerRepositoryInterface
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
    ) {
        Log::info("Inserting a new customer into the database, customer name: " . $name);

        return CustomerModel::create([
            'name' => $name,
            'email' => $email,
            'telephone' => $telephone,
            'birth' => $birth,
            'address' => $address,
            'complement' => $complement,
            'neighborhood' => $neighborhood,
            'zipcode' => $zipcode
        ]);
    }

    public function findByFilters(string $email = null, string $name = null) {
        Log::info("Searching customers by filters.", ['email' => $email, 'name' => $name]);

        $customerModel = CustomerModel::select([
            'id',
            'name',
            'email',
            'telephone',
            'birth',
            'address',
            'complement',
            'neighborhood',
            'zipcode',
            'created_at',
            'updated_at'
        ]);

        if($email) {
            $customerModel->where('email', 'like', '%' . $email . '%');
        }
        if($name) {
            $customerModel->where('name', 'like', '%' . $name . '%');
        }

        return $customerModel->get();
    }

    public function findById(int $id) {
        Log::info("Searching customers by ID: ". $id);

        $customerModel = CustomerModel::select([
            'id',
            'name',
            'email',
            'telephone',
            'birth',
            'address',
            'complement',
            'neighborhood',
            'zipcode',
            'created_at',
            'updated_at'
        ])->where('id', $id);

        return $customerModel->first();
    }

    public function listAll() {
        Log::info("Listing all customers registered.");
        return CustomerModel::get([
            'id',
            'name',
            'email',
            'telephone',
            'birth',
            'address',
            'complement',
            'neighborhood',
            'zipcode',
            'created_at',
            'updated_at'
        ]);
    }

    public function update($customer, array $data) {
        Log::info("Updating a customer.");
        return $customer->update($data);
    }

    public function delete($customer) {
        Log::info("Deleting a customer.");
        return $customer->delete();
    }
}
