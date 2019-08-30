<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories')->insert([
            [
                'description' => 'Lighting',
                'slug' => 'lighting',
                'parent' => 0,
                'level' => 1,
                'cna' => '2|',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'description' => 'LEDs',
                'slug' => 'leds',
                'parent' => 1,
                'level' => 2,
                'cna' => NULL,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]
        ]);
    }
}
