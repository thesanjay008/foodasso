<?php

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $countryArray = array(
                            array('India', 'IN', '+91', 'Rupees', 'INR', '₹', NULL, 'active', NULL, '2020-01-07 06:56:01'),
							array('United States of America', 'US', '+1', 'Dollars', 'USD', '$', NULL, 'inactive', NULL, NULL),
                		);
		foreach ($countryArray as $key => $value) {
            $createArray = array();
            $createArray['title']			= $value[0];
            $createArray['iso_code']		= $value[1];
            $createArray['calling_code']	= $value[2];
            $createArray['currency']		= $value[3];
            $createArray['currency_code']	= $value[4];
            $createArray['currency_symbol']	= $value[5];
            $createArray['slug']			= $value[6];
            $createArray['status']			= $value[7];
            Country::create($createArray);
        }
    }
}