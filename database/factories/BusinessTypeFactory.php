<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessType>
 */
class BusinessTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'country_id' => Country::factory(),
            'short' =>$this->faker->word,
        ];
    }
}
