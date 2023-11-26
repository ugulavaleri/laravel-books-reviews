<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'review' => fake()->paragraph(5),
            'rating' => fake()->numberBetween(1,5),
        ];
    }

    public function good(){
        return $this->state(function (array $attributes){
            return [
                'rating' => fake()->numberBetween(3,5),
            ];
        });
    }

    public function average(){
        return $this->state(function (array $attributes){
            return [
                'rating' => fake()->numberBetween(2,4),
            ];
        });
    }

    public function bad(){
        return $this->state(function (array $attributes){
            return [
                'rating' => fake()->numberBetween(1,3),
            ];
        });
    }
}
