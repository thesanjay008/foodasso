@extends('layouts.backend.master')
@section('content')
		<div class="app-page-title app-page-title-simple">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div>
						<div class="page-title-head center-elem">
							<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
							<span class="d-inline-block">{{ trans('profile.change_password') }}</span>
						</div>
					</div>
				</div>
				<div class="page-title-actions">
					<div class="page-title-subheading opacity-10">
						<nav class="" aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
								<li class="breadcrumb-item"> <a href="{{ route('my-profile') }}">{{ trans('profile.plural') }}</a></li>
								<li class="active breadcrumb-item" aria-current="page">{{ trans('profile.change_password') }}</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
		
		<!-- CONTENT START -->
		<div class="main-card mb-3 card">
			<div class="card-body">
				<!--<h5 class="card-title">{{trans('profile.change_password')}}</h5>-->
				<form id="changePasword" action="javascript:void(0);" onsubmit="changePasword();">
					<div class="form-row">
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="old_password" class="">{{ trans('profile.title_old_password') }}</label>
								<input type="text" id="old_password" placeholder="{{ trans('profile.placeholder.old_password') }}" class="form-control">
								<div class="validation-div" id="val-old_password"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="password" class="">{{ trans('profile.title_password') }}</label>
								<input type="password" id="password" placeholder="{{ trans('profile.placeholder.password') }}" class="form-control">
								<div class="validation-div" id="val-password"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="password_confirmation" class="">{{ trans('profile.title_password_confirmation') }}</label>
								<input type="password" id="password_confirmation" placeholder="{{ trans('profile.placeholder.password_confirmation') }}" class="form-control">
								<div class="validation-div" id="val-password_confirmation"></div>
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
	// Change
	function changePasword(){
		var data = new FormData();
		data.append('old_password', $('#changePasword #old_password').val());
		data.append('password', $('#changePasword #password').val());
		data.append('password_confirmation', $('#changePasword #password_confirmation').val());
		var response = adminAjax('{{route("changePassword")}}', data);
		if(response.status == '200' && response.status){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.reload(); }, 2000)
			
		}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
		}else {
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection