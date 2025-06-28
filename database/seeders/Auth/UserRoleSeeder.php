<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        User::find(1)->assignRole(config('boilerplate.access.role.admin'));

        $runner = Permission::where('name', 'staff.runner')->first()->id;
        $manager = Permission::where('name', 'staff.manager')->first()->id;
        $officer = Permission::where('name', 'staff.inhouse')->first()->id;
        $distributor = Permission::where('name', 'staff.distributor')->first()->id;
        $biacc = Permission::where('name', 'staff.biacc')->first()->id;

        $limbang = User::find(2);
        $limbang->assignRole(2);
        $limbang->syncPermissions([$distributor]);

        $miri = User::find(3);
        $miri->assignRole(2);
        $miri->syncPermissions([$distributor]);

        $lambak = User::find(4);
        $lambak->assignRole(2);
        $lambak->syncPermissions([$manager, $officer]);

        $kilanas = User::find(5);
        $kilanas->assignRole(2);
        $kilanas->syncPermissions([$manager, $officer]);

        $belait = User::find(8);
        $belait->assignRole(2);
        $belait->syncPermissions([$manager, $officer]);

        $staff_biacc = User::find(9);
        $staff_biacc->assignRole(2);
        $staff_biacc->syncPermissions([$manager, $biacc]);

        $run = User::find(6);
        $run->assignRole(2);
        $run->syncPermissions([$runner]);

        $this->enableForeignKeys();
    }
}
