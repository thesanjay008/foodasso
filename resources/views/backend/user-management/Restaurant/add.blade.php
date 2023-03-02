@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('restaurant.create') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a href="{{ route('user.management',['Restaurant']) }}">{{ trans('restaurant.plural') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('restaurant.add') }}</li>
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
			<form id ="restaurent_form" action="javascript:void(0);" onsubmit="saveData();">
				<div class="form-row">
					<div class="col-md-6">
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
									<label for="phone_number">{{ trans('common.phone_number') }}</label>
									<input id="phone_number" type="text" class="form-control" placeholder="{{ trans('common.placeholder.phone_number') }}">
									<div class="validation-div" id="val-phone_number"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="password">{{ trans('common.password') }}</label>
									<input id="password" type="password" class="form-control" placeholder="{{ trans('common.placeholder.password') }}">
									<div class="validation-div" id="val-password"></div>
								</div>
							</div>
							
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
								<br/>
								<div id="map_canvas" style="height:350px; width:100%;"></div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="exampleFile">{{ trans('common.image') }}</label>
									<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-image"></div>
									<div class="image-preview"><img id="image-src" src="" width="70%"/></div>
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
	$(document).ready(function(e) {
		$("#image").change(function () {
			readURL(this);
		});

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
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				jQuery('#image-src').attr('src', e.target.result);
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
		data.append('state_id', $('#state_id').val());
		data.append('city_id', $('#city_id').val());
		data.append('opening_time', $('#opening_time').val());
		data.append('closing_time', $('#closing_time').val());
		data.append('latitude', $('#latitude').val());
		data.append('longitude', $('#longitude').val());
		data.append('address', $('#searchTextField').val());
		data.append('password', $('#password').val());
		data.append('delivery_by', $('#delivery_by option:selected').val());
		data.append('status', $('#status').val());
		data.append('image', $('#image')[0].files[0]);
		
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