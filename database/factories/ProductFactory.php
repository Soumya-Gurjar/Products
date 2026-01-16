<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'sku' => 'SKU-' . $this->faker->unique()->numberBetween(100, 999),
            'price' => 100,
            'stock' => 10,
            'is_active' => 1,
        ];
    }
}
