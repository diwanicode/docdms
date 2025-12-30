<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessType;
use App\Models\User; 
use Carbon\Carbon; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languageBs = 1;
        $languageEn = 2;
        Log::info('BusinessSeeder');
   
        BusinessType::insert([
            [
                'name' => 'Društvo s ograničenom odgovornošću',
                'short' => 'd.o.o',
                'country_id' => 1,
            ],
             [
                'name' => 'Dioničko društvo',
                'short' => 'd.d',
                'country_id' => 1,
            ] ,
            [
                'name' => 'Obrt',
                'short' => 'OD',
                'country_id' => 1,
            ],
                        
        ]);
        Business::insert([
            [
                'name'=>'Start HN',
                'slug'=>'start_hn',
                'vat_number'=> '1111111111111111',
                'business_type_id' =>1,
                'country_id' =>1,
                'country_region_id' =>1,
                'language_id' =>1,
                'is_test' =>1,
            ],
            [
                'name'=>'Adin racunovodstvo',
                'slug'=>'adin_racunovodstvo',
                'vat_number'=> '1111111111111111',
                'business_type_id' =>1,
                'country_id' =>1,
                'country_region_id' =>1,
                'language_id' =>1,
                'is_test' =>1,
            ],
            [
                'name'=>'Emina racunovodstvo',
                'slug'=>'emina_racunovodstvo',
                'vat_number'=> '1111111111111111',
                'business_type_id' =>2,
                'country_id' =>1,
                'country_region_id' =>1,
                'language_id' =>1,
                'is_test' =>1,
            ]
        ]);
        $startHn = Business::where('slug','start_hn')->first();
        $adinBiznis = Business::where('slug','adin_racunovodstvo')->first();
        $eminaBiznis = Business::where('slug','emina_racunovodstvo')->first();

        $departmentAdmin =  $startHn->businessDepartments()->create([
                'name' => 'Administracija',
                'description' => 'Ovo je administracija',
          ]);
        $departmentFin =  $startHn->businessDepartments()->create([
                'name' => 'Financije',
                'description' => 'Ovo je financije',
          ]);
        User::factory()->create([
            'name' => 'Selma',
            'email' => 'selma@example.com',
            'password' => Hash::make('Sarajevo71000'),
        ]);
        User::factory()->create([
            'name' => 'Aida',
            'email' => 'aida@example.com',
            'password' => Hash::make('Sarajevo71000'),
        ]);
        User::factory()->create([
            'name' => 'Adin',
            'email' => 'adin@example.com',
            'password' => Hash::make('Sarajevo71000'),
        ]);
        User::factory()->create([
            'name' => 'Emina',
            'email' => 'emina@example.com',
            'password' => Hash::make('Sarajevo71000'),
        ]);       
       
        $selma = User::where('name','Selma')->first();
        $aida = User::where('name','Aida')->first();
        $adin = User::where('name','Adin')->first();
        $emina = User::where('name','Emina')->first(); 
       
      
        $selmaEmployee= $startHn->businessEmployees()->create([
                'user_id' => $selma->id,
                'is_owner' => true,
                'is_working' => true,
                'name' => 'Selma',
                'start_date' => '2025-02-07',
                'work_email' => $selma->email,
                'phone_number' => '62123232',

        ]);
        $selmaEmployee->departments()->attach([$departmentAdmin->id, $departmentFin->id]);
     
        $aidaEmployee =$startHn->businessEmployees()->create([
                'user_id' => $aida->id,
                'is_owner' => false,
                'is_working' => true,
                'name' => 'Aida',
                'start_date' => '2025-02-07',
                'work_email' => $aida->email,
                'phone_number' => '062123232',
        ]);
        $aidaEmployee->departments()->attach([ $departmentFin->id]);

        $adinEmployee =  $adinBiznis->businessEmployees()->create([
            'user_id' => $adin->id,
            'is_owner' => true,
            'is_working' => false,
            'name' => 'Adin',
            'start_date' => '2025-02-07',
            'work_email' => $adin->email,
            'phone_number' => '62123232',
        ]);
    
        $eminaEmployee = $eminaBiznis->businessEmployees()->create([
            'user_id' => $emina->id,
            'is_owner' => true,
            'is_working' => true,
            'name' => 'Emina',
            'start_date' => '2025-02-07',
            'work_email' =>  $emina->email,
            'phone_number' => '62123232',
        ]);
     
        //clients 
          User::factory()->create([
            'name' => 'Emina Diwani',
            'email' => 'emina_diwani@example.com',
            'password' => Hash::make('Sarajevo71000'),
        ]);       
       
        $eminaDiwani = User::where('name','Emina Diwani')->first();

        $startHn->businessClients()->create([
            'name'=>'Diwani Code Solutions',
            'user_id' => $eminaDiwani->id,
            'contact' => 'Emina Alikadic',
            'vat_number'=> '1111111111111111',
            'business_employee_id'=>$aida->id,
            'business_type_id' =>2,
            'country_id' =>1,
            'city' =>'Sarajevo',
            'address' => 'Geteova 12',
            'email' => $eminaDiwani->email,
            'number' =>'063212323',
            'start_date' => '2025-11-07',

        ]);
        
    }
}
