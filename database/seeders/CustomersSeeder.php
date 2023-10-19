<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            "name" => "João Marques",
            "email" => "joao.marques@hotmail.com",
            "telephone" => "1194723444",
            "birth" => "2001-11-08",
            "address" => "Av. Paulista, 2974",
            "complement" => "APTO. 201",
            "neighborhood" => "Paulista",
            "zipcode" => "01310930",
            "created_at" => date('Y-m-d H:i:s')
        ]);
    }
}
