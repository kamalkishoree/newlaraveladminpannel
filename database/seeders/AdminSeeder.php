<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::Where('email', 'admin@example.com')->first();

        if(is_null($user))
        {
            $user = User::create([
                'name'      => 'Super Admin',
                'email'     => 'admin@quick.com',
                'mobile'    => '01689201370',
                'dial_code' => '+91',
                'password'  => bcrypt('12345678'),
                'status'    => 1
            ]);

            $user->assignRole('Admin');
        }

    }
}
