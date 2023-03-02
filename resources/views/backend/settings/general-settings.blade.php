@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('settings.general.heading') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('homePage') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="active breadcrumb-item"> <a>{{ trans('settings.general.plural') }}</a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('settings.information')}}</div>
			<div class="btn-actions-pane-right text-capitalize">
				<!--<a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" href="">{{trans('order.add')}}</a>-->
			</div>
		</div>
		<div class="card-body">
			<form id="save-general-settings" action="javascript:void(0);" onsubmit="saveSettings()">
				@if($data)
				@foreach($data as $row)
				<div class="position-relative row form-group">
					<label for="exampleEmail" class="col-sm-3 col-form-label">{{ trans('settings.'.$row->name) }}</label>
					<div class="col-sm-9">
						<input type="text" id="{{ $row->name }}" class="form-control" value="{{ $row->value }}"/>
						<div class="validation-div" id="val-{{ $row->name }}"></div>
					</div>
				</div>
				@endforeach
				<button type="submit" class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
				@endif
			</form>
		</div>
	</div>
	
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('settings.logo')}}</div>
		</div>
		<div class="card-body">
			<form id="save-qr-code" action="javascript:void(0);" onsubmit="saveLogo()">
				<div class="box-header with-border">
					<div class="col-sm-10">
						<img id="logo-image-view" style="max-height: 180px;" src="@if(Settings::get('logo')){{ asset(Settings::get('logo')) }} @endif"/>
						<input name="file" id="logo-image" type="file" class="form-control-file">
						<small class="form-text text-muted">Max size 300 KB</small>
					</div>
				</div>
				<br>
				<br>
				<br>
				<button type="submit" class="mt-2 btn btn-primary">{{ trans('settings.save_logo') }}</button>
			</form>
		</div>
	</div>
	
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('settings.qr_code')}}</div>
			<div class="btn-actions-pane-right text-capitalize">
				<a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" href="" title="Share to WhatsApp"><i class="fa fa-facebook" aria-hidden="true"></i></a>
			</div>
		</div>
		<div class="card-body">
			<form id="save-qr-code" action="javascript:void(0);" onsubmit="saveQRcode()">
				<div class="position-relative row form-group qr-code-box">
					<div class="col-sm-4"></div>
					<div class="col-sm-4">
						<img src="@if(Settings::get('qr_code')){{ Settings::get('qr_code') }} @endif" class="qr-img">
					</div>
					<div class="col-sm-4"></div>
				</div>
				<button type="submit" class="mt-2 btn btn-primary">{{ trans('settings.generate_new_qr') }}</button>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script>
	// SAVE
  	function saveSettings(){
		var data = new FormData();
		@if($data)
		@foreach($data as $row)
		data.append('{{ $row->name }}', $('#{{ $row->name }}').val());
		@endforeach
		@endif
		var response = adminAjax('{{route("ajax.store.general-settings")}}', data);
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

<script>
	// QR Code
	var parametersJson = {
		"size": 250, // Size of Qr Code
		"backgroundColor": "38-38-38", // Background Color Of Qr Code (In RGB)
		"qrColor": "255-255-255", // Color of Qr Code (In RGB)
		"padding": 2, // Padding 
		"data": "dev.to"
	};

	var parameters;

	var btn = document.querySelector("button");
	var img = document.querySelector(".qr-img");
	var siteUrl = '{{ url("/menu") }}';

	function saveQRcode(){
		
		var url = `https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=${siteUrl}`;
		
		var data = new FormData();
		data.append('file', url);
		var response = adminAjax('{{route("ajax.store.qr-code")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			img.src = url;
		} else{
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>

<!-- Logo Upload -->
<script>
	$(document).ready(function(e) {
		$("#logo-image").change(function () {
			readURL(this);
		});
	});
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				jQuery('#logo-image-view').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
		
	function saveLogo(){
		var data = new FormData();
		data.append('logo', jQuery('#logo-image')[0].files[0]);
		var response = adminAjax('{{route("ajax.store.logo")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
		} else{
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection