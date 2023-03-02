@extends('layouts.backend.master')
@section('css')
 <link rel="stylesheet" type="text/css" href="{{ asset('public/css/multiselect/multiselect.css') }}"> 
 @endsection  
@section('content')
				<div class="app-page-title app-page-title-simple">
					<div class="page-title-wrapper">
						<div class="page-title-heading">
							<div>
								<div class="page-title-head center-elem">
									<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
									<span class="d-inline-block">{{ trans('addon.add') }}</span>
								</div>
							</div>
						</div>
						<div class="page-title-actions">
							<div class="page-title-subheading opacity-10">
								<nav class="" aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
										<li class="breadcrumb-item"> <a  href="{{ route('addons.index') }}">{{ trans('addon.singular') }}</a></li>
										<li class="active breadcrumb-item" aria-current="page">{{ trans('addon.add') }}</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
				</div>

				<!-- CONTENT START -->
				<div class="main-card mb-3 card">
					<div class="card-body">
						<form class="" action="javascript:void(0);" onsubmit="saveAddon()"  enctype="multipart/form-data">
							<div class="form-row">
								<div class="col-md-6">
									<div class="position-relative form-group">
										<label for="title" class="">{{ trans('addon.title') }}</label>
										<input type="text" id="title" placeholder="{{ trans('addon.placeholder.enter_title') }}" class="form-control">
										<div class="validation-div" id="val-title"></div>
									</div>
								</div>
							</div>
							
							<div class="form-row">
								<div class="col-md-3">
									<div class="position-relative form-group">
										<label for="price" class="">{{ trans('addon.price') }}</label>
										<input type="text" id="price" placeholder="{{ trans('addon.placeholder.enter_price') }}" class="form-control">
										<div class="validation-div" id="val-price"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
					            		<div class="position-relative form-group">
						                  	<label for="addon_group_id" class="content-label">{{trans('addon.addon_group')}}</label>
						                  	<select class="form-control" id="addon_group_id">
						                  	 	<option value=''>{{trans('addon.select_addon_group')}} </option>
						                  		@foreach ($addon_groups as $addon_grp)
												<option value= "{{$addon_grp->id}}"
						                        @if(old('addon_grp') ==  $addon_grp->id) selected @endif>
						                        {{$addon_grp->title}}
						                      	</option>
						                    	@endforeach
						                 	 </select>
						                  <div class="validation-div" id="val-addon_group_id"></div>
						              	</div>
						          	</div>
								</div>
								<div class="col-md-2">
									<div class="form-group @error('status') ? has-error : ''  @enderror">
										{{Form::label('status', trans('common.status'),['class' => 'content-label'])}}<br>
										<select  class="form-control" minlength="2" maxlength="255" id="status" name="status">
										  <option value="active">{{trans('common.active')}}</option>
										  <option value="inactive ">{{trans('common.inactive')}}</option>
										</select>
										<div class="validation-div" id="star"></div>
										@if ($errors->has('status')) 
										<strong class="help-block">{{ $errors->first('status') }}</strong>
										@endif
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
<script src="{{ asset('public/js/multiselect/multiselect.min.js') }}"></script>
<script>

  // CREATE
  function saveAddon(){
	var data = new FormData();
	data.append('title', $('#title').val());
	data.append('price', $('#price').val());
	data.append('choice', $('#choice').val());
	data.append('addon_group_id', $('#addon_group_id').val());
	data.append('status', $('#status').val());

	var response = adminAjax('{{route("addons.store")}}', data);
	if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.href = '{{route("addons.index")}}'; }, 2000)

	}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
			
	}else if(response.status == '201'){
		$('.validation-div').text('');
		swal.fire({title: response.message,type: 'error'});
	}
  }
</script>
@endsection