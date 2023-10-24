<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerModel>
 */
class CustomerModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake('pt_BR')->cellphone(),
            'birth' => fake()->date(),
            'address' => fake()->streetAddress(),
            'complement' => fake()->secondaryAddress(),
            'neighborhood' => fake()->streetSuffix(),
            'zipcode' => str_replace("-", "", fake('pt_BR')->postcode()),
        ];
    }
}
