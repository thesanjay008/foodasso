<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 3; $i++) { 
	    	if($i == 0){
	    		$role = Role::where('name','developer')->first();
	    		$mobile = '1234567890';
	    		$password = '11111111';
	    	} else if($i == 1){
	    		$role = Role::where('name','superAdmin')->first();
	    		$mobile = '1234569078';
				$password = '11111111';
	    	} else if($i == 2){
	    		$role = Role::where('name','Outlet')->first();
	    		$mobile = '0987654321';
				$password = '12345678';
	    	} 
	    	$user = User::firstOrCreate([
			            'name' => $role->name,
			            'email' => $role->name.'@mail.com',
			            'mobile_number' => $mobile,
			            'password' => bcrypt($password),
			            'user_type' => $role->name,
			            'profile_image' => '',
			            'email_verified_at' => date('Y-m-d H:i:s'),
			        ]);
   			$user->assignRole([$role->id]);
    	}
    }
}
