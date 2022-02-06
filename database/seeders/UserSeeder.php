<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::create([
            'name' => 'Wissen Forum Admin User',
            'email' => 'chibuikemogbuagu@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);


        $role = new \App\Models\RoleUser;
        $role->role_id = \App\Models\Role::where('name', 'super-admin')->first()->id;
        $user->role()->save($role);
    }
}
