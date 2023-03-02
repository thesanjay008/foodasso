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
					            <label>Name</label>
					            <input class="form-control" id="checkout-name" placeholder="Enter Name">
								<div class="validation-div" id="val-name"></div>
					        </div>
					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group">
					                    <label>Phone</label>
					                    <input class="form-control" id="checkout-phone_number" placeholder="Enter Phone">
										<input type="hidden" class="form-control" id="checkout-table_number" value="{{ $table_id }}">
										<div class="validation-div" id="val-phone_number"></div>
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
					        <div class="payment_select">
					            <label class="container_radio">Pay with Razorpay
					                <input type="radio" value="2" checked="checked" name="payment_method"><span class="checkmark"></span>
					            </label>
					            <i class="icon_creditcard"></i>
					        </div>
					        <div class="payment_select">
					            <label class="container_radio">Cash On Delivery
					                <input type="radio" value="1" name="payment_method"><span class="checkmark"></span>
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
								<hr>
								<ul class="clearfix">
									<li>Subtotal<span id="sub_total">0.00</span></li>
									<li class="total">Total<span id="total">0.00</span></li>
								</ul>
								<a href="#modal-dialog" class="modal_dialog otp-box"></a>
								<a href="javascript:void(0);" class="btn_1 gradient full-width mb_5" onclick="sendTableOrderOTP()">Confirm Order</a>
							</div>
						</div>
					</div>
				</div>
		    </div>
		</div>
	</main>
    <!-- /main -->
	
	<!-- Modal Adderss -->
	<div id="modal-dialog" class="zoom-anim-dialog mfp-hide">
		<div class="small-dialog-header">
			<h3 id="modal_dialog-title"></h3>
		</div>
		<div class="content">
			<div class="row">
				<div class="col-md-8">
					<h5>Enter OTP</h5>
					<input type="number" id="otp_code" class="form-control">
				</div>
				<div class="col-md-2">
					<h5>Resend</h5>
					<button class="btn_1 outline icon_mobile" onclick="sendTableOrderOTP();"><i class="fa fa-facebook"></i></button>
				</div>
			</div>
		</div>
		<div class="footer">
			<div class="row small-gutters">
				<div class="col-md-4">
					<button type="reset" class="close-box btn_1 outline full-width mb-mobile">Cancel</button>
				</div>
				<div class="col-md-8">
					<button type="reset" class="btn_1 full-width" onclick="createTableOrder()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /Modal item order -->
@endsection

@section('js')
<script>
	$(document).ready(function(e) {
		checkoutList();
	});
	
	// SEND OTP
	function sendTableOrderOTP(){
		var data = new FormData();
		data.append('name', $('#checkout-name').val());
		data.append('phone_number', $('#checkout-phone_number').val());
		var response = runAjax('{{route("send.order.otp")}}', data);
		if(response.status == '200' && response.success == 1){
			$('.otp-box').trigger('click');
		}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
			
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection