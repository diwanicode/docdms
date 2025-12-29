<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
           [
                'name' => 'Bosnia and Herzegovina',
                'short' => 'BA',
                'phone_code' => '+387',
                'continent' => 'Europe',
                'capital' => 'Sarajevo',
                'currency' => 'BAM',
                'flag' => '/images/ui_flags/ba.png',
                'has_trunk_prefix' => true
            ],
            [
                'name' => 'Germany',
                'short' => 'DE',
                'phone_code' => '+49',
                'continent' => 'Europe',
                'capital' => 'Berlin',
                'currency' => 'EUR',
                'flag' => '/images/ui_flags/de.png',
                'has_trunk_prefix' => true
            ],         
        ];

        DB::table('countries')->insert($countries);
        $countryRegions = [
           [
                'name' => 'Federacija Bosne i Hercegovine',
                'short' => 'FBiH',
                'country_id' => 1,
            ],
            [
                'name' => 'Republika Srpska',
                'short' => 'RS',
                'country_id' => 1,
            ], 
            [
                'name' => 'Brčko District',
                'short' => 'BD',
                'country_id' => 1,
            ],        
        ];
        DB::table('country_regions')->insert($countryRegions);
      
      
        $countryFileCategories = [
           [
                'name' => 'Ulazne fakture',
                'slug' => 'ulazne_fakture',
                'country_id' => 1,
            ],
            [
                'name' => 'Izlazne fakture',
                'slug' => 'izlazne_fakture',
                'country_id' => 1,
            ], 
            [
                'name' => 'Plate',
                'slug' => 'plate',
                'country_id' => 1,
            ],  
            [
                'name' => 'Ostalo',
                'slug' => 'ostalo',
                'country_id' => 1,
            ],        
        ];
        DB::table('country_file_categories')->insert($countryFileCategories);
         $countryFileTypes = [
           [
                'name' => 'Platna lista',
                'slug' => 'platna_lista',
                'country_id' => 1,
                'country_file_category_id' => 3,
            ],
            [
                'name' => 'Obračun',
                'slug' => 'obracun',
                'country_id' => 1,
                'country_file_category_id' => 3,
            ], 
            [
                'name' => 'Prijava/odjava radnika',
                'slug' => 'prijava_odjava_radnika',
                'country_id' => 1,
                'country_file_category_id' => 3,
            ]      
        ];
        DB::table('country_file_types')->insert($countryFileTypes);
         // business file status 
        $bosnia = Country::where('short','BA')->first();

        $bosnia->fileStatuses()->create([
            'name' => 'Učitan',
            'color_light' => '#f3e5f5',
            'color_dark' => '#6a1b9a',
            'order' => 1,
        ]);

        $bosnia->fileStatuses()->create([
            'name' => 'U obradi',
            'color_light' => '#fdf5e6',
            'color_dark' => '#ef6c00',
            'order' => 2,
        ]);

        $bosnia->fileStatuses()->create([
            'name' => 'Odobren',
            'color_light' => '#e8f5e9',
            'color_dark' => '#2e7d32',
            'order' => 3,
        ]);

    }
}
