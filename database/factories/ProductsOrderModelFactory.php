<?php

namespace Database\Factories;

use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerModel>
 */
class ProductsOrderModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = OrderModel::inRandomOrder()->first();
        $product = ProductModel::inRandomOrder()->first();
        
        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ];
    }
}
