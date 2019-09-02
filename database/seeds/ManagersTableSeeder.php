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
            'first_name' => 'Michael',
            'last_name' => 'Selby',
            'email' => 'dev@michaelselby.me',
            'password' => bcrypt('admin2019'),
            'role' => '1',
            'state' => '1',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
