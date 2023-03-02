$(document).ready(function(e) {
	//
});

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


// REGISTRATION
function registerUsers(){
	var data = new FormData();
	data.append('email', $('.ai-signup #email').val());
	data.append('name', $('.ai-signup #name').val());
	data.append('mobile_number', $('.ai-signup #mobile_number').val());
	data.append('password', $('.ai-signup #password').val());
	data.append('password_confirmation', $('.ai-signup #password_confirmation').val());
	data.append('is_restaurant', $('.ai-signup #is_restaurant').prop("checked") ? 1 : 0 );
	data.append('restaurent_english', $('.ai-signup #restaurent_english').val());
	var response = runAjax(regurl, data);
	if(response.status == '200'){
		swal.fire({ 
			type: 'success',
			title: response.message,
			html: response.data.html,
			onClose: function () {
				window.location.href = hmrl;
			}
		});
		
	}else if(response.status == '422'){
		$('.validation-div').text('');
		$.each(response.error, function( index, value ) {
			$('.val-'+index).text(value);
		});
		
	} else if(response.status == '201'){
		$('.validation-div').text('');
		swal.fire({title: response.message,type: 'error'});
	}
}

// LOGIN
function loginUser(){
	var data = new FormData();
	data.append('username', $('.ai-signin #username').val());
	data.append('password', $('.ai-signin #examplePassword').val());
	var response = runAjax(lgnurl, data);
	if(response.status == '200'){
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