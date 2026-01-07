<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

// Import all related models
use App\Models\BusinessType;
use App\Models\Language;
use App\Models\Country;
use App\Models\CountryRegion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'business_type_id' => BusinessType::factory(),
            'language_id' => Language::factory(),
            'country_id' => Country::factory(),
            'country_region_id' => CountryRegion::factory(),
            'logo' => null,
            'visible' => true,
            'is_test' => true,
            'vat_number' => null,
        ];
    }
}
