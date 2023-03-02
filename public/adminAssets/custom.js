$(document).ready(function(e) {
	$(".form-control-file").change(function () {
		var preview = $(this).attr("data-att-preview");
		readURL(this, preview);
	});
	
	// Active parent div for navigation
	$( "a.mm-active" ).parent().addClass('mm-active');
});

function readURL(input, preview) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			jQuery('#'+preview).attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

var adminAjax = (function (i = null, ii = null, type = 'POST'){
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


// RUN CRONE
(function() {
  $(document).ready(function() {runFunction();});
	function runFunction() {
	  orderCount();
	  setTimeout(runFunction, 5000);
	}
  }
)();


// ORDER COUNT
function orderCount(){
	var data = new FormData();
	var response = adminAjax(SITE_URL +'/backend/checkNewOrders', data);
	if(response.status == '200'){
		if(response.data.count > 0){
			$('.nav-order-count').text(response.data.count);
			$('.nav-order-count').show();
			$('.count-open-orders').text(response.data.count);
			$('#audioHtml').html(response.data.html);
		}else{
			$('.nav-order-count').text('');
			$('.nav-order-count').hide();
		}
		
	}
}