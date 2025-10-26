<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'nisn' => $this->faker->unique()->numerify('##########'),
            'class' => 'IX-' . $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'gender' => $this->faker->randomElement(['L', 'P']),
        ];
    }
}
