@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('delivery.edit') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a href="{{ route('user.management',['DeliveryBoy']) }}">{{ trans('delivery.plural') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('delivery.edit') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form class="" action="javascript:void(0);">
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="name">{{ trans('common.name') }}</label>
							<input id="name" type="text" class="form-control" value="{{$data->name}}" placeholder="{{ trans('common.placeholder.name') }}">
							<div class="validation-div" id="val-name"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="email">{{ trans('common.email') }}</label>
							<input id="email" type="text" class="form-control" value="{{$data->email}}" placeholder="{{ trans('common.placeholder.email') }}">
							<div class="validation-div" id="val-email"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="phone_number">{{ trans('common.phone_number') }}</label>
							<input id="phone_number" type="text" class="form-control" value="{{$data->phone_number}}" placeholder="{{ trans('common.placeholder.phone_number') }}">
							<div class="validation-div" id="val-phone_number"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="password">{{ trans('common.new_password') }}</label>
							<input id="password" type="text" class="form-control" value="" placeholder="{{ trans('common.placeholder.password') }}">
							<div class="validation-div" id="val-password"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="dob">{{ trans('common.dob') }}</label>
							<input id="dob" type="date" max='<?php echo Date("Y-m-d"); ?>' value="{{$data->dob}}" class="form-control" placeholder="{{ trans('common.placeholder.dob') }}">
							<div class="validation-div" id="val-dob"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="phone_number">{{ trans('delivery.gender') }}</label>
							<br>
							<input type="radio" id="gender" name="gender" value="Male" {{$data->gender == "Male" ? 'checked' : ''}}>
							<label for="Male">Male</label>
							<input type="radio" id="gender" name="gender" value="Female" {{$data->gender == "Female" ? 'checked' : ''}}>
							<label for="Female">Female</label>
							<div class="validation-div" id="val-gender"></div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="starttime">{{ trans('common.starttime') }}</label>
							<input id="starttime" type="time" class="form-control" value="{{$datainfo->start_time}}" placeholder="{{ trans('common.placeholder.starttime') }}">
							<div class="validation-div" id="val-starttime"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="endtime">{{ trans('common.endtime') }}</label>
							<input id="endtime" type="time" value="{{$datainfo->end_time}}" class="form-control" placeholder="{{ trans('common.placeholder.endtime') }}">
							<div class="validation-div" id="val-endtime"></div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label>Busy On Delivery</label>
							<select class="form-control" id="is_occupie">
								<option value="0" @if(isset($userInfo->is_occupie)) {{ $userInfo->is_occupie == "0" ? 'selected' : '' }} @endif >No</option>
								<option value="1" @if(isset($userInfo->is_occupie)) {{ $userInfo->is_occupie == "1" ? 'selected' : '' }} @endif >Yes</option>
							</select>
							<div class="validation-div" id="val-is_occupie"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="idproof">Deliveryboy Type <b>(Working for)</b></label>
							<select class="form-control" id="deliveryboy_assign">
								
								<option value="">Select Deliveryboy Type</option>
								<option value="selfdel">HealU</option>
								<option value="vendordel" {{ $datainfo->organization_id !='' ? 'selected' : '' }}>Organization</option>
							</select>
							<div class="validation-div" id="val-assigndeliveryboy"></div>
						</div>
					</div>
					
					<div class="col-md-3" id="selectvendor_type" style="margin-top: 30px;">
						<div class="position-relative form-group">
							<select class="form-control" id="assign_vendor">
								<option value="">Select Vedor Type</option>
								<option value="Store" @if(isset($user_info) && $user_info != '') {{ $user_info->user_type == "Store" ? 'selected' : '' }} @endif>Store</option>
								<option value="Restaurant" @if(isset($user_info) && $user_info != '') {{ $user_info->user_type == "Restaurant" ? 'selected' : '' }} @endif>Restaurant</option>
							</select>
						</div>
					</div>
					<input type=hidden id="myHiddenInputId" name="myHiddenInputName" value="@if(isset($user_info) && $user_info != '') {{$user_info->id}}  @endif" />
					<div class="col-md-4" id="vendorlist_all" style="margin-top: 30px;">
						<div class="position-relative form-group">
							<select class="form-control" id="vendorlist"></select>
							<div class="validation-div" id="val-assignvendor"></div>
						</div>
					</div>
				</div>
					
				<div class="form-row">
					<div class="col-md-12"><hr></div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleFile">Image</label>
							<input name="file" id="imageprofile" type="file" class="form-control-file item-img" accept="image/*">
							<div class="validation-div" id="val-image"></div>
							<div class="image-preview"><img id="image-src-profile" src="@if($data->profile_image) {{asset($data->profile_image)}} @endif" width="100%"/></div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="licensedoc">{{ trans('common.license') }}</label>
							<input name="file" id="licensedoc" type="file" class="form-control-file item-img" accept="image/*">
							<div class="validation-div" id="val-licensedoc"></div>
							<div class="image-preview"><img id="image-src-lincense" src="@if($datainfo->license) {{asset('deliveryboy_lincense/'.$datainfo->license)}} @endif" width="100%"/></div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-12"><hr></div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="idproof">{{ trans('delivery.idproof') }}</label>
							<select class="form-control" id="idproof_upload">
								<option value="">Select Id Proof</option>
								<option value="aadharcard" {{ $datainfo->document_type == "aadharcard" ? 'selected' : '' }}>Aadhar Card</option>
								<option value="pancard"  {{ $datainfo->document_type == "pancard" ? 'selected' : '' }}>Pan Card</option>
								<div class="validation-div" id="val-documnets"></div>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6" id="pancardidproof">
								<div class="position-relative form-group">
									<label for="pancard_idproof">{{ trans('delivery.document') }}(Pan Card)</label>
									<input name="file" id="pancard_idproof" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-pancardidproof"></div>
									<div class="image-preview"><img id="image-src-pancard_idproof" src="@if($datainfo->document_type == 'pancard') @if($datainfo->documentfile_front){{asset('deliveryboy_idproof_document/'.$datainfo->documentfile_front)}} @endif @endif" width="100%"/></div>
								</div>
							</div>
							<div class="col-md-6" id="adharcardidproof">
								<div class="position-relative form-group">
									<label for="adharfront">{{ trans('delivery.document') }}(Aadhar Card Front)</label>
									<input name="file" id="adharfront" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-adharfront"></div>
									<div class="image-preview"><img id="image-src-adharfront" src="@if($datainfo->document_type == 'aadharcard') @if($datainfo->documentfile_front){{asset('deliveryboy_idproof_document/'.$datainfo->documentfile_front)}} @endif @endif" width="100%"/></div>
								</div>
								<div class="position-relative form-group">
									<label for="adharback">{{ trans('delivery.document') }}(Aadhar Card Back)</label>
									<input name="file" id="adharback" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-adharback"></div>
									<div class="image-preview"><img id="image-src-adharback" src="@if($datainfo->document_type == 'aadharcard') @if($datainfo->documentfile_back){{asset('deliveryboy_idproof_document/'.$datainfo->documentfile_back)}} @endif @endif" width="100%"/></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-12"><hr></div>
					<div class ="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="state_id">{{ trans('common.State') }}</label>
									<select name="state_id" id="state_id" class="form-control">
										<option value="">--select--</option>
										@if($state->count())
											@foreach($state as $state_list)
												<option value="{{$state_list->id}}" @if($state_list->id == $datainfo->state_id)selected @endif>{{$state_list->title}}</option>
											@endforeach
										@endif
									</select>
									<div class="validation-div" id="val-state_id"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="city_id">{{ trans('common.city') }}</label>
									<select name="city_id" id="city_id" class="form-control">
										@if(!empty($city))
											@foreach($city as $city_list)
												<option value="{{$city_list->id}}" @if($city_list->id == $datainfo->city_id)selected @endif>{{$city_list->title}}</option>
											@endforeach
										@endif
									</select>
									<div class="validation-div" id="val-city_id"></div>
								</div>
							</div>
							<div class="col-md-12">
								Address:
								<input id="searchTextField"  class="form-control"  type="text" value="{{$datainfo->address}}" size="50" style="text-align: left;direction: ltr;">
								<br>
								<div class="row">
									<div class="col-md-6">
										Latitude:<input name="latitude" id="latitude" class="MapLat form-control" value="{{$datainfo->latitude}}" type="text" placeholder="Latitude"  disabled>
										<div class="validation-div" id="val-longitude"></div>
									</div>
									<div class="col-md-6">
										Longitude:<input name="longitude" id="longitude" class="MapLon form-control" value="{{$datainfo->longitude}}" type="text" placeholder="Longitude"  disabled>
										<div class="validation-div" id="val-longitude"></div>
									</div>
								</div>
								<br>
								<div id="map_canvas" style="height: 350px; width: 100%;"></div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="status" class="">{{ trans('common.status') }}</label>
							<select  class="form-control" id="status">
							  <option value="Active"  @if($data->status == 'active') selected @endif>{{trans('common.active')}}</option>
							  <option value="Inactive" @if($data->status == 'inactive') selected @endif>{{trans('common.inactive')}}</option>
							</select>
							<div class="validation-div" id="val-status"></div>
						</div>
					</div>
				</div>
				<button type="submit" class="mt-2 btn btn-primary" onclick="saveData()">{{ trans('common.submit') }}</button>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
	
	<hr>
	@include('backend.user-management.user-info')
@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key={{Settings::get('google_map_api_key')}}&libraries=places"></script>
<script>
	var lat = '{{$userInfo->latitude}}';
	var lng = '{{$userInfo->longitude}}';
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
	<?php if($datainfo->document_type == "aadharcard"){ ?>
		$('#adharcardidproof').show();
		$('#pancardidproof').hide();
	<?php }elseif($datainfo->document_type == "pancard"){ ?>
		$('#pancardidproof').show();
	$('#adharcardidproof').hide();
		<?php }else{
	?>
	$('#pancardidproof').hide();
	$('#adharcardidproof').hide();
	<?php
		} ?>
	
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
	$(document).ready(function(e) {
		var organizationid =$('#myHiddenInputId').val();
		if(organizationid != '')
		{
			$('#selectvendor_type').show();
		}
		var vendor = $('#assign_vendor :selected').text();
		var data = new FormData();
		data.append('assignvendor', vendor);
		var response = adminAjax('{{route("deliveryboy.vendortype.assignlist")}}', data);
		if(response.status == '200'){
			if(response.data.length !== 0){
				$('#vendorlist_all').show();
				$('#vendorlist').empty();
				$.each(response.data, function(key, value){
					if(value.id == organizationid)
					{
						var selected = 'selected';
					}
					else{
						var selected = '';
					}
					console.log(value.title);
					$('#vendorlist').append('<option value="'+value.id+'"'+selected+'>'+value.name+'</option>');
				});
			}else{
				$('#vendorlist').html('<option value="">'+response.message+'</option>');
			}
		}
	});
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
	// $(document).ready(function(e) {
	// 	$('.documentssubmit').hide();
	// 	$(".documentsreq").change(function () {
	// 		var value = $(this).val();
	// 		if(value == "adhar")
	// 		{
	// 			$('#adharcard').show();
	// 			$('#pancards').hide();
	// 		}
	// 		else if(value == "pan")
	// 		{
	// 			$('#pancards').show();
	// 			$('#adharcard').hide();
	// 		}
	// 		else
	// 		{
	// 			$('.documentssubmit').hide();
	// 		}
	// 	});
	// });
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
		var deliveryboy_type = $('#deliveryboy_assign option:selected').val();
		if(deliveryboy_type == 'selfdel'){var organization = '';}
		else{var organization = $('#vendorlist option:selected').val();}
		var data = new FormData();
		data.append('role', '{{$role}}');
		data.append('user_id', '{{$data->id}}');
		data.append('name', $('#name').val());
		data.append('email', $('#email').val());
		data.append('phone_number', $('#phone_number').val());
		data.append('documnets', $('#idproof_upload option:selected').val());
		data.append('organization', organization);
		data.append('gender', $("input[name='gender']:checked").val());
		data.append('state_id', $('#state_id').val());
		data.append('city_id', $('#city_id').val());
		data.append('latitude', $('#latitude').val());
		data.append('longitude', $('#longitude').val());
		data.append('address', $('#searchTextField').val());
		data.append('starttime', $('#starttime').val());
		data.append('endtime', $('#endtime').val());
		data.append('password', $('#password').val());
		data.append('status', $('#status').val());
		data.append('dob', $('#dob').val());
		data.append('is_occupie', $('#is_occupie').val());
		data.append('image', $('#imageprofile')[0].files[0]);
		data.append('licensedoc', $('#licensedoc')[0].files[0]);
		data.append('pancardidproof', $('#pancard_idproof')[0].files[0]);
		data.append('adharfront', $('#adharfront')[0].files[0]);
		data.append('adharback', $('#adharback')[0].files[0]);

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

<script>
	// UPDATE WALLET
  	function updateWallet(){
		var data = new FormData();
		data.append('user_id', '{{$data->id}}');
		data.append('title', $('#walletModal #title').val());
		data.append('amount', $('#walletModal #amount').val());
		data.append('type', $('input[name="typeRadio"]:checked').val());

		var response = adminAjax('{{route("ajax.user.update.wallet")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.reload(); }, 2000)
			
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection