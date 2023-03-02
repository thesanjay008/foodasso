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
						<span class="d-inline-block">{{ trans('category.update') }}</span>
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
			<form class="" action="javascript:void(0);" onsubmit="saveMenuCategory()">
				<input name="_method" type="hidden" value="PUT">
				@foreach(config('app.locales') as $lk=>$lv)
					<div class="form-row">
						<div class="col-md-12">
							<div class="position-relative form-group">
								<label for="title_{{$lk}}" class="">{{ trans('category.title_'.$lk) }}</label>
								<input type="text" id="title_{{$lk}}" value="{{$categories->translate($lk)->title}}" placeholder="{{ trans('category.title_'.$lk) }}" class="form-control">
								<div class="validation-div" id="val-title_{{$lk}}"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-12">
							<div class="position-relative form-group">
								<label for="description_{{$lk}}" class="">{{ trans('category.description_'.$lk) }}</label>
								<textarea  name="description_{{$lk}}" id="description_{{$lk}}"  rows="4" class="form-control"> {{$categories->translate($lk)->description}}</textarea>
								<div class="validation-div" id="val-description_{{$lk}}"></div>
							</div>
						</div>
					</div>
				@endforeach
				<div class="form-row">
					<div class="col-md-6">
              <div class="form-group">
                  <label for="status" class="content-label">{{trans('common.status')}}</label>
                  <select class="form-control" name="status" id="status" required>
                    <option value="active" 
                      @if($categories->status == 'active') selected @endif>
                      {{trans('common.active')}}
                    </option>
                    <option value="inactive" 
                      @if($categories->status == 'inactive') selected @endif>
                      {{trans('common.inactive')}}
                    </option>
                  </select>
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
	data.append('item_id', '{{$categories->id}}');
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