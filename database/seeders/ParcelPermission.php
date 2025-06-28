<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Seeder;

class ParcelPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //add permision parcel with children 'Receive Parcel', 'Assign Parcel', 'Deliver Parcel', 'Return Parcel'
        $parcel = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.parcel',
            'description' => 'All Parcel Permissions',
        ]);

        $parcel->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.parcel.receive',
                'description' => 'Receive Parcel',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.parcel.assign',
                'description' => 'Assign Parcel',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.parcel.deliver',
                'description' => 'Deliver Parcel',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.parcel.return',
                'description' => 'Return Parcel',
            ])
        ]);


    }
}
