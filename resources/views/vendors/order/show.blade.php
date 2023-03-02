@extends('layouts.backend.master')
@section('css')
<style type="text/css">
	p.details {
		background-color: #e9ecef;
	    opacity: 1;
	}
</style>
@endsection
@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('order.heading') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a>{{ trans('order.plural') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('order.details') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<h5 class="card-title">{{ trans('order.details') }}</h5>
			<form class="">
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.order_number') }}</label>
							<p class="form-control details">{{$order->order_id}}</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.order_date') }}</label>
							<p class="form-control details">{{$order->order_date}}</p>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.customer_name') }}</label>
							<p class="form-control details">{{$order->user->name}}</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.customer_email') }}</label>
							<p class="form-control details">{{$order->user->email}}</p>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-12">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.address') }}</label>
							<p class="form-control details">{{$order->Address->address}}</p>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.coupon') }}</label>
							<p class="form-control details">@if($order->coupon) {{$order->coupon->code}} @endif</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.item_count') }}</label>
							<p class="form-control details">{{$order->item_count}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.quantity') }}</label>
							<p class="form-control details">{{$order->quantity}}</p>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.total') }}</label>
							<p class="form-control details">{{$order->total}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.tax') }}</label>
							<p class="form-control details">{{$order->tax}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.discount') }}</label>
							<p class="form-control details">{{$order->discount}}</p>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.grand_total') }}</label>
							<p class="form-control details">{{$order->grand_total}}</p>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.payment_mode') }}</label>
							<p class="form-control details">
								@if($order->payment_method_id == '1')
									{{trans('order.cod')}}
								@else
									{{trans('order.online')}}
								@endif
							</p>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.payment_status') }}</label>
							<p class="form-control details">{{$order->payment_status}}</p>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="exampleEmail11" class="">{{ trans('order.order_status') }}</label>
							<p class="form-control details">{{$order->status}}</p>
						</div>
					</div>
				</div>
				<div class="main-card mb-3 card">
					<div class="card-body">
						<h5 class="card-title">{{ trans('order.order_items') }}</h5>
						@foreach($order->order_items as $key => $item)
							<div class="main-card mb-3 card">
								<div class="card-body">
									<h5 class="card-title">{{ trans('order.item') }} #{{$key+1}}</h5>
									<form class="">
										<div class="form-row">
											<div class="col-md-4">
												<div class="position-relative form-group">
													<label for="exampleEmail11" class="">{{ trans('order.product_name') }}</label>
													<p class="form-control details">{{$item->product->title}}</p>
												</div>
											</div>
											<div class="col-md-4">
												<div class="position-relative form-group">
													<label for="exampleEmail11" class="">{{ trans('order.price') }}</label>
													<p class="form-control details">{{$item->product->price}}</p>
												</div>
											</div>
											<div class="col-md-4">
												<div class="position-relative form-group">
													<label for="exampleEmail11" class="">{{ trans('order.quantity') }}</label>
													<p class="form-control details">{{$item->quantity}}</p>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection