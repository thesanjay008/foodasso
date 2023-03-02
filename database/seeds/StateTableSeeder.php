<?php

use Illuminate\Database\Seeder;
use App\Models\State;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stateArray = array(array(1, 'Andaman and Nicobar Islands', 1),
								array(2, 'Andhra Pradesh', 1),
								array(3, 'Arunachal Pradesh', 1),
								array(4, 'Assam', 1),
								array(5, 'Bihar', 1),
								array(6, 'Chandigarh', 1),
								array(7, 'Chhattisgarh', 1),
								array(8, 'Dadra and Nagar Haveli', 1),
								array(9, 'Daman and Diu', 1),
								array(10, 'Delhi', 1),
								array(11, 'Goa', 1),
								array(12, 'Gujarat', 1),
								array(13, 'Haryana', 1),
								array(14, 'Himachal Pradesh', 1),
								array(15, 'Jammu and Kashmir', 1),
								array(16, 'Jharkhand', 1),
								array(17, 'Karnataka', 1),
								array(18, 'Kenmore', 1),
								array(19, 'Kerala', 1),
								array(20, 'dasds', 1),
								array(21, 'Madhya Pradesh', 1),
								array(22, 'Maharashtra', 1),
								array(23, 'Manipur', 1),
								array(24, 'Meghalaya', 1),
								array(25, 'Mizoram', 1),
								array(26, 'Nagaland', 1),
								array(27, 'Narora', 1),
								array(28, 'Natwar', 1),
								array(29, 'Odisha', 1),
								array(30, 'Paschim Medinipur', 1),
								array(31, 'Pondicherry', 1),
								array(32, 'Punjab', 1),
								array(33, 'Rajasthan', 1),
								array(34, 'Sikkim', 1),
								array(35, 'Tamil Nadu', 1),
								array(36, 'Telangana', 1),
								array(37, 'Tripura', 1),
								array(38, 'Uttar Pradesh', 1),
								array(39, 'Uttarakhand', 1),
								array(40, 'Vaishali', 1),
								array(41, 'West Bengal', 1));
		foreach ($stateArray as $key => $value) {
            $createArray = array();
            $createArray['title']			= $value[1];
            $createArray['country_id']      = $value[2];
            $createArray['status']          = 'active';

            State::create($createArray);
        }
    }
}