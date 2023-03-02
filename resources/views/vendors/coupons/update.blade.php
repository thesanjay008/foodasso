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
							<label for="title_in_english" class="">{{ trans('product.title_in_english') }}</label>
							<input type="text" id="title_in_english" placeholder="{{ trans('product.placeholder.enter_title') }}" value="{{$coupons->translate('en')->title}}" class="form-control">
							<div class="validation-div" id="val-title_in_english"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title_in_armenians" class="">{{ trans('product.title_in_armenians') }}</label>
							<input type="text" id="title_in_armenians" placeholder="{{ trans('product.placeholder.enter_title') }}" value="{{$coupons->translate('hy')->title}}" class="form-control">
							<div class="validation-div" id="val-title_in_armenians"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title_in_russia" class="">{{ trans('product.title_in_russia') }}</label>
							<input type="text" id="title_in_russia" placeholder="{{ trans('product.placeholder.enter_title') }}" value="{{$coupons->translate('ru')->title}}" class="form-control">
							<div class="validation-div" id="val-title_in_russia"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="code" class="">{{ trans('coupons.code') }}</label>
							<input type="text" id="code" placeholder="{{ trans('coupons.placeholder.enter_code') }}" class="form-control" value="{{$coupons->code}}">
							<div class="validation-div" id="val-code"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="discount" class="">{{ trans('coupons.discount') }}</label>
							<div class="input-group">
                                <input class="form-control" placeholder="{{trans('coupons.placeholder.enter_discount')}}" name="discount" type="text" id="discount" value="{{$coupons->discount}}">
                                <select name="discount_type" class="selectpicker" id="discount_type">
                                    <option value="amount" @if($coupons->discount_type == 'amount') selected @endif>$</option>
                                    <option value="percentage" @if($coupons->discount_type == 'percentage') selected @endif>%</option>
                                </select>
                            </div>
							<div class="validation-div" id="val-discount"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="start_date" class="">{{ trans('coupons.start_date') }}</label>
							<input type="text" id="start_date" placeholder="{{ trans('coupons.placeholder.enter_start_date') }}" class="form-control" value="{{date('m/d/Y',strtotime($coupons->start_date))}}" autocomplete="off" data-toggle="datepicker">
							<div class="validation-div" id="val-start_date"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="end_date" class="">{{ trans('coupons.end_date') }}</label>
							<input type="text" id="end_date" placeholder="{{ trans('coupons.placeholder.enter_end_date') }}" class="form-control" value="{{date('m/d/Y',strtotime($coupons->end_date))}}" autocomplete="off" data-toggle="datepicker">
							<div class="validation-div" id="val-end_date"></div>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
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
	/*var start = new Date();
    var end = new Date(new Date().setYear(start.getFullYear()+1));
    $('#start_date').datepicker({
    	autoclose: true,
        startDate : start,
        endDate   : end,
        format : 'yyyy-mm-dd',
        icons:{
            time: 'glyphicon glyphicon-time',
            date: 'glyphicon glyphicon-calendar',
            previous: 'glyphicon glyphicon-chevron-left',
            next: 'glyphicon glyphicon-chevron-right',
            today: 'glyphicon glyphicon-screenshot',
            up: 'glyphicon glyphicon-chevron-up',
            down: 'glyphicon glyphicon-chevron-down',
            clear: 'glyphicon glyphicon-trash',
            close: 'glyphicon glyphicon-remove'
        },
        language : '{{config("app.locale")}}'
    }).on('changeDate', function(){
        $('#end_date').datepicker('setStartDate', new Date($(this).val()));
    }); 

    $('#end_date').datepicker({
    	autoclose: true,
        startDate : start,
        endDate   : end,
        format : 'yyyy-mm-dd',
        icons:{
            time: 'glyphicon glyphicon-time',
            date: 'glyphicon glyphicon-calendar',
            previous: 'glyphicon glyphicon-chevron-left',
            next: 'glyphicon glyphicon-chevron-right',
            today: 'glyphicon glyphicon-screenshot',
            up: 'glyphicon glyphicon-chevron-up',
            down: 'glyphicon glyphicon-chevron-down',
            clear: 'glyphicon glyphicon-trash',
            close: 'glyphicon glyphicon-remove'
        },
        language : '{{config("app.locale")}}'
    }).on('changeDate', function(){
        $('#start_date').datepicker('setEndDate', new Date($(this).val()));
    });*/
  // CREATE
  function saveCoupon(){
	var data = new FormData();
	data.append('item_id', '{{$coupons->id}}');
	data.append('title_in_english', $('#title_in_english').val());
	data.append('title_in_armenians', $('#title_in_armenians').val());
	data.append('title_in_russia', $('#title_in_russia').val());
	data.append('code', $('#code').val());
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