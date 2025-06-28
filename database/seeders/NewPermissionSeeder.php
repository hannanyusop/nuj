<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Seeder;

class NewPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //add permission 'Open Trip', 'Close Trip' , 'Master List Trip' , 'Billing Trip'
        $trip = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.trip',
            'description' => 'All Trip Permissions',
        ]);

        $trip->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.trip.open',
                'description' => 'Open Trip',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.trip.close',
                'description' => 'Close Trip',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.trip.master',
                'description' => 'Master List Trip',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.trip.billing',
                'description' => 'Billing Trip',
            ])
        ]);

        $role = Role::where('name', config('boilerplate.access.role.admin'))->first();
        $role->givePermissionTo('admin.trip.open');
        $role->givePermissionTo('admin.trip.close');
        $role->givePermissionTo('admin.trip.master');
        $role->givePermissionTo('admin.trip.billing');

    }
}
