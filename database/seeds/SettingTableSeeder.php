<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;
class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $settingArray = [
        	['name'=>'site_name', 'value' => 'Demo', 'status' => 'active' ],
        	['name'=>'contact_no', 'value' => '+91 1234567890', 'status' => 'active' ],
        	['name'=>'site_email', 'value' => 'demo@example.com', 'status' => 'active' ],
            ['name'=>'site_short_name', 'value' => 'Demo', 'status' => 'active' ],
            ['name'=>'app_version', 'value' => '1.0.0', 'status' => 'active' ],
            ['name'=>'copy_rights_credit_line', 'value' => 'Demo', 'status' => 'active' ],
            ['name'=>'copy_rights_year', 'value' => '2020-2021', 'status' => 'active' ],
            ['name'=>'google_map_api_key', 'value' => '111', 'status' => 'active' ],
            ['name'=>'moderation_queue', 'value' => 'on', 'status' => 'active' ],
            ['name'=>'fcm_server_key', 'value' => 'AAAAUot9YAc:APA91bF7IpPFZNq_3_FbXCTV67pbAyRgIaOm1wnnJK1tmQOfF', 'status' => 'active' ],
            ['name'=>'address', 'value' => '123 main street', 'status' => 'active' ],
            ['name'=>'timing', 'value' => '9 AM - 10 PM', 'status' => 'active' ],
            ['name'=>'start_time', 'value' => '10', 'status' => 'active' ],
            ['name'=>'end_time', 'value' => '22', 'status' => 'active' ],
            ['name'=>'qr_code', 'value' => '', 'status' => 'active' ],
            ['name'=>'accept_order', 'value' => 'Yes', 'status' => 'active' ],
            ['name'=>'accept_booking', 'value' => 'Yes', 'status' => 'active' ],
            ['name'=>'accept_table_order', 'value' => 'Yes', 'status' => 'active' ],
            ['name'=>'accept_table_payment', 'value' => 'Yes', 'status' => 'active' ],
            ['name'=>'show_booking_home', 'value' => 'Yes', 'status' => 'active' ],
            ['name'=>'show_menu_home', 'value' => 'Yes', 'status' => 'active' ],
            ['name'=>'show_menu_header', 'value' => 'Yes', 'status' => 'active' ],
            ['name'=>'show_booking_header', 'value' => 'Yes', 'status' => 'active' ],
			['name'=>'logo', 'value' => 'default/logo.png', 'status' => 'active' ],
			['name'=>'primary_color', 'value' => '#ea564a', 'status' => 'active' ],
			['name'=>'secondary_color', 'value' => '#ea564a', 'status' => 'active' ],
			['name'=>'facebook_profile', 'value' => '', 'status' => 'active' ],
			['name'=>'twitter_profile', 'value' => '', 'status' => 'active' ],
			['name'=>'instagram_profile', 'value' => '', 'status' => 'active' ],
			['name'=>'youtube_profile', 'value' => '', 'status' => 'active' ],
        ];
		foreach ($settingArray as $key => $value) {
			Setting::create($value);
        }
    }
}