<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
     protected static $languages = [
        [
            'code' => 'bs',
            'name' => 'Bosnian',
            'short' => 'bos', // Tesseract code
        ],
        [
            'code' => 'en',
            'name' => 'English',
            'short' => 'eng', // Tesseract code
        ],
    ];

    protected static $index = 0;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lang = self::$languages[self::$index];

        // Move index to next language for next factory call
        self::$index = (self::$index + 1) % count(self::$languages);

        return [
            'code' => $lang['code'],
            'name' => $lang['name'],
            'short' => $lang['short'], // matches Tesseract traineddata
        ];
    }
}
