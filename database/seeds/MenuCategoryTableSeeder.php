<?php

use Illuminate\Database\Seeder;
use App\Models\MenuCategory;

class MenuCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
			['Pizza', '1', '2'],
			['Garlic Bread', '2', '2'],
			['Sides', '3', '2'],
			['Combos', '4', '2'],
			['Dessert', '5', '2'],
			['Drinks', '6', '2'],
		];
        foreach ($data as $key => $value) {
            $createArray = array();
            
            $createArray['title:en']	= $value[0];
            $createArray['priority']	= $value[1];
            $createArray['owner_id']	= $value[2];

			MenuCategory::create($createArray);
        }
    }
}