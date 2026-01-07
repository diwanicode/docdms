<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $languageBs = 1;
        $languageEn = 2;
        Language::insert([
            ['code' => 'bs','short' => 'bos', 'name' => 'Bosnian'],
            ['code' => 'en','short' => 'eng', 'name' => 'English'],
        ]);

        $this->call([          
            CountrySeeder::class,
            PermissionBsSeeder::class,
            BusinessSeeder::class,
        ]);
    }
}
