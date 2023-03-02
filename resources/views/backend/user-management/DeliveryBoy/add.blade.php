@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('delivery.create') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a href="{{ route('user.management',['DeliveryBoy']) }}">{{ trans('delivery.plural') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('delivery.add') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<!-- <h5 class="card-title">Grid Rows</h5> -->
			<form class="" action="javascript:void(0);" onsubmit="saveData();">
				<div class="form-row">
					<div class="row">
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="name">{{ trans('common.name') }}</label>
								<input id="name" type="text" class="form-control" placeholder="{{ trans('common.placeholder.name') }}">
								<div class="validation-div" id="val-name"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="email">{{ trans('common.email') }}</label>
								<input id="email" type="text" class="form-control" placeholder="{{ trans('common.placeholder.email') }}">
								<div class="validation-div" id="val-email"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="password">{{ trans('common.password') }}</label>
								<input id="password" type="text" class="form-control" placeholder="{{ trans('common.placeholder.password') }}">
								<div class="validation-div" id="val-password"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="phone_number">{{ trans('common.phone_number') }}</label>
								<input id="phone_number" type="text" class="form-control" placeholder="{{ trans('common.placeholder.phone_number') }}">
								<div class="validation-div" id="val-phone_number"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="phone_number">{{ trans('delivery.gender') }}</label>
								<br>
								<input type="radio" id="gender" name="gender" value="Male">
								<label for="Male">Male</label>
								<input type="radio" id="gender" name="gender" value="Female">
								<label for="Female">Female</label>
								<div class="validation-div" id="val-gender"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="dob">{{ trans('common.dob') }}</label>
								<input id="dob" type="date"class="form-control" placeholder="{{ trans('common.placeholder.dob') }}">
								<div class="validation-div" id="val-dob"></div>
							</div>
						</div>
						<!-- <div class="col-md-6">
							<div class="position-relative form-group">
								<label for="pancard">{{ trans('common.pancard') }}</label>
								<select name="documnets" id="documnets" class="form-control documentsreq">
										<option value="">Select Document</option>
										<option value="adhar">Adhar Card</option>
										<option value="pan">Pan Card</option>
								</select>
							</div>
						</div> -->
						<!-- <div class="col-md-6">
							<div class="position-relative form-group documentssubmit" id="pancards">
								<label for="pancard">{{ trans('common.pancard') }}</label>
								<input id="pancard" type="text" class="form-control" placeholder="{{ trans('common.placeholder.pancard') }}">
								<span id="lblPANCard" style="color:red" class="error">Invalid PAN Number</span>
								<div class="validation-div" id="val-pancard"></div>
							</div>
							<div class="position-relative form-group documentssubmit" id="adharcard">
								<label for="adhar">{{ trans('common.adhar') }}</label>
								<input  id="adhar" class="form-control pan" placeholder="{{ trans('common.placeholder.adhar') }}" type="text" data-type="adhaar-number" maxLength="19">
								<p style="color:red;" class="error"></p> -->
								<!-- <input  type="text" class="form-control" > -->
								<!-- <div class="validation-div" id="val-adhar"></div>
							</div>
						</div> -->
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="starttime">{{ trans('common.starttime') }}</label>
								<input id="starttime" type="time"  class="form-control" placeholder="{{ trans('common.placeholder.starttime') }}">
								<div class="validation-div" id="val-starttime"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="endtime">{{ trans('common.endtime') }}</label>
								<input id="endtime" type="time"class="form-control" placeholder="{{ trans('common.placeholder.endtime') }}">
								<div class="validation-div" id="val-endtime"></div>
							</div>
						</div>
						
						<!-- <div class="col-md-6">
							<div class="position-relative form-group">
								<label for="license">{{ trans('common.license') }}</label> -->
								<!-- <input id="license" type="text" class="form-control" placeholder="{{ trans('common.placeholder.license') }}">
								<div class="validation-div" id="val-license"></div> -->
							<!-- </div>
						</div> -->
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="licensedoc">{{ trans('common.license') }}</label>
								<input name="file" id="licensedoc" type="file" class="form-control-file item-img" accept="image/*">
								<div class="validation-div" id="val-licensedoc"></div>
								<div class="image-preview"><img id="image-src-lincense" src="" width="100%"/></div>
							</div>
						</div>
					<!-- <div class="row">
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="idproof">{{ trans('delivery.idproof') }}</label>
								<input id="license" type="text" class="form-control" placeholder="{{ trans('common.placeholder.license') }}">
								<div class="validation-div" id="val-license"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="idproof">{{ trans('delivery.document') }}(Aadhar Card / Pan Card / Voter ID)</label>
								<input name="file" id="idproof" type="file" class="form-control-file item-img" accept="image/*">
								<div class="validation-div" id="val-idproof"></div>
								<div class="image-preview"><img id="image-src-idproof" src="" width="100%"/></div>
							</div>
						</div>
					</div> -->
					
						<div class="position-relative form-group">
							<label for="exampleFile">{{ trans('brand.image') }}</label>
							<input name="file" id="imageprofile" type="file" class="form-control-file item-img" accept="image/*">
							<div class="validation-div" id="val-image"></div>
							<div class="image-preview"><img id="image-src-profile" src="" width="100%"/></div>
						</div>
						<!-- <div class="position-relative form-group">
							<label for="documentupload">{{ trans('common.documentupload') }}</label>
							<input name="file" id="documentupload" type="file" class="form-control-file item-img" >
							<div class="validation-div" id="val-documentupload"></div> -->
							<!-- <div class="image-preview"><img id="image-src" src="" width="100%"/></div> -->
						<!-- </div> -->
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="idproof">{{ trans('delivery.idproof') }}</label>
									<select class="form-control" id="idproof_upload">
										<option value="">Select Id Proof</option>
										<option value="aadharcard">Aadhar Card</option>
										<option value="pancard">Pan Card</option>
									</select>
									<div class="validation-div" id="val-documnets"></div>
								</div>
							</div>
							<div class="col-md-6" id="pancardidproof">
								<div class="position-relative form-group">
									<label for="pancard_idproof">{{ trans('delivery.document') }}(Pan Card)</label>
									<input name="file" id="pancard_idproof" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-pancardidproof"></div>
									<div class="image-preview"><img id="image-src-pancard_idproof" src="" width="100%"/></div>
								</div>
							</div>
							<div class="col-md-6" id="adharcardidproof">
								<div class="position-relative form-group">
									<label for="adharfront">{{ trans('delivery.document') }}(Aadhar Card Front)</label>
									<input name="file" id="adharfront" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-adharfront"></div>
									<div class="image-preview"><img id="image-src-adharfront" src="" width="100%"/></div>
								</div>
								<div class="position-relative form-group">
									<label for="adharback">{{ trans('delivery.document') }}(Aadhar Card Back)</label>
									<input name="file" id="adharback" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-adharback"></div>
									<div class="image-preview"><img id="image-src-adharback" src="" width="100%"/></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6" >
								<div class="position-relative form-group">
									<label for="idproof">{{ trans('delivery.type') }}</label>
									<select class="form-control" id="deliveryboy_assign">
										<option value="">Select Deliveryboy Type</option>
										<option value="selfdel">Self</option>
										<option value="vendordel">Organization</option>
									</select>
									<div class="validation-div" id="val-assigndeliveryboy"></div>
								</div>
							</div>
							<div class="col-md-6" id="selectvendor_type" style="margin-top: 30px;">
								<div class="position-relative form-group">
									<select class="form-control" id="assign_vendor">
										<option value="">Select Vedor Type</option>
										<option value="Store">Store</option>
										<option value="Restaurant">Restaurant</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
							</div>
							<div class="col-md-6" id="vendorlist_all">
								<div class="position-relative form-group">
									<select class="form-control" id="vendorlist">
										
									</select>
									<div class="validation-div" id="val-assignvendor"></div>
								</div>
							</div>
						</div>
					</div>
					
					<div class ="col-md-6">
						<div class="row">
							<div class="col-md-6">
									<div class="position-relative form-group">
										<label for="state_id">{{ trans('common.State') }}</label>
										<select name="state_id" id="state_id" class="form-control">
											<option value="">--select--</option>
											@if($state->count())
												@foreach($state as $state_list)
													<option value="{{$state_list->id}}">{{$state_list->title}}</option>
												@endforeach
											@endif
										</select>
										<div class="validation-div" id="val-state_id"></div>
									</div>
							</div>
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="city_id">{{ trans('common.city') }}</label>
									<select name="city_id" id="city_id" class="form-control"></select>
									<div class="validation-div" id="val-city_id"></div>
								</div>
							</div>
							<div class="col-md-12">
								Address:
								<input id="searchTextField"  class="form-control"  type="text" size="50" style="text-align: left;direction: ltr;">
								<div class="validation-div" id="val-address"></div>
								<br>
								<div class="row">
									<div class="col-md-6">
										Latitude:<input name="latitude" id="latitude" class="MapLat form-control" value="" type="text" placeholder="Latitude"  disabled>
										<div class="validation-div" id="val-latitude"></div>
									</div>
									<div class="col-md-6">
										Longitude:<input name="longitude" id="longitude" class="MapLon form-control" value="" type="text" placeholder="Longitude"  disabled>
										<div class="validation-div" id="val-longitude"></div>
									</div>
									</div>
								<div id="map_canvas" style="height: 350px;width: 450px;margin: 0.6em;"></div>
							</div>
						</div>
					</div>
					
				</div>
				</div>

				
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="status" class="">{{ trans('common.status') }}</label>
							<select  class="form-control" id="status">
							  <option value="active">{{trans('common.active')}}</option>
							  <option value="inactive">{{trans('common.inactive')}}</option>
							</select>
							<div class="validation-div" id="val-status"></div>
						</div>
					</div>
				</div>
				<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key={{Settings::get('google_map_api_key')}}&libraries=places"></script>
<script>
	var lat = 23.076099;
	var lng = 72.508408;
</script>
<script src="{{asset('themeAssets/js/google-map.js')}}"></script>

<script>
	$('#state_id').change( function () {
		let $state_id = $('#state_id').val();
		var data = new FormData();
		data.append('state_id', $state_id);
		var response = adminAjax('{{route("ajax.city.list")}}', data);
		if(response.status == '200'){
			if(response.data.length !== 0){
				$('#city_id').empty();
				$.each(response.data, function(key, value){
					console.log(value.title);
					$('#city_id').append('<option value="'+value.id+'">'+value.title+'</option>');
				});
			}else{
				$('#city_id').html('<option value="">'+response.message+'</option>');
			}
		}
	});
	$('#selectvendor_type').hide();
	$('#deliveryboy_assign').change(function(){
		var value = $(this).val();
		if(value === "vendordel")
		{
			$('#selectvendor_type').show();

		}
		else if(value === "selfdel")
		{
			$('#vendorlist_all').hide();
			$('#selectvendor_type').hide();
		}
		else
		{
			$('#vendorlist_all').hide();
			$('#selectvendor_type').hide();
		}
	});
	$('#vendorlist_all').hide();
	$('#assign_vendor').change(function(){
		var data = new FormData();
		data.append('assignvendor', $(this).val());
		var response = adminAjax('{{route("deliveryboy.vendortype.assignlist")}}', data);
		if(response.status == '200'){
			if(response.data.length !== 0){
				$('#vendorlist_all').show();
				$('#vendorlist').empty();
				$.each(response.data, function(key, value){
					console.log(value.title);
					$('#vendorlist').append('<option value="'+value.id+'">'+value.name+'</option>');
				});
			}else{
				$('#vendorlist').html('<option value="">'+response.message+'</option>');
			}
		}
	})
	$('#pancardidproof').hide();
	$('#adharcardidproof').hide();
	$('#idproof_upload').change(function(){
		var value = $(this).val();
		if(value === "pancard")
		{
			$('#pancardidproof').show();
			$('#adharcardidproof').hide();
		}
		else if(value === "aadharcard")
		{
			$('#adharcardidproof').show();
			$('#pancardidproof').hide();
		}
		else
		{
			$('#adharcardidproof').hide();
			$('#pancardidproof').hide();
		}
	})
$(function () {
	$("#lblPANCard").hide();
        $("#pancard").keyup(function(){ 
			 
            var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
            if (regex.test($("#pancard").val().toUpperCase())) {
                $("#lblPANCard").css("visibility", "hidden");
				$("#lblPANCard").hide();
                return true;
            } else {
                $("#lblPANCard").show();
                return false;
            }
        });
});
	$('[data-type="adhaar-number"]').keyup(function() {
		var value = $(this).val();
		value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
		$(this).val(value);
	});
	$('[data-type="adhaar-number"]').on("change, blur", function() {
	var value = $(this).val();
	var maxLength = $(this).attr("maxLength");
	if (value.length != maxLength) {
		$(this).addClass("highlight-error");
	} else {
		$(this).removeClass("highlight-error");
	}
	});
	$(document).ready(function(e) {
		$("#imageprofile").change(function () {
			readimageprofileURL(this);
		});
	});
	// $(document).ready(function(e) {
	// 	$("#idproof").change(function () {
	// 		readURLidproof(this);
	// 	});
	// });
	$(document).ready(function(e) {
		$("#licensedoc").change(function () {
			readURLlicensedoc(this);
		});
	});
	$(document).ready(function(e) {
		$("#adharfront").change(function () {
			readURLadharfrontdoc(this);
		});
	});
	$(document).ready(function(e) {
		$("#pancard_idproof").change(function () {
			readURLpancard_idproofdoc(this);
		});
	});
	$(document).ready(function(e) {
		$("#adharback").change(function () {
			readURLadharbackdoc(this);
		});
	});
	function readURLadharfrontdoc(input){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				jQuery('#image-src-adharfront').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	function readURLpancard_idproofdoc(input)
	{
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				jQuery('#image-src-pancard_idproof').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	function readURLadharbackdoc(input){
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				jQuery('#image-src-adharback').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	function readimageprofileURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				jQuery('#image-src-profile').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	// function readURLidproof(input) {
	// 	if (input.files && input.files[0]) {
	// 		var reader = new FileReader();
	// 		reader.onload = function (e) {
	// 			jQuery('#image-src-idproof').attr('src', e.target.result);
	// 		}
	// 		reader.readAsDataURL(input.files[0]);
	// 	}
	// }
	function readURLlicensedoc(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				jQuery('#image-src-lincense').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	
  	// CREATE
  	function saveData(){
		var data = new FormData();
		data.append('role', '{{$role}}');
		data.append('name', $('#name').val());
		data.append('email', $('#email').val());
		data.append('phone_number', $('#phone_number').val());
		data.append('documnets', $('#idproof_upload option:selected').val());
		data.append('organization', $('#vendorlist option:selected').val());
		// data.append('adharcard', $('#adhar').val());
		// data.append('pancard', $('#pancard').val());
		// data.append('license', $('#license').val());
		data.append('state_id', $('#state_id').val());
		data.append('city_id', $('#city_id').val());
		data.append('latitude', $('#latitude').val());
		data.append('longitude', $('#longitude').val());
		data.append('address', $('#searchTextField').val());
		data.append('gender', $("input[name='gender']:checked").val());
		data.append('starttime', $('#starttime').val());
		data.append('endtime', $('#endtime').val());
		data.append('password', $('#password').val());
		data.append('status', $('#status').val());
		data.append('dob', $('#dob').val());
		data.append('image', $('#imageprofile')[0].files[0]);
		data.append('pancardidproof', $('#pancard_idproof')[0].files[0]);
		data.append('adharfront', $('#adharfront')[0].files[0]);
		data.append('adharback', $('#adharback')[0].files[0]);
		data.append('licensedoc', $('#licensedoc')[0].files[0]);
		
		var response = adminAjax('{{route("user.management.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.href ='{{route("user.management",[$role])}}'; }, 2000)
			
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