<?php

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cityArray = [
			[1, 'Satellite', 1, '380015'],
			[2, 'Bopal', 1, '380058'],
			[3, 'Gota', 1, '380081'],
		];
        foreach ($cityArray as $key => $value) {
            $createArray = array();
            
            $createArray['title']		= $value[1];
            $createArray['city_id']		= $value[2];
            $createArray['postal_code']	= $value[3];
			Area::create($createArray);
        }
    }
}