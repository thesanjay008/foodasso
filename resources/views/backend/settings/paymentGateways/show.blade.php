@extends('layouts.backend.master')
@section('css')
	<!--<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
 	<link rel='stylesheet prefetch' href='https://foliotek.github.io/Croppie/croppie.css'>-->
	<style type="text/css">
	p.details {
		background-color: #e9ecef;
	    opacity: 1;
	}
	</style>
@endsection
@section('popup')
	<!-- CROP MODAL -->
	<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  	<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body">
		            <div id="upload-demo" class="center-block"></div>
		            <input type="hidden" id="image_type">
		      	</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
				</div>
			</div>
		</div>
	</div>
	<!-- CROP MODAL OVER -->
@endsection
@section('content')
		<div class="app-page-title app-page-title-simple">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div>
						<div class="page-title-head center-elem">
							<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
							<span class="d-inline-block">{{ trans('restaurants.show') }}</span>
						</div>
					</div>
				</div>
				<div class="page-title-actions">
					<div class="page-title-subheading opacity-10">
						<nav class="" aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
								<li class="breadcrumb-item"> <a>{{ trans('restaurants.plural') }}</a></li>
								<li class="active breadcrumb-item" aria-current="page">{{ trans('restaurants.show') }}</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
		
		<!-- CONTENT START -->
		<div class="main-card mb-3 card">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title_in_english" class="">{{ trans('restaurants.title_in_english') }}</label>
							<p class="form-control details">{{$data->translate('en')->title}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title_in_armenians" class="">{{ trans('restaurants.title_in_armenians') }}</label>
							<p class="form-control details">{{$data->translate('hy')->title}}</p>
						</div>
					</div>

					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title_in_russia" class="">{{ trans('restaurants.title_in_russia') }}</label>
							<p class="form-control details">{{$data->translate('ru')->title}}</p>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="email" class="">{{ trans('restaurants.email') }}</label>
							<p class="form-control details">{{$data->email}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="phone_number" class="">{{ trans('restaurants.phone_number') }}</label>
							<p class="form-control details">{{$data->phone_number}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="flat_discount" class="">{{ trans('restaurants.flat_discount') }}</label>
							<p class="form-control details">{{$data->flat_discount}}</p>
						</div>
					</div>
				</div>
				<div class="form-row">
					@if($data->country_id)
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="country_id" class="">{{trans('restaurants.country')}}</label>
								<p class="form-control details">{{$data->country->country_name}}</p>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="city_id" class="">{{trans('restaurants.city')}}</label>
							<p class="form-control details">{{$data->city->name}}</p>
						</div>
					</div>
					@endif
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="zip_code" class="">{{trans('restaurants.zip')}}</label>
							<p class="form-control details">{{$data->zip_code}}</p>
						</div>
					</div>
				</div>
				<!-- Address -->
				<div class="form-row">
					@foreach(config('app.locales') as $lk=>$lv)
					<div class="col-md-4">
					  <div class="form-group">
						<label for="address:{{$lk}}">{{ trans('restaurants.address_'.$lk) }}</label>
						<p class="form-control details">{{$data->translate($lk)->address}}</p>
					  </div>
					</div>
					@endforeach
				</div>
				
				<div class="form-row">
					<!--LATTITUDE  -->
					<div class="col-md-3">
					  <div class="position-relative form-group">
						{{Form::label('latitude', trans('common.latitude'),['class' => 'content-label'])}}<br>
						<p class="form-control details">{{$data->latitude}}</p>
					  </div>
					</div>
					
					<!-- LONGITUDE -->
					<div class="col-md-3">
					  <div class="position-relative form-group">
						  {{Form::label('longitude', trans('common.longitude'),['class' => 'content-label'])}}<br>
							<p class="form-control details">{{$data->longitude}}</p>
					  </div>
					</div>
					@if($data->category_id)
					<div class="col-md-3">
						<div class="position-relative form-group">
							{{Form::label('categories', trans('restaurants.categories'),['class' => 'content-label'])}}<br>
						<p class="form-control details">{{$data->restaurant_category->category_id}}</p>
						</div>
					</div>
					@endif
				</div>
				
				<div class="form-row mt-3">
					<div class="col-md-12">
						<h5>Restaurant Timing</h5>
						<hr>
					</div>
					<div class="col-md-3">
					  <div class="position-relative form-group">
						Start Time<br>
						<p class="form-control details">{{$data->latitude}}</p>
					  </div>
					</div>
					<!-- LONGITUDE -->
					<div class="col-md-3">
					  <div class="position-relative form-group">
						End Time<br>
						<p class="form-control details">{{$data->longitude}}</p>
					  </div>
					</div>
				</div>
				
				
				
				<br>
				<div class="form-row">
					<div class="col-md-12">
						<h4>Login Details</h4>
						<hr>
					</div>
					<div class="col-md-2">
						<div class="position-relative form-group">
							<label for="" class="">Code</label>
							<p class="form-control details">{{ $data->owner->country_code }}</p>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="" class="details">Mobile Number</label>
							<p class="form-control details">{{ $data->owner->phone_number }}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="" class="details">Email Address</label>
							<p class="form-control details">{{ $data->owner->email }}</p>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="" class="details">Created at</label>
							<p class="form-control details">{{ $data->owner->created_at }}</p>
						</div>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-6">
						@if($data->image)
						<br>
						<div class="form-row">
							<div class="col-md-6">
							  <div class="position-relative form-group">
								<label for="exampleFile" class="">{{ trans('restaurants.image') }}</label>
								<div class="validation-div" id="val-image"></div>
					
								<div class="image-preview"><img id="image-src" src="{{asset('public/'. $data->image)}}" width="100%"/></div>
							  </div>
							</div>
							@if($data->banner_image)
							<div class="col-md-6">
							  <div class="position-relative form-group">
								<label for="exampleFile" class="">{{ trans('restaurants.banner_image') }}</label>
								<div class="validation-div" id="val-banner_image"></div>
								<div class="image-preview"> <img id="banner_image-src" src="{{asset('public/'. $data->banner_image)}}" width="100%"/> </div>
							  </div>
							</div>
							@endif
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		<!-- CONTENT OVER -->
@endsection

