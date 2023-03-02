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
						<span class="d-inline-block">{{ trans('coupons.update') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="{{ route('coupons.index') }}">{{ trans('coupons.singular') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('coupons.add') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form class="" action="javascript:void(0);" onsubmit="saveCoupon()">
				<input name="_method" type="hidden" value="PUT">
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title" class="">{{ trans('product.title') }}</label>
							<input type="text" id="title" placeholder="{{ trans('product.placeholder.enter_title') }}" value="{{$coupons->title}}" class="form-control">
							<div class="validation-div" id="val-title"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="code" class="">{{ trans('coupons.code') }}</label>
							<input type="text" id="code" placeholder="{{ trans('coupons.placeholder.enter_code') }}" class="form-control" value="{{$coupons->code}}">
							<div class="validation-div" id="val-code"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="discount" class="">{{ trans('coupons.discount') }}</label>
							<div class="input-group">
                                <input class="form-control" placeholder="{{trans('coupons.placeholder.enter_discount')}}" name="discount" type="text" id="discount" value="{{$coupons->discount}}">
                                <select name="discount_type" class="selectpicker" id="discount_type">
                                    <option value="amount" @if($coupons->discount_type == 'amount') selected @endif>₹</option>
                                    <option value="percentage" @if($coupons->discount_type == 'percentage') selected @endif>%</option>
                                </select>
                            </div>
							<div class="validation-div" id="val-discount"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="start_date" class="">{{ trans('coupons.start_date') }}</label>
							<input type="text" id="start_date" placeholder="{{ trans('coupons.placeholder.enter_start_date') }}" class="form-control" value="{{date('m/d/Y',strtotime($coupons->start_date))}}" autocomplete="off" data-toggle="datepicker">
							<div class="validation-div" id="val-start_date"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="end_date" class="">{{ trans('coupons.end_date') }}</label>
							<input type="text" id="end_date" placeholder="{{ trans('coupons.placeholder.enter_end_date') }}" class="form-control" value="{{date('m/d/Y',strtotime($coupons->end_date))}}" autocomplete="off" data-toggle="datepicker">
							<div class="validation-div" id="val-end_date"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="description" class="">{{ trans('coupons.description') }}</label>
							<textarea name="description" id="description"  rows="4" class="form-control">{{$coupons->description}}</textarea>
							<div class="validation-div" id="val-description"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-row">
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="exampleFile" class="">{{ trans('coupons.image') }}</label>
									<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-image"></div>
									<div class="image-preview"><img id="image-src" src="@if($coupons->image) {{ asset($coupons->image) }} @endif" width="100%"/></div>
									<input type="hidden" id="img-blob">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">
	                  	<div class="form-group">
		                    <label for="status" class="content-label">{{trans('common.status')}}</label>
		                    <select class="form-control" name="status" id="status" required>
		                      	<option value="active" 
		                        @if($coupons->status == 'active') selected @endif>
		                        {{trans('common.active')}}
		                      	</option>
		                      	<option value="inactive" 
		                        @if($coupons->status == 'inactive') selected @endif>
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
	function saveCoupon(){
		var data = new FormData();
		data.append('item_id', '{{$coupons->id}}');
		data.append('title', $('#title').val());
		data.append('code', $('#code').val());
		data.append('description', $('#description').val());
		data.append('image', $('#image')[0].files[0]);
		data.append('discount', $('#discount').val());
		data.append('discount_type', $('#discount_type').val());
		data.append('start_date', $('#start_date').val());
		data.append('end_date', $('#end_date').val());
		data.append('status', $('#status').val());
		var response = adminAjax('{{route("coupons.store")}}', data);
		if(response.status == '200'){
			window.location.href = "{{route('coupons.index')}}";
		}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
		}
	}
</script>
@endsection