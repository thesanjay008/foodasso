<?php

namespace App\Http\Controllers\Api;

use App;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use Twilio\Rest\Client;
use Authy\AuthyApi;
use Mail;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM,Settings;

use App\Models\Notifications;
use Edujugon\PushNotification\PushNotification;
use App\Models\MessageLog; // For message report
use App\Models\User; // For message report


class BaseController extends Controller
{
  public function __construct()
  {
    if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
      App::setLocale($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    }
  }

  /**
   * success response method.
   *
   * @return \Illuminate\Http\Response
   */
  
  public function sendResponse($result = [],$message,$status= '200'){
  	$response = [
                  'success' => "1",
                  'status'  => $status,
                  'message' => $message,
                  'data' => new \stdClass(),
                ];

    if(!empty($result)){
      $response['data'] = $result;
    }
    return response()->json($response, 200);
  }
  
  /**
   * success response method.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendArrayResponse($result = [],$message,$status= '200'){
  	$response = [
                  'success' => "1",
                  'status'  => $status,
                  'message' => $message,
                  'data' => [],
                ];

    if(!empty($result)){
      $response['data'] = $result;
    }
    return response()->json($response, 200);
  }
  
  /**
   * return error response.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendError($result = [],$message, $code = 200 , $status= '201'){
    $response = [
                  'success' => "0",
                  'status'  => $status,
                  'message' => $message,
                  'data' => new \stdClass(),
                ];

    if(!empty($result)){ $response['data'] = $result; }
    return response()->json($response, $code);
  }

  /**
   * return error response.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendArrayError($result = [],$message, $code = 200 , $status= '201'){
    $response = [
                  'success' => "0",
                  'status'  => $status,
                  'message' => $message,
                  'data'    => array(),
                ];

    if(!empty($result)){ $response['data'] = $result; }
    return response()->json($response, $code);
  }
  /**
  * return validation error response.
  *
  * @return \Illuminate\Http\Response
  */
  public function sendValidationError($result = [],$message, $code = 200 , $status= '201'){
  	$response = [
                  'success' => "0",
                  'status'  => $status,
                  'message' => $message,
                  'data' => new \stdClass(),
                ];

    if(!empty($result)){ $response['data'] = $result; }
    return response()->json($response, $code);
  }
  /**
   * special conditions
   *
   * @return \Illuminate\Http\Response
   */
  public function sendException($result = [],$message,$status= '201'){
    $response = [
        'success' => "1",
        'status'  => $status,
        'message' => $message,
        'data' => new \stdClass(),
    ];

    if(!empty($result)){ $response['data'] = $result; }
    return response()->json($response, 200);
  }

  //SEND MAIL
  public function sendMailNew($email = '', $subject = '', $body = ''){
    $data = array('email'=>$email,'subject'=>$subject, 'body'=>$body);
    Mail::send('email-templates.forgot-password', $data, function($mail) use ($data) {
      $mail->to($data['email'], '')->subject($data['subject']);
      $mail->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
      $mail->htmlData = $data;
    });
    return true;
  }

  // SEND MOBILE NOTIFICATION
  public function sendNotificationNew($user = '', $title = '', $message = '', $type = '', $type_id = '', $data = ''){

    $fcm_server_key = Settings::get('fcm_server_key');
    $token          = ($user->device_detail) ? $user->device_detail->device_token : '';
    
    $data = [
      "user_id"   => (string) $user->id,
      "type"      => (string) $type,
      "type_id"   => (string) $type_id,
      "date_time" => date('Y-m-d H:i:s'),
    ];
    
    try {
      $insert = Notifications::create($data);
      if($insert){
        if($insert->id && $token){
          $data['notification_id'] = (string) $insert->id;
          
          $sendArray = [
            "to" => $token,
            "notification" => ["title"=>$title,"body"=>$message],
            "data"=>$data
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

  public function sendMessage($invite_id, $message_type, $to_mobile_number, $country_code, $slug = ""){
    try {
      $invitation = Invitation::find($invite_id);

      $couple_name = $invitation['detail']['bride_name'].' & '.$invitation['detail']['groom_name'];
      $messageText = '';

      if($message_type == 'uninvited'){
          $messageText = 'You are uninvited for '.$couple_name.' wedding ceremony';
      } else if($message_type == 'invite'){
          $dynamic_url = $invitation->invite_url;
          $url   = route('invitation',[$dynamic_url,$slug]);
          $url1 = "http://sayarataxi.com/";
          $messageText = "You are invited for the wedding ceremony of $couple_name for more information please click on link : $url";
          \Log::info("URL : ".$url);
      } else if($message_type == 'cancelled'){
          $messageText = 'Your invitation has been cancelled for '.$couple_name.' wedding ceremony';
      }
      \Log::info($messageText);
      if(Settings::has('twilio_sid')){
        $sid = Settings::get('twilio_sid');
      } else {
        $sid = config('services.twilio.sid');
      }
      if(Settings::has('twilio_auth_token')){
        $token = Settings::get('twilio_auth_token');
      } else {
        $token = config('services.twilio.token');
      }
      if(Settings::has('twilio_from')){
        $twilio_from  = Settings::get('twilio_from');
      } else {
        $twilio_from  = config('services.twilio.from');
      }
      if(Settings::has('twilio_whatsapp_from')){
        $twilio_whatsapp_from  = Settings::get('twilio_whatsapp_from');
      } else {
        $twilio_whatsapp_from  = config('services.twilio.whatsapp_from');
      }

      $twilio = new Client($sid, $token);
      \Log::info('Flag - '.$sid." - ". $token." - ".$twilio_whatsapp_from);
      //$this->authy = new AuthyApi(config('services.twilio.authy_api_key'));

      //$phone = $this->authy->phoneInfo($to_mobile_number, $country_code );
      //if($phone->bodyvar('type') == "cellphone"){
      if($country_code != ''){
        $to_mobile_number = $country_code.''.$to_mobile_number;
      }
      \Log::info("callback url : ".route('message.status'));
      if($invitation->package_type == 'sms'){
          //send sms
          $from = $twilio_from;
          $message = $twilio->messages->create(
                      $to_mobile_number,
                     [
                        'from' => $from,
                        'body' => $messageText,
                        "statusCallback" => route('message.status')
                     ]
                 );
      } else if($invitation->package_type == 'whatsapp'){
          //send whatsapp message
          $from = $twilio_whatsapp_from;
          $message = $twilio->messages
                      ->create("whatsapp:".$to_mobile_number, // to
                              [
                                "from" => 'whatsapp:' . $from,//from_number
                                'body' => $messageText,
                                //'MediaUrl' => "https://contenthub-static.grammarly.com/blog/wp-content/uploads/2017/10/thank-you-760x400.jpg",
                                "statusCallback" => route('message.status')
                                //"statusCallbackMethod" => 'POST'
                                //asset($user->invitation->invite_image)
                              ]
                      );
      }
      \Log::info('message status : '.$message->sid);
      if($message->sid == ''){
        return "false";
      }
      /* Save to SMS Report */
        $message_report_array = [
          'branch_id' => @$invitation->branch->id,
          'invitation_id' => @$invite_id,
          'package_type' => @$invitation->package_type,
          'message_type' => @$message_type,
          'message_text' => @$messageText,
          'message_to' => @$to_mobile_number,
        ];
        MessageLog::create($message_report_array);
      /* Save to SMS Report */

      /**/
      return $message->sid;
      /*} else {
        //start logging for not valid number
        return false;
      }  */
    } catch (\Twilio\Exceptions\RestException $e) {
      return "false";
    } catch (Exception $e) {
      return "false";
    }
  }
  public function randomPassword($length = 8) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }
  /**
   * send notofication
   *
   * @return \Illuminate\Http\Response
   */
  public function sendNotification($user = '',$title,$body,$type,$data = ''){
    \Log::info("title : ".$title." - Body : ".$body." - type : ".$type);
    \Log::info("Notification for ".json_encode($user->device_detail));
    $token = ($user->device_detail) ? $user->device_detail->device_token : '';
    //Save notification in DB
    $notify = new Notifications();
    $notify->user_id  = $user->id;
    $notify->title    = $title;
    $notify->message  = $body;
    $notify->save();
    
    if($type == ''){
      $type = "1";
    }
    $fcm_key = Settings::get('fcm_server_key');
    \Log::info("FCM Key : ".$fcm_key);
    if($user->device_detail){
      if($user->device_detail->device_type == 'iPhone'){
        //ios notification
        $push = new PushNotification('apn');
        $message = [
            'aps' => [
                'alert' => [
                    'title' => $title,
                    'body' => $body
                ],
                'sound' => 'default',
                'badge' => 1
            ],
            'type' => $type
        ];
        $push->setMessage($message)
            ->setDevicesToken([
                $token
            ]);
        $push = $push->send();
        return $push->getFeedback();
      }
      if($user->device_detail->device_type == 'android'){
        //android notification
        $push = new PushNotification('fcm');
        $message = [             
                     'data' => [
                             'title'=> $title,
                             'message'=> $body,
                             'type' => "2"
                             ]
                    ];
        $push->setMessage($message)
                            ->setApiKey($fcm_key) // only if you are using GCM/FCM
                            ->setDevicesToken([$token])
                            ->send()
                            ->getFeedback();
        return $push;
      }
    }
  }
  
  public function sendPaginateResponse($result = [],$message,$status= '200'){
    // return $result;
    $response = [
                  'success' => "1",
                  'status'  => $status,
                  'message' => $message, 
                  'data' => [], 
                ];

    if(!empty($result)){
      $response['data'] = $result->items();
      /*$response['links'] = [
          'first' => $result->url(1),
          'last' => $result->url($result->lastPage()),
          'prev' => ($result->previousPageUrl()) ? $result->previousPageUrl() : "",
          'next' => ($result->nextPageUrl()) ? $result->nextPageUrl() : "",
        ];*/
      $response['meta'] = [
          'current_page' => (string) $result->currentPage(), 
          // 'from' => '', 
          'last_page' => (string) $result->lastPage(), 
          // 'path' => $result->url(1), 
          'per_page' => (string) $result->perPage(), 
          // 'to' => '', 
          'total' => (string) $result->total(), 
        ];
    }
    return response()->json($response, 200);
  }

  // GET USER DETAILS
  public function userValidate($user_id = null){
    $result = User::where(['id'=> $user_id,'status'=>'active'])->first();
    if(empty($result)){
      return 0;
    }
    return $user_id;
  }
}
