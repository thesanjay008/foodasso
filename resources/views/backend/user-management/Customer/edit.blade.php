@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('customer.edit') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a href="{{ route('user.management',['Customer']) }}">{{ trans('customer.plural') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('customer.edit') }}</li>
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
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="name">{{ trans('common.name') }}</label>
									<input id="name" type="text" class="form-control" value="{{$data->name}}" placeholder="{{ trans('common.placeholder.name') }}">
									<div class="validation-div" id="val-name"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="email">{{ trans('common.email') }}</label>
									<input id="email" type="text" class="form-control" value="{{$data->email}}" placeholder="{{ trans('common.placeholder.email') }}">
									<div class="validation-div" id="val-email"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="phone_number">{{ trans('common.phone_number') }}</label>
									<input id="phone_number" type="text" class="form-control" value="{{$data->phone_number}}" placeholder="{{ trans('common.placeholder.phone_number') }}">
									<div class="validation-div" id="val-phone_number"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="password">{{ trans('common.new_password') }}</label>
									<input id="password" type="text" class="form-control" value="" placeholder="{{ trans('common.placeholder.password') }}">
									<div class="validation-div" id="val-password"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleFile">{{ trans('common.image') }}</label>
							<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
							<div class="validation-div" id="val-image"></div>
							<div class="image-preview"><img id="image-src" src="@if($data->profile_image) {{asset($data->profile_image)}} @endif" width="100%"/></div>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="status" class="">{{ trans('common.status') }}</label>
							<select  class="form-control" id="status">
							  <option value="Active">{{trans('common.active')}}</option>
							  <option value="Inactive">{{trans('common.inactive')}}</option>
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
	<hr>
	@include('backend.user-management.user-info')
	
@endsection

@section('js')
<script>
	$(document).ready(function(e) {
		$("#image").change(function () {
			readURL(this);
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
		data.append('role', '{{$data->user_type}}');
		data.append('user_id', '{{$data->id}}');
		data.append('name', $('#name').val());
		data.append('email', $('#email').val());
		data.append('phone_number', $('#phone_number').val());
		data.append('password', $('#password').val());
		data.append('status', $('#status').val());
		data.append('image', $('#image')[0].files[0]);
		
		var response = adminAjax('{{route("user.management.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.href ='{{route("user.management",[$data->user_type])}}'; }, 2000)
			
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