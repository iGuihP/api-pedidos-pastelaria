<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => 'Pastel de Frango',
            'price' => 6.90,
            'image' => '123',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('products')->insert([
            'name' => 'Pastel de Carne',
            'price' => 5.20,
            'image' => '123',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
