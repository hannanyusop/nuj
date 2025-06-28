<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\Office;
use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        $limbang = Office::create([
            'id' => 1,
            'code' => 'LMN',
            'name' => 'PUSAT LIMBANG',
            'is_drop_point' => 0,
            'is_receiver' => 1,
            'address' => 'Limbang, Sarawak',
        ]);

        $miri = Office::create([
            'id' => 2,
            'code' => 'MYY',
            'name' => 'PUSAT MIRI',
            'is_drop_point' => 0,
            'is_receiver' => 1,
            'address' => 'Miri Sarawak',
        ]);

        $lambak = Office::create([
            'id' => 3,
            'code' => 'L',
            'name' => 'PUSAT PUNGUTAN LAMBAK',
            'is_drop_point' => 1,
            'is_receiver' => 0,
            'address' => 'Lambak, Brunei',
        ]);

        $kilanas = Office::create([
            'id' => 4,
            'code' => 'K',
            'name' => 'PUSAT PUNGUTAN KILANAS',
            'is_drop_point' => 1,
            'is_receiver' => 0,
            'address' => 'Kilanas, Brunei',
        ]);

        $kb = Office::create([
            'id' => 5,
            'code' => 'B',
            'name' => 'PUSAT PUNGUTAN KUALA BELAIT',
            'is_drop_point' => 1,
            'is_receiver' => 0,
            'address' => 'Kuala Belait, Brunei',
        ]);

        $biacc = Office::create([
            'id' => 6,
            'code' => 'BIACC',
            'name' => 'BRUNEI INTERNATIONAL AIRPORT CARGO CENTRE',
            'is_drop_point' => 0,
            'is_receiver' => 0,
            'address' => 'WWVM+728, Bandar Seri Begawan, Brunei',
        ]);



        // Add the master administrator, user id of 1
        User::create([
            'id' => 1, // 'id' => '1
            'type' => User::TYPE_ADMIN,
            'name' => 'Super Admin',
            'email' => 'admin@mail.com',
            'password' => 'secret',
            'email_verified_at' => now(),
            'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
            'phone_number' => '010'.rand(1000000,9999999),
            'active' => true,
        ]);

        if (app()->environment(['local', 'testing'])) {
            User::create([
                'id' => 2,
                'type' => User::TYPE_ADMIN,
                'office_id' => $limbang->id,
                'name' => 'STAFF LIMBANG',
                'email' => 'limbang@mail.com',
                'password' => 'secret',
                'email_verified_at' => now(),
                'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
                'phone_number' => '010'.rand(1000000,9999999),
                'active' => true,
            ]);

            User::create([
                'id' => 3,
                'type' => User::TYPE_ADMIN,
                'office_id' => $miri->id,
                'name' => 'STAFF MIRI',
                'email' => 'miri@mail.com',
                'password' => 'secret',
                'email_verified_at' => now(),
                'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
                'phone_number' => '010'.rand(1000000,9999999),
                'active' => true,
            ]);

            User::create([
                'id' => 4,
                'type' => User::TYPE_ADMIN,
                'office_id' => $lambak->id,
                'name' => 'STAFF LAMBAK ',
                'email' => 'lambak@mail.com',
                'password' => 'secret',
                'email_verified_at' => now(),
                'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
                'phone_number' => '010'.rand(1000000,9999999),
                'active' => true,
            ]);

            User::create([
                'id' => 5,
                'type' => User::TYPE_ADMIN,
                'office_id' => $kilanas->id,
                'name' => 'STAFF KINLANAS ',
                'email' => 'kilanas@mail.com',
                'password' => 'secret',
                'email_verified_at' => now(),
                'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
                'phone_number' => '010'.rand(1000000,9999999),
                'active' => true,
            ]);

            User::create([
                'id' => 6,
                'type' => User::TYPE_ADMIN,
                'office_id' => 0,
                'name' => 'RUNNER',
                'email' => 'runner@mail.com',
                'password' => 'secret',
                'email_verified_at' => now(),
                'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
                'phone_number' => '010'.rand(1000000,9999999),
                'active' => true,
            ]);

            User::create([
                'id' => 7,
                'type' => User::TYPE_USER,
                'office_id' => 0,
                'name' => 'HANNAN YUSOP',
                'email' => 'customer@mail.com',
                'password' => 'secret',
                'email_verified_at' => now(),
                'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
                'phone_number' => '010'.rand(1000000,9999999),
                'active' => true,
            ]);
        }

        User::create([
            'id' => 8,
            'type' => User::TYPE_ADMIN,
            'office_id' => $kb->id,
            'name' => 'STAFF KUALA BELAIT ',
            'email' => 'belait@mail.com',
            'password' => 'secret',
            'email_verified_at' => now(),
            'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
            'phone_number' => '010'.rand(1000000,9999999),
            'active' => true,
        ]);

        User::create([
            'id' => 9,
            'type' => User::TYPE_ADMIN,
            'office_id' => $biacc->id,
            'name' => 'STAFF BIACC',
            'email' => 'biacc@mail.com',
            'password' => 'secret',
            'email_verified_at' => now(),
            'default_drop_point' => Office::where('is_drop_point', 1)->first()->id,
            'phone_number' => '010'.rand(1000000,9999999),
            'active' => true,
        ]);

        $this->enableForeignKeys();
    }
}
