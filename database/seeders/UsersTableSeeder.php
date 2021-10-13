<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(config('admin.admin_name')) {
            User::firstOrCreate(
                ['email' => config('admin.admin_email')], [
                    'username' => config('admin.admin_name'),
                    'password' => bcrypt(config('admin.admin_password')),
                    'role'=>3
                ]
            );
        }
    }
}
