<?php

use Illuminate\Database\Seeder;

class CountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('counts')->insert([
            [
                'customer' => 0,
                'product' => 0,
                'sku' => 0,
                'sku_image' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]
        ]);
    }
}
