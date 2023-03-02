<?php

use Illuminate\Database\Seeder;
use App\Models\City;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cityArray = [
			[1, 'Ahmedabad', 12],
			[2, 'Mumbai', 22],
			[3, 'Delhi', 10],
			[4, 'Bangalore', 17],
			[5,'Hyderabad',36],
			[6,'Chennai',35],
			[7,'Kolkata',41]
		];
        foreach ($cityArray as $key => $value) {
            $createArray = array();
            
            $createArray['title']		= $value[1];
            $createArray['state_id']	= $value[2];
            $createArray['status']		= 'active';
            City::create($createArray);
        }
    }
}