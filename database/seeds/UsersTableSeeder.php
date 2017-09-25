<?php

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
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@jpcommunity.com',
            'user_type' => 'is_admin',
            'password' => bcrypt('zxcv1234'),
        ]);
    }
}
