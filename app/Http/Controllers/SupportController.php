<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SupportController extends Controller
{
	/*
	 *----------------------
	 * Clear Cache
	 *----------------------
	*/
	public function clear_cache(){
		Artisan::call('config:clear');
		echo 'Config cleared </br>';
		Artisan::call('route:clear');
		echo 'Route cleared </br>';
		Artisan::call('cache:clear');
		echo 'Cache cleared </br>';
		Artisan::call('view:clear');
		echo 'View cleared </br>';
    }
	
	/*
	 *----------------------
	 * Create Cache
	 *----------------------
	*/
	public function caches(){
		Artisan::call('config:cache');
		echo 'Config cached </br>';
		Artisan::call('route:cache');
		echo 'Route cached </br>';
		Artisan::call('optimize');
		echo 'Optimize';
    }
	
	/*
	 *----------------------
	 * Run Migration
	 *----------------------
	*/
	public function migration(){
		Artisan::call('migrate');
		return "Migrate run successfully!!";
    }
	
	/*
	 *----------------------
	 * Run Database Seeder
	 *----------------------
	*/
	public function seeding(){
		//Artisan::call('db:seed');
		return "Seeder run successfully!!";
    }
	
	/*
	 *----------------------
	 * Test Notification
	 *----------------------
	*/
	public function test_notification($to = '')
	{
		if(empty($to)){
			echo '<p>Error: Token required</p>'; exit;
		}
		$fcm_server_key = 'AAAAPi3A6Xk:APA91bEzBQGqIraaxnCtWWdH2P_LKyXheu1e12dPjzC9IhhUBDs8IAnF7c7OO2tGKW8BirN8-kbZ-Lvc0xGZO0U8Uxo9n_I9XuMXxzdaxN9MuDRhAJO5vxguJONoLiGpdZPW-Wl2c0uB';
		
		$data = [
			"click_action" 		=> "FLUTTER_NOTIFICATION_CLICK",
			"sound" 			=> "default",
			"content_available" => true,
			"mutable_content" 	=> true,
		];
		$sendArray = [
			"to" => $to,
			"notification" => ["title"=>"Hello ". (rand(1,1111)),"body"=>'Lorem ipsum dummy text here...'],
			//"data"=>$data
		];
		
		$sendArray2 = [
			"to" => $to, 
			"notification" => [
				"body" 	=> "sample body",
				"title" => "Hello ". (rand(1,1111)),
			], 
			"android" => [
				"priority" => "high",
			],
			"apns" => [
				"headers" => ["apns-priority" => "10",],
				"payload" => ["aps" => ["sound" => "default",],],
			],
			"data" => [
				"click_action" 		=> "FLUTTER_NOTIFICATION_CLICK",
				"sound" 			=> "default",
				"content_available" => true,
				"mutable_content" 	=> true,
			]
		];
		
		
		$headers = array ( 'Authorization: key=' . $fcm_server_key, 'Content-Type: application/json' );
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($sendArray));
		$result = curl_exec($ch);
		//$result = json_decode(curl_exec($ch), TRUE);
		
		if(curl_error($ch)){
			echo 'Request Error:' . curl_error($ch); exit;
		}
	
		curl_close ($ch);
		
		
		echo '<b>Payload :</b><br>';
		echo json_encode($sendArray);
		
		echo '<br><br><br><br><hr><b>Return Response:</b><br>';
		
		echo'<pre>'; print_r($result);
    }
}
