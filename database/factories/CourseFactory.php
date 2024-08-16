<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'instructor_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
            'description' => $this->faker->paragraph,
            'brief' => $this->faker->text(100),
            'image' => $this->faker->imageUrl,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'professional']),
            'created_at' => now(),
            'updated_at' => now(),
          
        ];
    }
}
