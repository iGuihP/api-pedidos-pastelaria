<?php

namespace Database\Factories;

use App\Models\CustomerModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerModel>
 */
class ProductModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = md5(fake()->unique()->word()) . '.jpg';
        $image = imagecreatetruecolor(200, 200);
        imagejpeg($image, storage_path('app/public/' . $filename));

        return [
            'name' => fake()->word(),
            'price' => fake()->randomFloat(2, 0, 100),
            'image' => $filename,
        ];
    }
}
