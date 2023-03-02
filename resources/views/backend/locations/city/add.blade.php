@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('city.create') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a href="{{route('cities.index')}}">Cities</a></li>
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
							<label for="title">City Name</label>
							<input id="title" type="text"class="form-control" required>
							<div class="validation-div" id="val-title"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="state_id">State</label>
							<select class="form-control" id="state_id">
							  <option value="">Select State</option>
							  @foreach($states as $list)
							  <option value="{{$list->id}}">{{$list->title}}</option>
							  @endforeach
							</select>
							<div class="validation-div" id="val-state_id"></div>
						</div>
					</div>
				</div>
				
				<hr>
				<div class="form-row">
					<div class="col-md-2">
						<div class="form-group">
						<label for="state_id">Status</label>
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
		data.append('state_id', $('#state_id').val());
		data.append('status', $('#status').val());
		var response = adminAjax('{{route("cities.store")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ window.location.href = "{{route('cities.index')}}"; }, 2000)
			
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