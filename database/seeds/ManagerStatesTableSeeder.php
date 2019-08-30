<?php

use Illuminate\Database\Seeder;

class ManagerStatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('manager_states')->insert([
            [
                'description' => 'Active',
                'html_description' => "<span style='color:green;'> Active </span> ",
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'description' => 'Inactive',
                'html_description' => "<span style='color:orange;'> Inactive </span> ",
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'description' => 'Deleted',
                'html_description' => "<span style='color:red;'> Deleted </span> ",
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]
        ]);
    }
}
