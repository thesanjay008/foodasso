$(document).ready(function(e) {
	$(".input-file").change(function () {
		var preview = $(this).attr("data-att-preview");
		readURL(this, preview);
	});
	
	// Add To Cart Box
	$('.modal_dialog').click(function(){
		$('#item_id').val($(this).attr('data-att-id'));
		$('#modal_dialog-title').text($(this).attr('data-att-title'));
	});
	
	// SELECT LANGUAGE
	$(".close-box").click(function () {
		$('.mfp-close').trigger('click');
	});
});

// CHECK NEW ORDER STATUS
(function() {
  //$(document).ready(function() {update();});
  //function update() { checkNewOrder(); setTimeout(update, 3000); }
  }
)();

// AJAX RUN
var runAjax = (function (i = null, ii = null, type = 'POST'){
	if(i == ''){ return; }
	ii.append('visit_from', 'web');
	ii.append('_token', token);
	var ob = jQuery.ajax({
		url: i,
		type: type,
		enctype: 'multipart/form-data',
		contentType: 'application/json; charset=UTF-8',
		processData: false,
		contentType: false,
		data: ii,
		cache: false,
		async: false,
		success: function (response) {
		},
	}).responseText;
	return jQuery.parseJSON(ob);
});

// IMAGE PREVIEW
function readURL(input, preview) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			jQuery('#'+preview).attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

//
function checkNewOrder(){
	var data = new FormData();
	var response = runAjax(checkNewOrderURL, data);
	if(response.status == '200'){
		$('#audioHtml').html(response.html);
	}
}


// Login
function loginProtal(){
	var data = new FormData();
	data.append('username', $('#loginForm #username').val());
	data.append('password', $('#loginForm #password').val());
	var response = runAjax(SITE_URL +'/loginPortal', data);
	if(response.status == '200' && response.success == '1'){
		swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
		setTimeout(function(){ location.reload(); }, 2000);
	}else if(response.status == '422'){
		$('.validation-div').text('');
		$.each(response.error, function( index, value ) {
			$('.val-'+index).text(value);
		});
	} else if(response.status == '201'){
		$('.validation-div').text('');
		swal.fire({title: response.message,type: 'error'});
	}else{
		setTimeout(function(){ location.reload(); }, 2000);
	}
}

// LISTING
function getRestroList(url = '', page = 1, count = 10){
	var data = new FormData();
	data.append('search', $('#restro-search').val());
	var response = runAjax(restrolistURL, data);
	if(response.status == '200'){
		if(response.data.length > 0){
			var htmlData = '';
			$.each(response.data, function( index, value ) {
				htmlData+= '<div class="col-lg-6 col-md-6 col-sm-12">'+
								'<div class="popular-dish-box wow fadeIn" data-wow-delay="0.2s">'+
									'<div class="popular-dish-thumb">'+
										'<a href="'+ value.url +'" title="" itemprop="url"><img src="'+ value.image +'" alt="'+ value.title +'" itemprop="image"></a>'+
										'<span class="post-rate yellow-bg brd-rd2"><i class="fa fa-star-o"></i> 4.25</span>'+
										'<span class="post-likes brd-rd4"><i class="fa fa-heart-o"></i> 12</span>'+
									'</div>'+
									'<div class="popular-dish-info">'+
										'<h4 itemprop="headline"><a href="'+ value.url +'" title="" itemprop="url">'+ value.title +'</a></h4>'+
										'<p itemprop="description">'+ value.description +'</p>'+
										'<span class="price">$85.00</span>'+
										'<a class="brd-rd2" href="'+ value.url +'" title="Order Now" itemprop="url">Order Now</a>'+
									'</div>'+
								'</div>'+
							'</div>';
			});
			$('#restroList').html(htmlData);
		}
		
	}
}

// CART LIST
function cartList(){
	var data = new FormData();
	var response = runAjax(cartListURL, data);
	if(response.status == '200'){
		$('#sub_total').text('');
		$('#total').text('');
		$('.order-list-inner').html('');
		if(response.data.list.length > 0){
			var htmlData = '';
			$.each(response.data.list, function( index, value ) {
				htmlData+= '<li>'+
							'<div class="dish-name">'+
								'<i>'+ (index + 1) +'.</i> <h6 itemprop="headline">'+ value.product.title +'</h6> <span class="">'+ value.price + 'x' + value.quantity + '</span> <span class="price">'+ value.total +'</span>'+
							'</div>'+
						'</li>';
			});
			$('#sub_total').text(response.data.sub_total);
			$('#total').text(response.data.total);
			$('.order-list-inner').html(htmlData);
		}
		
	}
}

// CHECKOUT LIST
function checkoutList(){
	var data = new FormData();
	var response = runAjax(cartListURL, data);
	if(response.status == '200'){
		if(response.data.list.length > 0){
			var htmlData = '';
			$.each(response.data.list, function( index, value ) {
				htmlData+= '<li>'+
							'<div class="dish-name">'+
								'<li><a href="javascript:void(0);">' + value.quantity + ' x '+ value.product.title +'</a><span>'+ value.total +'</span></li>'+
							'</div>'+
						'</li>';
			});
			$('#sub_total').text(response.data.sub_total);
			$('#total').text(response.data.total);
			$('.order-list-inner').html(htmlData);
		}
		
	}
}


/*
	*
	* BOOKING APIs
	*
*/

// Create Booking
function createBooking(address_id){
	var booking_day  = $('input[name="booking_day"]:checked').is(':checked') ? $('input[name="booking_day"]:checked').val() : '';
	var booking_time = $('input[name="booking_time"]:checked').is(':checked') ? $('input[name="booking_time"]:checked').val() : '';	
	
	var data = new FormData();
	data.append('booking_day', booking_day);
	data.append('booking_time', booking_time);
	data.append('guest', $('#guest').val());
	var response = runAjax(SITE_URL +'/create-booking', data);
	if(response.success == '1'){
		window.location.assign(SITE_URL + '/booking/checkout');
		if(payment_method == '1'){
			//swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			//setTimeout(function(){ window.location.assign(SITE_URL + '/order-success'); }, 2000);
		}else{
			//setTimeout(function(){ window.location.assign(response.data.payment_url); }, 2000);
		}
	}else if(response.status == '201'){
		swal.fire({ type: 'error', title: response.message});
	}
}

// BOOKING LIST
function bookingCheckoutList(){
	var data = new FormData();
	var response = runAjax(SITE_URL +'/booking-checkout-list', data);
	if(response.status == '200'){
		if(response.data){
			$('#booking-date').text(response.data.booking_date);
			$('#booking-time').text(response.data.booking_time);
			$('#booking-guest').text(response.data.booking_guest);
			$('#sub_total').text(response.data.sub_total);
			$('#grand_total').text(response.data.grand_total);
		}
	}
}

// Confirm Booking
function confirmBooking(){
	var payment_method 	 = $('input[name="payment_method"]:checked').is(':checked') ? $('input[name="payment_method"]:checked').val() : '';
	
	var data = new FormData();
	data.append('name', $('#booking-name').val());
	data.append('email', $('#booking-email').val());
	data.append('phone_number', $('#booking-phone_number').val());
	data.append('payment_method', payment_method);
	var response = runAjax(SITE_URL +'/confirm-booking', data);
	if(response.success == '1'){
		if(payment_method == '1'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.assign(SITE_URL + '/booking-success'); }, 2000);
		}else{
			//setTimeout(function(){ window.location.assign(response.data.payment_url); }, 2000);
		}
	}else if(response.status == '201'){
		swal.fire({ type: 'error', title: response.message});
	}
}



/*
	*
	* TABLE APIs
	*
*/

// ADD TO CART
function TableaddToCart(table_id = ''){
	var data = new FormData();
	data.append('item_id', $('#modal-dialog #item_id').val());
	data.append('table_id', table_id);
	data.append('order_type', 'Dine-In');
	data.append('quantity', $('#modal-dialog #qty').val());
	var response = runAjax(SITE_URL +'/addtoCart', data);
	if(response.status == '200' && response.success == '1'){
		swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
		$('.mfp-close').trigger('click');
		cartList();
	}else{
		swal.fire({ type: 'error', title: response.message});
	}
}

// Create Table Order
function createTableOrder(){
	var payment_method 	 = $('input[name="payment_method"]:checked').is(':checked') ? $('input[name="payment_method"]:checked').val() : '1';
	
	var data = new FormData();
	data.append('otp_code', $('#modal-dialog #otp_code').val());
	data.append('order_type', 'Dine-In');
	data.append('table_number', $('.box_order_form #checkout-table_number').val());
	data.append('name', $('.box_order_form #checkout-name').val());
	data.append('phone_number', $('.box_order_form #checkout-phone_number').val());
	data.append('payment_method', payment_method);
	var response = runAjax(SITE_URL +'/create-table-order', data);
	if(response.success == '1'){
		if(payment_method == 1){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.assign(SITE_URL + '/table/order-success/'+response.success); }, 2000);
		}else{
			setTimeout(function(){ window.location.assign(response.data.payment_url); }, 2000);
		}
	}else if(response.status == '201'){
		swal.fire({ type: 'error', title: response.message});
	}
}






// ADD TO CART
function addToCart(){
	var data = new FormData();
	data.append('item_id', $('#modal-dialog #item_id').val());
	data.append('quantity', $('#modal-dialog #qty').val());
	data.append('order_type', 'Delivery');
	var response = runAjax(SITE_URL +'/addtoCart', data);
	if(response.status == '200' && response.success == '1'){
		swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
		$('.mfp-close').trigger('click');
		cartList();
	}else{
		swal.fire({ type: 'error', title: response.message});
	}
}

// Delete Cart
function delete_cart(item_id = null){
	var data = new FormData();
	data.append('item_id', item_id);
	var response = runAjax(SITE_URL +'/delete_cart', data);
	if(response.status == '200' && response.success == '1'){
		swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
		setTimeout(function(){ location.reload(); }, 2000);
		//cartList();
	}else{
		swal.fire({ type: 'error', title: response.message});
	}
}

// CART LIST
function cartList(){
	var data = new FormData();
	var response = runAjax(SITE_URL+'/cartList', data);
	if(response.status == '200'){
		$('#sub_total').text('0.00');
		$('#total').text('0.00');
		$('.order-list-inner').html('');
		if(response.data.list.length > 0){
			var htmlData = '';
			$.each(response.data.list, function( index, value ) {
				htmlData+= '<li><a href="javascript:void(0);" onclick="delete_cart('+ value.id +');">'+ value.quantity +'x '+ value.title +'</a><span>'+ value.price +'</span></li>';
			});
			$('#sub_total').text(response.data.sub_total);
			$('#delivery_fee').text(response.data.delivery_fee);
			$('#total').text(response.data.total);
			$('#order-list-inner').html(htmlData);
		}
		
	}
}

// Create Order
function createOrder(address_id){
	var delivery_address = $('input[name="delivery_address"]:checked').is(':checked') ? $('input[name="delivery_address"]:checked').val() : '';
	var payment_method 	 = $('input[name="payment_method"]:checked').is(':checked') ? $('input[name="payment_method"]:checked').val() : '';
	
	var data = new FormData();
	data.append('delivery_address', delivery_address);
	data.append('payment_method', payment_method);
	var response = runAjax(SITE_URL +'/create-order', data);
	if(response.success == '1'){
		if(payment_method == '1'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.assign(SITE_URL + '/order-success'); }, 2000);
		}else{
			//rzp1.open();
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500});
			setTimeout(function(){ window.location.assign(response.data.payment_url); }, 2000);
		}
	}else if(response.status == '201'){
		swal.fire({ type: 'error', title: response.message});
	}
}


/*
	*
	* Profile APIs
	*
*/

// Order List
function myOrderList(type = 'all'){
	var data = new FormData();
	data.append('type', type);
	var response = runAjax(SITE_URL +'/ajax_myOrders', data);
	if(response.status == '200'){
		if(response.data.length > 0){
			var htmlData = '';
			$.each(response.data, function( index, value ) {
				htmlData+= '<div class="col-md-12">'+
								'<a class="order_item menu_item" href="javascript:void(0);">'+
									'<h3>'+ value.title +'</h3>'+
									'<p>'+ value.address +'</p>'+
									'<ul>'+ value.order_items +'</ul>'+
									'<strong>'+ value.grand_total +'</strong>'+
									'<div class="order-status-btn"><button class="btn">'+ value.status +'</button></div>'+
									'<!--<figure><img src="" data-src="" alt="thumb" class="lazy loaded" data-was-processed="true"></figure>-->'+
								'</a>'+
							'</div>';
			});
			$('#myOrderList').html(htmlData);
		}
	}
}

// Update Profile
function updateProfile(){
	var data = new FormData();
	data.append('name', $('#updateProfile #name').val());
	data.append('email', $('#updateProfile #email').val());
	data.append('phone_number', $('#updateProfile #phone_number').val());
	var response = runAjax(SITE_URL +'/updateProfile', data);
	if(response.status == '200'){
		swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
	}else{
		swal.fire({ type: 'error', title: response.message});
	}
}

// Save Address
function saveAddress(){
	var data = new FormData();
	var address_type = $('input[name="address_type"]:checked').is(':checked') ? $('input[name="address_type"]:checked').val() : '';
	data.append('address_type', address_type);
	data.append('address', $('.saveAddress #address').val());
	data.append('postal_code', $('.saveAddress #postal_code').val());
	var response = runAjax(SITE_URL +'/saveAddress', data);
	if(response.status == '200'){
		swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
		setTimeout(function(){ location.reload(); }, 2000);
	}else{
		swal.fire({ type: 'error', title: response.message});
	}
}