<?php

use App\User;
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
       User::insert([
            'email' => 'admin@email.com',
            'username' => 'admin',
            'role' => 2,
            'password' => Hash::make('password'),
        ]);
    }
}
