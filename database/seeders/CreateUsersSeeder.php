<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
            'name' => 'Admin',
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'type' => 1,
            ],
            [
            'name' => 'Cashier 1',
            'username' => 'cashier',
            'password' => bcrypt('123456'),
            'type' => 0,
            ],

        ];
        
        foreach($users as $key => $user){
            User::create($user);
        }
            
    }
}
