@extends('layouts.backend.master')
@section('content')
		<div class="app-page-title app-page-title-simple">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div>
						<div class="page-title-head center-elem">
							<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
							<span class="d-inline-block">{{ trans('outlets.create') }}</span>
						</div>
					</div>
				</div>
				<div class="page-title-actions">
					<div class="page-title-subheading opacity-10">
						<nav class="" aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
								<li class="breadcrumb-item"> <a>{{ trans('outlets.plural') }}</a></li>
								<li class="active breadcrumb-item" aria-current="page">{{ trans('outlets.add') }}</li>
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
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="title">{{ trans('outlets.title') }}</label>
								<input id="title" type="text" class="form-control" placeholder="{{ trans('outlets.placeholder.title') }}">
								<div class="validation-div" id="val-title"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="email" class="">{{ trans('outlets.email') }}</label>
								<input id="email" type="text" class="form-control" placeholder="{{ trans('outlets.placeholder.email') }}">
								<div class="validation-div" id="val-email"></div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="phone_number" class="">{{ trans('outlets.phone_number') }}</label>
								<input id="phone_number" type="text" class="form-control" placeholder="{{ trans('outlets.placeholder.phone_number') }}">
								<div class="validation-div" id="val-phone_number"></div>
							</div>
						</div>
					</div>
					<hr>
					<div class="form-row">
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="country" class="">{{ trans('outlets.country') }}</label>
								<select  class="form-control" id="country">
								  <option value="">{{trans('outlets.select_country')}}</option>
								  <option value="53">India</option>
								  <option value="27">Cameroon</option>
								</select>
								<div class="validation-div" id="val-country"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="state" class="">{{ trans('outlets.state') }}</label>
								<select  class="form-control" id="state">
								  <option value="">{{trans('outlets.select_state')}}</option>
								  <option value="12">Gujarat</option>
								  <option value="22">Maharastra</option>
								</select>
								<div class="validation-div" id="val-state"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="city" class="">{{ trans('outlets.city') }}</label>
								<select  class="form-control" id="city">
								  <option value="">{{trans('outlets.select_city')}}</option>
								  <option value="1">Ahmedabad</option>
								  <option value="2">Mumbai</option>
								</select>
								<div class="validation-div" id="val-city"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="area" class="">{{ trans('outlets.area') }}</label>
								<input id="area" type="text" class="form-control" placeholder="{{ trans('outlets.placeholder.area') }}">
								<div class="validation-div" id="val-area"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="zip_code" class="">{{ trans('outlets.zip_code') }}</label>
								<input id="zip_code" type="text" class="form-control" placeholder="{{ trans('outlets.placeholder.zip_code') }}">
								<div class="validation-div" id="val-zip_code"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="latitude" class="">{{ trans('outlets.latitude') }}</label>
								<input id="latitude" type="text" class="form-control" placeholder="{{ trans('outlets.placeholder.latitude') }}">
								<div class="validation-div" id="val-latitude"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="longitude" class="">{{ trans('outlets.longitude') }}</label>
								<input id="longitude" type="text" class="form-control" placeholder="{{ trans('outlets.placeholder.longitude') }}">
								<div class="validation-div" id="val-longitude"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group">
								<label for="address" class="">{{ trans('outlets.address') }}</label>
								<input id="address" type="text" class="form-control" placeholder="{{ trans('outlets.placeholder.address') }}">
								<div class="validation-div" id="val-address"></div>
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
								  <option value="Closed">{{trans('common.closed')}}</option>
								  <option value="PickupOnly">{{trans('common.PickupOnly')}}</option>
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
<script>
  	// CREATE
  	function saveData(){
		var data = new FormData();
		data.append('slug', $('#slug').val());
		data.append('title', $('#title').val());
		data.append('email', $('#email').val());
		data.append('phone_number', $('#phone_number').val());
		data.append('zip_code', $('#zip_code').val());
		data.append('country', $('#country').val());
		data.append('state', $('#state').val());
		data.append('city', $('#city').val());
		data.append('area', $('#area').val());
		data.append('latitude', $('#latitude').val());
		data.append('longitude', $('#longitude').val());
		data.append('address', $('#address').val());
		data.append('status', $('#status').val());
		
		var response = adminAjax('{{route("ajax_saveOutlet")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.reload(); }, 2000)
			
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