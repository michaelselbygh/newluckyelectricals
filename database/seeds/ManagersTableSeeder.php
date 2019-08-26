<?php

use Illuminate\Database\Seeder;

class ManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('managers')->insert([
            'id' => 'NLES00001',
            'first_name' => 'Michael',
            'last_name' => 'Selby',
            'email' => 'michaelselbygh@gmail.com',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
