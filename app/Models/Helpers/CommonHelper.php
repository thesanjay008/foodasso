<?php

namespace App\Models\Helpers;

use Illuminate\Support\Facades\Storage;
use DB;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Booking;

trait CommonHelper
{
    //public variables
    public $media_path = 'uploads/';
	
    
	/**
	* GET Directory
	*/
	public function get_upload_directory($folder = ''){
	    $year 	= date("Y");
		$month 	= date("m");
		$folder = $folder ? $folder . '/' : '';
		
		$media_path1 = public_path($this->media_path . $folder . $year.'/');
		$media_path2 = public_path($this->media_path . $folder . $year .'/'. $month.'/');
		$media_path3 = $this->media_path . $folder . $year .'/'. $month.'/';
		
		if(!is_dir($media_path1)){
			mkdir($media_path1, 0755, true);
		}
		if(!is_dir($media_path2)){
			mkdir($media_path2, 0755, true);
		}
		return $media_path3;
	}
	
	/**
	* Save different type of media into different folders
	*/
	public function saveMedia($file, $folder = '',  $type = '', $width = '', $height = ''){
		
		if(empty($file)){ return; }
		
		$upload_directory 	= $this->get_upload_directory($folder);
		$name 				= md5($file->getClientOriginalName() . time() . rand());
		// $extension 			= $file->getClientOriginalExtension();
        $extension          = $file->guessExtension();
		$fullname 			= $name . '.' . $extension;
		$thumbnail 			= $name .'150X150.'. $extension;
		
		// CREATE THUMBNAIL IMAGE
		// $img = Image::make(public_path($fullname))->resize(150, 150)->insert(public_path($thumbnail));
		
        if($type == ''){
			$file->move(public_path($upload_directory),$fullname);
            return $upload_directory . $fullname;
        } else if($type == 'image'){
            DB::beginTransaction();
            try{
                $path = Storage::disk('s3')->put('images/originals', $file,'public');
                DB::commit();
                return $path;
            } catch(\Exception $e){
                DB::rollback();
                $path = '-';
                return $path;
            } 
        } else {
            return false;
        }
    }

    public function sendOTP($phone_number, $message)
    {
        
    }

    // CREATE PAYMENT ORDER
    public static function gateway_create_order($data = array())
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/payment_links');
		curl_setopt($ch, CURLOPT_USERPWD, config('constants.GATEWAY_KEY') . ':' . config('constants.GATEWAY_VALUE'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 80);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
		$posts_result = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);
        return $posts_result;
    }


    // VERIFY PAYMENT ORDER
    public static function verify_order($data = array())
    {
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_URL, 'https://services.ameriabank.am/VPOS/api/VPOS/GetPaymentDetails');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 80);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
		$posts_result = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);
        return $posts_result;
    }
	
	// After Verify Order Run this Function
	public static function order_finalization($order_id = 0, $user = []){
		
		$payment_method	= 'Cash';
		$templateItems 	= [];
		
		// GET ORDER DATA
		$order_data = Order::where(['id'=>$order_id])->first();
		if($order_data){
			
			// GET Order Items
			$order_items = OrderItem::where(['order_id'=>$order_data->id])->get();
			foreach($order_items as $key=> $list){
				
				// validate Items
				$product = Product::where(['id'=> $list->product_id])->first();
				if(!empty($product)){
					$templateItems[] = [
					  'image' => $product->image ? asset('public/'. $product->image) : asset('public/'.env("DEFAULT_IMAGE")),
					  'title' => $product->title,
					  'price' => $product->price,
					];
				}
			}
			
			// Delete existing cart
			Cart::where(['user_id'=>$user->id])->delete();
			
			$order_data->status = 'New';
			if($order_data->payment_method_id == 2){
				$payment_method	= 'Card';
				$order_data->payment_status = '1';
			}
            $order_data->update();
            DB::commit();
			
			// send SMS
			//$message = trans('customer_api.dear'). ' '. $user->name .',\r\n '. trans('customer_api.your_order_has_been_successfully_created') .'\r\n'. trans('customer_api.thank_you_for_choosing_amen_inch');
			//CommonHelper::sendMessage($user->country_code. $user->phone_number, $message);
			
			
			// Send Push Notification
			$message = trans('customer_api.you_have_new_order_from_customer') .' '. $user->name;
			//$restaurant = User::where('id', $order_data->owner_id)->first();
			//CommonHelper::send_notification($restaurant, 'New Order', $message, '1', $order_id, $order_data);
			

			// send Mail
			$template_data = new \stdClass();
			$template_data->user				= $user;
			$template_data->date				= date('F d, Y');
			$template_data->time				= date('h: i');
			$template_data->order_id			= $order_id;
			$template_data->order_items			= $templateItems;
			$template_data->restaurant_logo		= asset(env("DEFAULT_IMAGE"));
			$template_data->shipping_address	= $order_data->shipping_address;
			$template_data->shipping_charge		= $order_data->shipping_charge;
			$template_data->payment_method 		= $payment_method;
			$template_data->total				= $order_data->total;
			$template_data->grand_total			= $order_data->grand_total;
			$template_data->delivery_fee		= '0.00';
			
			CommonHelper::sendMail($user->email, 'Order created Successfully', 'email-templates.create-order-customer', $template_data);
		}
	}
	
	// After Verify Booking Run this Function
	public static function booking_finalization($item_id = 0, $user = []){
		
		$payment_method	= 'Cash';
		$templateItems 	= [];
		
		// GET BOOKING DATA
		$data = Booking::where(['id'=>$item_id])->first();
		if($data){
			
			$data->status = 'New';
			$data->process_completed = 'Yes';
            $data->update();
            DB::commit();
			
			// send SMS
			$message = 'Your table has been booked successfully!!';
			CommonHelper::sendMessage($data->phone_number, $message);
			
			
			// send Mail
			$template_data = new \stdClass();
			//$template_data->user				= $user;
			$template_data->date				= date('F d, Y');
			$template_data->time				= date('h: i');
			$template_data->payment_method		= date('h: i');
			
			//CommonHelper::sendMail($user->email, 'Order created Successfully', 'email-templates.create-order-customer', $template_data);
		}
	}
	
	// VALIDATE PHONE NUMBER
	public static function validate_phoneNumber($country_code = '+91', $phone_no = ""){
		if(empty($country_code)){ return; }
		if(empty($phone_no)){ return; }
		
		$number = str_replace(' ', '', $phone_no);
		$first_number = substr($number, 0, 1); 
		if ($first_number == 0) {
		  $number = substr($number, 1, 999); 
		}
		return $result = preg_replace("/^\+?{$country_code}/", '',$number);
		
	}
	
	// SEND MESSAGE
	public static function sendMessage($to, $message = ""){
		
		// send SMS
		return CommonHelper::sendSMS($to, $message);
		
		// send WhatsApp
		return CommonHelper::sendWhatsApp($to, $message);
	}
	
	// SEND SMS
	public static function sendSMS($to, $message = ""){
		if(empty($to)){ return; }
		if(empty($message)){ return; }
		
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"http://sms.hspsms.com/sendSMS?username=". config('constants.SMS_USERNAME') ."&message=". urlencode($message) ."&sendername=". config('constants.SMS_SENDER') ."&smstype=TRANS&numbers=$to&apikey=". config('constants.SMS_TOKEN'));
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close ($ch);
			return $response;
			
		} catch (Exception $e) {
			return false;
		}
	}
	
	// SEND SMS
	public static function sendWhatsApp($to, $message = ""){
		if(empty($to)){ return; }
		if(empty($message)){ return; }
		
		try {

			
			
		} catch (Exception $e) {
			return false;
		}
	}

	// SEND MAIL
	public static function sendMail_OLD($email = '', $subject = '', $template = '', $template_data){
		$data = array('email'=>$email,'subject'=>$subject, 'template_data'=>$template_data);
		
		try {
			Mail::send($template, $data, function($mail) use ($data) {
			  $mail->to($data['email'], '')->subject($data['subject']);
			  $mail->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
			  //$mail->htmlData = $data;
			});
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	// SEND MAIL
	public static function sendMail($email = '', $subject = '', $template = '', $template_data){
		$data = array('email'=>$email,'subject'=>$subject, 'template_data'=>$template_data);
		
		try {
			$url = 'https://api.sendgrid.com/v3/mail/send';
			$body = view($template, compact('template_data'))->render();
			$authorization = "Authorization: Bearer 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";
			
			$data = array (
			  'personalizations' => array (0 => array ('to' => array (0 => array ('email' => $email,),),),),
			  'from' => array ('name' => config('constants.APP_NAME'),'email' => 'mail@foodasso.com',),
			  'subject' => $subject,
			  'content' => array (0 => array ('type' => 'text/html','value' => $body,),),
			);
			$postdata = json_encode($data);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, 80);
			$response = curl_exec($ch);
			
			if(curl_error($ch)){
				$return = false;
			} else{
				$return = $response;
			}
			curl_close($ch);
			return $return;
		} catch (Exception $e) {
			return false;
		}
	}
	
	// SEND MAIL
	public static function send_contactMail($email = '', $subject = '', $template = '', $template_data){
		$data = array('email'=>$email,'subject'=>$subject, 'template_data'=>$template_data);
		
		try {
			Mail::send($template, $data, function($mail) use ($data) {
			  $mail->to($data['email'], '')->subject($data['subject']);
			  $mail->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
			  //$mail->htmlData = $data;
			});
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	// SEND MOBILE NOTIFICATION
	public static function send_notification($user = '', $title = '', $message = '', $type = '', $type_id = '', $data = ''){
		$fcm_server_key   = Setting::get('fcm_server_key');
		$token          = ($user->device_detail) ? $user->device_detail->device_token : '';
		
		$data = [
		  //"user_id"   		=> (string) $user->id,
		  //"type"      		=> (string) $type,
		  //"type_id"   		=> (string) $type_id,
		  //"date_time" 		=> date('Y-m-d H:i:s'),
		  "click_action" 		=> 'FLUTTER_NOTIFICATION_CLICK',
		  "sound" 				=> "default",
		  "content_available" 	=> true,
		  "mutable_content" 	=> true,
		];
		
		try {
		  $insert = Notifications::create($data);
		  if($insert){
			if($insert->id && $token){
			  $data['notification_id'] = (string) $insert->id;
			  
			  $sendArray = [
				"to" => $token,
				"notification" => ["title"=>$title,"body"=>$message],
				//"data"=>$data,
			  ];
			  
			  $sendArray__ = [
					"to" => $token, 
					"notification" => [
						"body" => $message,
						"title" => $title,
					], 
					"android" => [
						"priority" => "high",
					],
					"apns" => [
						"headers" => ["apns-priority" => "10",],
						"payload" => ["aps" => ["sound" => "default",],],
					],
					"data" => [
						"click_action" => "FLUTTER_NOTIFICATION_CLICK",
						"sound" => "default",
						"content_available" => true,
						"mutable_content" => true,
					],
			  ];

			  $headers = array ( 'Authorization: key=' . $fcm_server_key, 'Content-Type: application/json' );
			  $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
			  curl_setopt( $ch,CURLOPT_POST, true ); 
			  curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
			  curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true ); 
			  curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($sendArray));
			  $result = json_decode(curl_exec($ch), TRUE);
			  curl_close ($ch);
			  if(!empty($result) && $result['success'] == 1){
				$insert->is_sent = 1;
				$insert->update();
				return $result;
			  }else{
				return FALSE;
			  }
			}
		  }
		} catch (\Exception $e) { 
		  return FALSE;
		}
	}


    // get vendor notifications list
    public function get_notifications($owner_type = '', $owner_id = ''){
        $notifications = [];
        if($owner_type && $owner_type != '' && $owner_id && $owner_id != ''){

            if($owner_type == 'HomeNursing'){
                $notifications = Notification::where([
                    'user_id' => $owner_id,])
                ->whereIn('type', ['HomeNursing'])
                ->get()->toArray();
            } else {
                $notifications = Notification::where(
                [
                    'type' => $owner_type,
                    'user_id' => $owner_id,
                ])->get()->toArray();
            }

            
        }
        return $notifications;
    }

}