<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experiment>
 */
class ExperimentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $religion = ["islam", "kristen", "hindu", "buddha", "konhucu", "katolik"];
        return [
            "name"           => fake()->name,
            "religion"       => $religion[rand(0, count($religion)-1)],
            "picture"        => "default.png",
            "nilai"          => rand(1,100),
            "is_active"      => rand(0, 1),
            "birth_date"     => fake()->date,
            "birth_location" => fake()->word,
        ];
    }
}
