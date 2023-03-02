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
        $this->call(PermissionTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        //$this->call(SettingTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(StateTableSeeder::class);
        $this->call(CityTableSeeder::class);
		$this->call(AreaTableSeeder::class);
        //$this->call(MenuCategoryTableSeeder::class);
        //$this->call(ProductTableSeeder::class);
    }
}