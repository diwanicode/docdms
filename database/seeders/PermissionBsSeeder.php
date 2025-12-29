<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Permission;
use App\Models\PermissionCountry;
use Illuminate\Support\Str;


class PermissionBsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Business 
            'business.update',

            // Employees 
            'employees.create',
            'employees.update',
            'employees.delete',

            // Clients 
            'clients.create',
            'clients.update',
            'clients.delete',

            // Departments 
            'departments.view',
            'departments.create',
            'departments.update',
            'departments.delete',

            // Files 
            'files.create',
            'files.update',
            'files.delete',
        ];

        foreach ($permissions as $slug) {
            Permission::firstOrCreate([
                'slug' => $slug,
            ]);
        }

        $bosnia = Country::where('short', 'BA')->firstOrFail();
       $translations = [ 
            'business.update' => [
                'resource' => 'Biznis',
                'action'   => 'Uređivanje',
                'name'     => 'Uređivanje poslovanja',
            ],
 
            'employees.create' => [
                'resource' => 'Zaposleni',
                'action'   => 'Dodavanje',
                'name'     => 'Dodavanje zaposlenih',
            ],
            'employees.update' => [
                'resource' => 'Zaposleni',
                'action'   => 'Uređivanje',
                'name'     => 'Uređivanje zaposlenih',
            ],
            'employees.delete' => [
                'resource' => 'Zaposleni',
                'action'   => 'Brisanje',
                'name'     => 'Brisanje zaposlenih',
            ],
 
            'clients.create' => [
                'resource' => 'Klijenti',
                'action'   => 'Dodavanje',
                'name'     => 'Dodavanje klijenata',
            ],
            'clients.update' => [
                'resource' => 'Klijenti',
                'action'   => 'Uređivanje',
                'name'     => 'Uređivanje klijenata',
            ],
            'clients.delete' => [
                'resource' => 'Klijenti',
                'action'   => 'Brisanje',
                'name'     => 'Brisanje klijenata',
            ],

            'departments.view' => [
                'resource' => 'Odjel',
                'action'   => 'Pregled',
                'name'     => 'Pregled odjela',
            ],
            'departments.create' => [
                'resource' => 'Odjel',
                'action'   => 'Dodavanje',
                'name'     => 'Dodavanje odjela',
            ],
            'departments.update' => [
                'resource' => 'Odjel',
                'action'   => 'Uređivanje',
                'name'     => 'Uređivanje odjela',
            ],
            'departments.delete' => [
                'resource' => 'Odjel',
                'action'   => 'Brisanje',
                'name'     => 'Brisanje odjela',
            ],
 
            'files.create' => [
                'resource' => 'Dokumenti',
                'action'   => 'Dodavanje',
                'name'     => 'Dodavanje dokumenata',
            ],
            'files.update' => [
                'resource' => 'Dokumenti',
                'action'   => 'Uređivanje',
                'name'     => 'Uređivanje dokumenata',
            ],
            'files.delete' => [
                'resource' => 'Dokumenti',
                'action'   => 'Brisanje',
                'name'     => 'Brisanje dokumenata',
            ],
        ];


        foreach ($translations as $slug => $data) {
            $permission = Permission::where('slug', $slug)->first();

            if (! $permission) {
                continue;
            }

            PermissionCountry::updateOrCreate(
                [
                    'permission_id' => $permission->id,
                    'country_id' => $bosnia->id,
                ],
                [
                    'resource' => $data['resource'],
                    'action' => $data['action'],
                    'name' => $data['name'],
                    'description' => null,
                ]
            );
        }
    }
}
