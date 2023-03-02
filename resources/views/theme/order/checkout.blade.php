@extends('layouts.theme.master')

@section('content')
	<main id="checkoutPage" class="bg_gray" style="transform: none;">
		<div class="container margin_60_20" style="transform: none;">
		    <div class="row justify-content-center" style="transform: none;">
		        <div class="col-xl-6 col-lg-8">
		        	<div class="box_order_form">
					    <div class="head">
					        <div class="title"><h3>Select Order Addrss <a class="modal_dialog" href="#modal-dialog" data-att-id="" data-att-title="Add Address"><i class="icon_plus_alt2"></i></a></h3></div>
					    </div>
						<div class="row address-list">
							@if($addresses)
							@foreach($addresses as $list)
							<div class="col-xl-12">
								<label class="container_radio">
									@if($list->address_type == 'Home') <i class="icon_house_alt"></i> Home
									@else($list->address_type == 'Home') <i class="icon_mobile"></i> Work @endif
					                <input type="radio" value="1" name="delivery_address"><span class="checkmark"></span>
					            </label>
								<p>{{ $list->address .' '. $list->city->name .' '. $list->address }}</p>
							</div>
							@endforeach
							@endif
						</div>
					</div>
					<!-- /box_order_form -->
		            <div class="box_order_form">
					    <div class="head">
					        <div class="title">
					            <h3>Payment Method</h3>
					        </div>
					    </div>
					    <div class="main">
					        <div class="payment_select">
					            <label class="container_radio">Pay with Razorpay
					                <input type="radio" value="2" name="payment_method"><span class="checkmark"></span>
					            </label>
					            <i class="icon_creditcard"></i>
					        </div>
					        <div class="payment_select">
					            <label class="container_radio">Cash On Delivery
					                <input type="radio" value="1" checked="checked" name="payment_method"><span class="checkmark"></span>
					            </label>
					            <i class="icon_wallet"></i>
					        </div>
					    </div>
					</div>
					<!-- /box_order_form -->
		        </div>
		        <!-- /col -->
		        <div class="col-xl-4 col-lg-4" id="sidebar_fixed" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
					<div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px;">
						<div class="box_order">
							<div class="head">
								<h3>Order Summary</h3>
							</div>
							<div class="main">
								<ul>
									<li>Date<span><?php echo date('d/m/Y'); ?></span></li>
								</ul>
								<hr>
								<ul class="clearfix order-list-inner">
									
								</ul>
								<ul class="clearfix">
									<li>Subtotal<span id="sub_total">0.00</span></li>
									<li>Delivery fee<span>0.00</span></li>
									<li class="total">Total<span id="total">0.00</span></li>
								</ul>
								<div class="row opt_order">
		                            <div class="col-6">
		                                <label class="container_radio">Delivery
		                                    <input type="radio" value="option1" name="opt_order" checked>
		                                    <span class="checkmark"></span>
		                                </label>
		                            </div>
		                            <div class="col-6">
		                                <label class="container_radio">Take away
		                                    <input type="radio" value="option1" name="opt_order">
		                                    <span class="checkmark"></span>
		                                </label>
		                            </div>
		                        </div>
								<a href="javascript:void(0);" onclick="createOrder();" class="btn_1 gradient full-width mb_5">Confirm Order</a>
								<div class="text-center"><small>Or Call Us at <strong>{{ Settings::get('contact_no') }}</strong></small></div>
							</div>
						</div>
					</div>
				</div>
		    </div>
		</div>
	</main>
    <!-- /main -->
	
	<!-- Modal Adderss -->
	<div id="modal-dialog" class="saveAddress zoom-anim-dialog mfp-hide">
		<div class="small-dialog-header">
			<h3 id="modal_dialog-title"></h3>
		</div>
		<div class="content">
			<h5>Address Type</h5>
			<ul id="variation-list" class="clearfix">
				<li>
					<label class="container_radio"><i class="icon_house_alt"></i> Home</span>
						<input type="radio" value="Home" name="address_type">
						<span class="checkmark"></span>
					</label>
				</li>
				<li>
					<label class="container_radio"><i class="icon_mobile"></i> Work</span>
						<input type="radio" value="Work" name="address_type">
						<span class="checkmark"></span>
					</label>
				</li>
			</ul>
			<div class="row">
				<div class="col-md-6">
					<h5>City</h5>
					<div class="styled-select currency-selector">
						<select>
							<option value="botad" selected="">Botad</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<h5>Postal Code</h5>
					<input type="number" id="postal_code" class="form-control" name="postal_code">
				</div>
			</div>
			
			<br>
			<h5>Address</h5>
			<textarea name="address" id="address" rows="2" class="form-control" spellcheck="false"></textarea>
			<br>

		</div>
		<div class="footer">
			<div class="row small-gutters">
				<div class="col-md-4">
					<button type="reset" class="close-box btn_1 outline full-width mb-mobile">Cancel</button>
				</div>
				<div class="col-md-8">
				<input id="item_id" type="hidden" value="" />
					<button type="reset" class="btn_1 full-width" onclick="saveAddress()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /Modal item order -->
@endsection

@section('js')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
	$(document).ready(function(e) {
		checkoutList();
	});
	
	var options = {
		"key": "rzp_test_qSymNqLPLJ4wiK",
		"amount": "5000",
		"currency": "INR",
		"name": "Foodasso",
		"description": "Order Payment",
		"image": "http://127.0.0.1:8000/uploads/2021/12/b6a47f898d49ef3e46a1b75506197f50.png",
		"order_id": "wqeqw",
		"handler": function (response){
			//alert(response.razorpay_payment_id);
			//alert(response.razorpay_order_id);
			//alert(response.razorpay_signature)
		},
		"prefill": {
			"email": "",
			"contact": ""
		},
		"notes": {
			"custom_order_id": "",
			"address": ""
		},
		"theme": {
			"color": "#3399cc"
		}
	};
	var rzp1 = new Razorpay(options);
	rzp1.on('payment.failed', function (response){
			alert(response.error.code);
			alert(response.error.description);
			alert(response.error.source);
			alert(response.error.step);
			alert(response.error.reason);
			alert(response.error.metadata.order_id);
			alert(response.error.metadata.payment_id);
	});
	document.getElementById('rzp-button1').onclick = function(e){
		rzp1.open();
		e.preventDefault();
	}
</script>
@endsection