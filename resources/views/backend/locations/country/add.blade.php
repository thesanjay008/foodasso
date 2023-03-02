@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('country.create') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a href="{{route('countries.index')}}">Countries</a></li>
							<li class="active breadcrumb-item" aria-current="page">Create</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form action="javascript:void(0);" onsubmit="saveData()">
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title" class="">Country Name</label>
							<input id="title" type="text"class="form-control" required>
							<div class="validation-div" id="val-title"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="iso_code" class="">ISO Code</label>
							<input id="iso_code" type="text" class="form-control">
							<div class="validation-div" id="val-iso_code"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="calling_code" class="">Calling Code</label>
							<input id="calling_code" type="text" class="form-control">
							<div class="validation-div" id="val-calling_code"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="currency">Currency</label>
							<input id="currency" type="text" class="form-control">
							<div class="validation-div" id="val-currency"></div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="currency_code" class="">Currency code</label>
							<input id="currency_code" type="text" class="form-control">
							<div class="validation-div" id="val-currency_code"></div>
						</div>
					</div>

					
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="currency_symbol" class="">Currency Symbol</label>
							<input id="currency_symbol" type="text" class="form-control">
							<div class="validation-div" id="val-currency_symbol"></div>
						</div>
					</div>
				</div>
	
				<hr>
				<div class="form-row">
					<div class="col-md-2">
						<div class="form-group">
							<select class="form-control" name="status" id="status" required>
								<option value="active">{{trans('common.active')}}</option>
								<option value="inactive">{{trans('common.inactive')}}</option>
							</select>
							<div class="validation-div" id="val-status"></div>
						</div>
					</div>
				</div>
				<button class="mt-2 btn btn-primary">Submit</button>
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
		data.append('title', $('#title').val());
		data.append('iso_code', $('#iso_code').val());
		data.append('calling_code', $('#calling_code').val());
		data.append('currency_code', $('#currency_code').val());
		data.append('currency', $('#currency').val());
		data.append('currency_symbol', $('#currency_symbol').val());
		data.append('status', $('#status').val());
		var response = adminAjax('{{route("countries.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.href = "{{route('countries.index')}}"; }, 2000)
			
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