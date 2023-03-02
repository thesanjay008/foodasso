<?php

namespace App\Http\Controllers\Api\Customer;

use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderItem;
use App\Http\Resources\CartResource;
use App\Http\Resources\CheckoutResource;

class CartController extends BaseController
{

  /**
   * CARTS
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request){
      
    $page   = $request->page ?? 1;
    $count  = $request->count ?? '10000';

    if ($page <= 0){ $page = 1; }
    $offset = $count * ($page - 1);

    $user_id = Auth::user()->id;
    if(empty($user_id)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    try{
        $query = Cart::query();
        $query = $query->where(['user_id'=>$user_id])->orderBy('id', 'DESC')->offset($offset)->limit($count)->get();
        
        if($query){
          $details = (object) array('items' => $query);
          $details->total_item = Cart::where(['user_id'=>$user_id])->get()->sum("quantity");
          $details->tax = '0.00';
          $details->total_amount = Cart::where(['user_id'=>$user_id])->get()->sum("total");

          return $this->sendArrayResponse(new CartResource($details), trans('customer_api.data_found_success'));
        }
        return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
    }catch (\Exception $e) { 
      DB::rollback();
      return $this->sendError('', $e->getMessage()); 
    }
  }

  /**
   * Registration api
   *
   * @return \Illuminate\Http\Response
   */
  public function add(Request $request)
  {
    $quantity = $request->quantity ?? '1';
    $validator = Validator::make($request->all(), [
      'product_id'  => 'required|exists:products,id',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }

    
    $user = Auth::user()->id;
    if(empty($user)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    // EMPTY CART
    if($request->clear_cart == 1){
      Cart::where('user_id', $user)->delete();
    }

    $vendor_validate = Cart::where('owner_id', '!=', $request->owner_id)->where('user_id', $user)->first();
    if(!empty($vendor_validate)){
      return $this->sendError('',trans('customer_api.remove_old_vendor_items'), '200', '202');
    }

    $checkinINcart = Cart::where(['product_id'=>$request->product_id, 'user_id'=>$user])->first();
    if(!empty($checkinINcart)){
      return $this->sendError('',trans('customer_api.item_already_in_cart'));
    }

    DB::beginTransaction();
    try {
      $product = Product::where(['id'=> $request->product_id,'status'=>'active'])->first();
      if($product){
        $data = array(
          'product_id'    => $product->id,
          'user_id'       => $user,
          'owner_id'      => $product->owner_id,
          'quantity'      => $quantity,
          'price'         => $product->price,
          'total'         => $product->price * $quantity,
          'date'          => date('Y-m-d'),
        );
        $return = Cart::create($data);
        DB::commit();

        $success['id']              =  (string)$return->id;
        $success['title']           =  (string)$product->title;
        $success['image']           =  $product->image ? asset($product->image) : '';
        $success['description']     =  (string)$product->description;
        $success['product_id']      =  (string)$return->product_id;
        $success['quantity']        =  (string)$return->quantity;
        $success['price']           =  (string)$product->price;
        $success['total']           =  (string)$return->total;
        
        return $this->sendResponse($success, trans('customer_api.data_added_success'));
      }
      else{
        DB::rollback();
        return $this->sendError('',trans('auth.data_added_error'));
      }
    }catch (Exception $e) {
      DB::rollback();
      return $this->sendException($this->object,$e->getMessage());
    }
    return $this->sendError('',trans('customer_api.data_added_error'));
  }

  /**
   * UPDATE CART
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $quantity = $request->quantity ?? '1';
    $validator = Validator::make($request->all(), [
      'product_id'  => 'required|exists:products,id',
      'quantity'    => 'required',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }
    
    $user = Auth::user()->id;
    if(empty($user)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    DB::beginTransaction();
    try {
      $product = Product::where(['id'=> $request->product_id,'status'=>'active'])->first();
      if($product){
        
        if($quantity > $product->quantity){
          return $this->sendError('',trans('customer_api.out_of_quantity'));
        }

        $data = array(
          'quantity'      => $quantity,
          'price'         => $product->price,
          'total'         => $product->price * $quantity,
        );

        $cartItem = Cart::where(['product_id'=>$request->product_id, 'user_id'=>$user])->first();
        if($cartItem){
          
          $cartItem->quantity = $quantity;
          $cartItem->price    = $product->price;
          $cartItem->total    = $product->price * $quantity;

          $cartItem->save();
          DB::commit();
          
          $success['id']              =  (string)$cartItem->id;
          $success['title']           =  (string)$product->title;
          $success['image']           =  $product->image ? asset($product->image) : '';
          $success['description']     =  (string)$product->description;
          $success['product_id']      =  (string)$cartItem->product_id;
          $success['quantity']        =  (string)$cartItem->quantity;
          $success['price']           =  (string)$cartItem->price;
          $success['total']           =  (string)$cartItem->total;
          return $this->sendResponse($success, trans('customer_api.data_added_success'));
        }else{
          return $this->sendError('',trans('customer_api.invalid_item'));
        }
      }
      else{
        DB::rollback();
        return $this->sendError('',trans('auth.data_added_error'));
      }
    }catch (Exception $e) {
      DB::rollback();
      return $this->sendException($this->object,$e->getMessage());
    }
    return $this->sendError('',trans('customer_api.data_added_error'));
  }

  /**
   * DELETE CART
   *
   * @return \Illuminate\Http\Response
   */
  public function delete(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'product_id'  => 'required|exists:products,id',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }
    
    $user = Auth::user()->id;
    if(empty($user)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    DB::beginTransaction();
    try {
      
      $cartItem = Cart::where(['product_id'=>$request->product_id, 'user_id'=>$user])->first();
      if($cartItem){
        $delete = Cart::where(['product_id'=>$request->product_id, 'user_id'=>$user])->delete();
        if($delete){
          DB::commit();
          return $this->sendResponse('', trans('customer_api.data_delete_success'));
        }
        DB::rollback();
        return $this->sendResponse('', trans('customer_api.data_delete_failed'));
      }else{
        return $this->sendError('',trans('customer_api.invalid_item'));
      }

    }catch (Exception $e) {
      DB::rollback();
      return $this->sendException($this->object,$e->getMessage());
    }
    return $this->sendError('',trans('customer_api.data_added_error'));
  }

  /**
   * CHECKOUT
   * @return \Illuminate\Http\Response
   */
  public function checkout(Request $request)
  {
    $user_id = Auth::user()->id;
    if(empty($user_id)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    try{
        $query = Cart::query();
        $query = $query->where(['user_id'=>$user_id])->orderBy('id', 'DESC')->get();
        
        if($query){
          $details                = (object) array('items' => $query);
          $details->total_item    = Cart::where(['user_id'=>$user_id])->get()->sum("quantity");
          $details->tax           = '0.00';
          $details->total_amount  = Cart::where(['user_id'=>$user_id])->get()->sum("total");
          $details->address       = Address::where(['owner_id'=>$user_id, 'owner_type'=>'Customer'])->get();
          $details->payment_methods = [['id'=>"1", 'title'=>'Knet']];

          return $this->sendArrayResponse(new CheckoutResource($details), trans('customer_api.data_found_success'));
        }
        return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
    }catch (\Exception $e) { 
      DB::rollback();
      return $this->sendError('', $e->getMessage()); 
    }
  }

  /**
   * PLACE ORDER
   * @return \Illuminate\Http\Response
   */
  public function place_order(Request $request)
  {
    
    $validator  = Validator::make($request->all(), [
      'address_id'        => 'required|exists:addresses,id',
      'payment_method_id' => 'required',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());
    }

    $user_id = Auth::user()->id;
    if(empty($user_id)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    DB::beginTransaction();
    try{
        $query = Cart::where(['user_id'=>$user_id])->orderBy('id', 'DESC')->get();
        if($query->count() > 0){
          
          $item_count   = Cart::where(['user_id'=>$user_id])->get()->sum("quantity");
          $tax          = '0.00';
          $total        = Cart::where(['user_id'=>$user_id])->get()->sum("total");

          if($total == 0){
            return $this->sendError('', trans('customer_api.invalid_total_amount'));
          }

          $data = array(
            'user_id'           => $user_id,
            'owner_type'        => 'Pharmacy',
            'owner_id'          => $query['0']->owner_id,
            'item_count'        => $query->count(),
            'quantity'          => $item_count,
            'total'             => $total,
            'grand_total'       => $total + $tax,
            'address_id'        => $request->address_id,
            'payment_method_id' => $request->payment_method_id,
          );
          $return = Order::create($data);
          if($return){
            if($return->id){
              
              $postfields = array(
                'merchantCode'  => '842217',
                'amount'        => $return->total,
                'responseUrl'   => env('APP_URL').'/api/customer/payment-success?id='. $return->id .'&module=Pharmacy',
                'failureUrl'    => env('APP_URL').'/api/customer/payment-failed?id='. $return->id .'&module=Pharmacy',
                'description'   => 'Payment done',
                'variable1'     => 'variable2',
                'variable2'     => '',
                'variable3'     => '',
                'variable4'     => '',
                'variable5'     => '',
                'version'       => '2',
                'paymentType'   => '0',
              );
              $hesabe_data = $this->hesabe_create_order($postfields);
              if(empty($hesabe_data)){
                return $this->sendError('', trans('customer_api.order_place_error'));
              }

              foreach($query as $key=> $list){
                $orderItems = array(
                  'order_id'          => $return->id,
                  'product_id'        => $list->product_id,
                  'owner_id'          => $list->owner_id,
                  'quantity'          => $item_count,
                );
                OrderItem::create($orderItems);
              }
              
              DB::commit();
              $success['id']                =  (string) $return->id;
              $success['user_id']           =  (string) $return->user_id;
              $success['item_count']        =  $return->item_count ? (string) $return->item_count : '0';
              $success['quantity']          =  (string) $return->quantity;
              $success['total']             =  (string) $return->total;
              $success['address_id']        =  (string) $return->address_id;
              $success['payment_method_id'] =  (string) $return->payment_method_id;
              $success['payment_url']       =  'http://api.hesbstck.com/payment?data='.$hesabe_data;
              return $this->sendResponse($success, trans('customer_api.order_place_success'));
            }
          }
          DB::rollback();
          return $this->sendError('', trans('customer_api.order_place_error'));
        }
        return $this->sendError('', trans('customer_api.nothing_to_order'));
    }catch (\Exception $e) { 
      DB::rollback();
      return $this->sendError('', $e->getMessage()); 
    }
  }

  public function hesabe_encript_order($postfields)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('primary: PkW64zMe5NVdrlPVNnjo2Jy9nOb7v1Xg', 'secondary: 5NVdrlPVNnjo2Jy9'));
    curl_setopt($ch, CURLOPT_URL, 'http://api.hesbstck.com/api/encrypt');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
    $posts_result = json_decode(curl_exec($ch), TRUE);
    curl_close($ch);
    return $posts_result;
  }

  public function hesabe_decrypt_order($data)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('primary: PkW64zMe5NVdrlPVNnjo2Jy9nOb7v1Xg', 'secondary: 5NVdrlPVNnjo2Jy9', 'Accept: application/json'));
    curl_setopt($ch, CURLOPT_URL, 'http://api.hesbstck.com/api/decrypt');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['data'=>$data]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
    $posts_result = json_decode(curl_exec($ch), TRUE);
    curl_close($ch);
    return $posts_result;
  }

  public function hesabe_create_order($postfields)
  {
    $encript_result = $this->hesabe_encript_order($postfields);
    if(!empty($encript_result)){
      if($encript_result['status'] == 1){

        if($encript_result['response']){
          
          if($encript_result['response']['data']){
            
            $data = $encript_result['response']['data'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('accessCode: c333729b-d060-4b74-a49d-7686a8353481', 'Accept: application/json'));
            curl_setopt($ch, CURLOPT_URL, 'http://api.hesbstck.com/checkout');
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 80);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['data'=>$data]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
            $posts_result = curl_exec($ch);
            curl_close($ch);

            if($posts_result){
              
              $decrypt_result = $this->hesabe_decrypt_order($posts_result);
              if(!empty($decrypt_result) && $decrypt_result['status'] == 1){
                if(!empty($decrypt_result['response'])){
                  $response = json_decode($decrypt_result['response']['data'], TRUE);
                  if(!empty($response) && $response['status'] == true){
                    return $response['response']['data'];
                  }
                }
              }

            }

          }
        }
      }
    }
  }
}
