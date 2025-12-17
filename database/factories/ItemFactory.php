<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'category_id' => null,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'city' => $this->faker->randomElement([
                'Sofia', 'Plovdiv', 'Varna', 'Burgas', 'Ruse', 'Stara Zagora', 'Pleven'
            ]),
            'weight' => $this->faker->boolean(35) ? $this->faker->randomFloat(2, 0.1, 30) : null,
            'dimensions' => $this->faker->boolean(30) ? $this->faker->randomElement([
                '40x30x20 cm', '60x40x30 cm', '120x60x40 cm', '30x30x30 cm'
            ]) : null,
            'status' => $this->faker->boolean(15) ? 'gifted' : 'available',
        ];
    }
}
