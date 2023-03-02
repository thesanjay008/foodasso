@extends('layouts.theme.master')

@section('content')
	<button id="rzp-button1">Pay</button>
@endsection

@section('js')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
	$(document).ready(function(e) {
		$( "#rzp-button1" ).trigger( "click" );
	});
	
	var options = {
		"key": "rzp_test_qSymNqLPLJ4wiK", // Enter the Key ID generated from the Dashboard
		"amount": "{{ $data->grand_total }}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
		"currency": "INR",
		"name": "{{ Settings::get('site_name') }}",
		"description": "Order Payment",
		"image": "{{ asset(Settings::get('logo')) }}",
		"order_id": "{{ $data->tracking_id }}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
		"handler": function (response){
			//alert(response.razorpay_payment_id);
			//alert(response.razorpay_order_id);
			//alert(response.razorpay_signature)
		},
		"prefill": {
			"email": "{{ $data->contact_email }}",
			"contact": "{{ $data->contact_phone_number }}"
		},
		"notes": {
			"custom_order_id": "{{ $data->custom_order_id }}",
			"address": "{{ $data->shipping_address }}"
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