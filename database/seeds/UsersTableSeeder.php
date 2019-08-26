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
        \DB::table('users')->insert([
            'email' => 'michaelselbygh@gmail.com',
            'password' => bcrypt('admin2019'),
            'uid' => 'NLES00001',
            'type' => '0',
            'role' => '0',
            'status' => '0',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
