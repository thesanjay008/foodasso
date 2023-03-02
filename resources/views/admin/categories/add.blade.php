@extends('layouts.backend.master')
@section('css')
@endsection  
@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('category.add') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="{{ route('categories.index') }}">{{ trans('category.singular') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('category.add') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form class="" action="javascript:void(0);" onsubmit="saveMenuCategory()"  enctype="multipart/form-data">
				@foreach(config('app.locales') as $lk=>$lv)
					<div class="form-row">
						<div class="col-md-12">
							<div class="position-relative form-group">
								<label for="title_{{$lk}}" class="">{{ trans('category.title_'.$lk) }}</label>
								<input type="text" id="title_{{$lk}}" value="{{old('title_:'.$lk)}}" placeholder="{{ trans('category.title_'.$lk) }}" class="form-control">
								<div class="validation-div" id="val-title_{{$lk}}"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-12">
							<div class="position-relative form-group">
								<label for="description_{{$lk}}" class="">{{ trans('category.description_'.$lk) }}</label>
								<textarea  name="description_{{$lk}}" id="description_{{$lk}}"  rows="4" class="form-control"> {{old('description_:'.$lk)}}</textarea>
								<div class="validation-div" id="val-description_{{$lk}}"></div>
							</div>
						</div>
					</div>
				@endforeach
				<div class="form-row">
					<div class="col-md-4">
		                <div class="form-group @error('status') ? has-error : ''  @enderror">
		                    {{Form::label('status', trans('common.status'),['class' => 'content-label'])}}<br>
		                    <select  class="form-control" minlength="2" maxlength="255" id="status" name="status">
		                      <option value="active">{{trans('common.active')}}</option>
		                      <option value="inactive ">{{trans('common.inactive')}}</option>
		                    </select>
		                     @if ($errors->has('status')) 
		                    <strong class="help-block">{{ $errors->first('status') }}</strong>
		                  @endif
		                </div>
      				</div>
    			</div>
				<div class="form-row">
				<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script>

  // CREATE
  function saveMenuCategory(){
	var data = new FormData();
	data.append('title_en', $('#title_en').val());
	data.append('description_en', $('#description_en').val());
	data.append('status', $('#status').val());
	var response = adminAjax('{{route("categories.store")}}', data);
	if(response.status == '200'){
		// alert('Success!!');
		window.location.href = "{{route('categories.index')}}";
	}else if(response.status == '422'){
		$('.validation-div').text('');
		$.each(response.error, function( index, value ) {
			$('#val-'+index).text(value);
		});
	}
  }
</script>
@endsection