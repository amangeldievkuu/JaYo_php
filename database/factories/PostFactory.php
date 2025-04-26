<?php

namespace Database\Factories;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employer_id' => Employer::factory(),
            'title' => fake()->jobTitle(),
            'salary' => fake()->randomElement(['50,000$', '60,000$', '70,000$', '80,000$', '120,000$', '150,000$', '90,000$']),
            'location' => fake()->randomElement(['Remote', fake()->city()]),
            'schedule' => fake()->randomElement(['Full Time', 'Part Time', 'Internship']),
            'url' => fake()->url(),
            'featured' => false
        ];
    }
}
