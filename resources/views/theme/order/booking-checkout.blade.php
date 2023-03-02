@extends('layouts.theme.master')

@section('content')
	<main id="checkoutPage" class="bg_gray" style="transform: none;">
		<div class="container margin_60_20" style="transform: none;">
		    <div class="row justify-content-center" style="transform: none;">
		        <div class="col-xl-6 col-lg-8">
		        	<div class="box_order_form">
					    <div class="head">
					        <div class="title"><h3>Personal Details</h3></div>
					    </div>
						<div class="main">
					        <div class="form-group">
					            <label>First and Last Name</label>
					            <input class="form-control" id="booking-name" placeholder="First and Last Name">
					        </div>
					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group">
					                    <label>Email Address</label>
					                    <input class="form-control" id="booking-email" placeholder="Email Address">
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group">
					                    <label>Phone</label>
					                    <input class="form-control" id="booking-phone_number" placeholder="Phone">
					                </div>
					            </div>
					        </div>
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
					        <!--<div class="payment_select">
					            <label class="container_radio">Pay with Razorpay
					                <input type="radio" value="2" name="payment_method"><span class="checkmark"></span>
					            </label>
					            <i class="icon_creditcard"></i>
					        </div>-->
					        <div class="payment_select">
					            <label class="container_radio">Cash On Booking
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
								<h3>Booking Summary</h3>
							</div>
							<div class="main">
								<ul>
									<li>Date<span id="booking-date">YYYY-MM-DD</span></li>
									<li>Time<span id="booking-time">00:00</span></li>
									<li>Guest<span id="booking-guest">0 Guest</span></li>
								</ul>
								<hr>
								<ul class="clearfix order-list-inner">
									
								</ul>
								<ul class="clearfix">
									<li>Subtotal<span id="sub_total">0.00</span></li>
									<li class="total">Total<span id="grand_total">0.00</span></li>
								</ul>
								
								<a href="javascript:void(0);" onclick="confirmBooking();" class="btn_1 gradient full-width mb_5">Confirm Booking</a>
								<div class="text-center"><small>Or Call Us at <strong>{{ Settings::get('contact_no') }}</strong></small></div>
							</div>
						</div>
					</div>
				</div>
		    </div>
		</div>
	</main>
    <!-- /main -->
@endsection

@section('js')
<script>
	$(document).ready(function(e) {
		bookingCheckoutList();
	});
</script>
@endsection