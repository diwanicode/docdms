<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Bosnia',
            'short' => 'BA',
            'phone_code' => '+387',
            'continent' => 'Europe',
            'capital' => 'Sarajevo',
            'currency' => 'BAM',
            'flag' => 'Sa',
        ];
    }
}
