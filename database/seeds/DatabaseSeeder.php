<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(CustomersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ManagerStatesTableSeeder::class);
        $this->call(ManagersTableSeeder::class);
        $this->call(SiteSettingsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(CountsTableSeeder::class);
    }
}
