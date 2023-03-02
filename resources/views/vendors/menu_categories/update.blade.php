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
									<span class="d-inline-block">{{ trans('menu_category.update') }}</span>
								</div>
							</div>
						</div>
						<div class="page-title-actions">
							<div class="page-title-subheading opacity-10">
								<nav class="" aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
										<li class="breadcrumb-item"> <a  href="{{ route('menu_categories.index') }}">{{ trans('menu_category.singular') }}</a></li>
										<li class="active breadcrumb-item" aria-current="page">{{ trans('menu_category.add') }}</li>
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
							<div class="form-row">
								<div class="col-md-6">
									<div class="position-relative form-group">
										<label for="title_in_english" class="">{{ trans('product.title_in_english') }}</label>
										<input type="text" id="title_in_english" placeholder="{{ trans('product.placeholder.enter_title') }}" value="{{$menu_categories->translate('en')->title}}" class="form-control">
										<div class="validation-div" id="val-title_in_english"></div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="position-relative form-group">
										<label for="priority" class="">{{ trans('menu_category.priority') }}</label>
										<input type="text" id="priority" placeholder="{{ trans('menu_category.placeholder.enter_priority') }}" value="{{$menu_categories->priority}}" class="form-control">
										<div class="validation-div" id="val-priority"></div>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6">
				                  <div class="form-group">
				                    <label for="status" class="content-label">{{trans('common.status')}}</label>
				                    <select class="form-control" name="status" id="status" required>
				                      <option value="active" 
				                        @if($menu_categories->status == 'active') selected @endif>
				                        {{trans('common.active')}}
				                      </option>
				                      <option value="inactive" 
				                        @if($menu_categories->status == 'inactive') selected @endif>
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
	data.append('item_id', '{{$menu_categories->id}}');
	data.append('title_in_english', $('#title_in_english').val());
	data.append('priority', $('#priority').val());
	data.append('status', $('#status').val());
	var response = adminAjax('{{route("menu_categories.store")}}', data);
	if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.reload(); }, 2000)
	}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
	}else if(response.status == '201'){
		swal.fire({title: response.message,type: 'error'});
	}
  }
</script>
@endsection