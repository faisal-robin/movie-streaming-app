<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public function run()
    {
       DB::table('users')->insert([
            [
                'name' => 'Faisal Robin',
                'email' => 'faisalrobin22@gmail.com',
                'user_type' => 'admin',
                'subscription_type' => null,
                'password' => Hash::make('12345678'),
            ],
           [
               'name' => 'Kaiser Jewel',
               'email' => 'kaiserjewel@gmail.com',
               'user_type' => 'user',
               'subscription_type' => 'premium',
               'password' => Hash::make('12345678'),
           ],
           [
               'name' => 'Mainuddin Tuhin',
               'email' => 'tuhin@gmail.com',
               'user_type' => 'user',
               'subscription_type' => 'basic',
               'password' => Hash::make('12345678'),
           ],

        ]);
    }
}
