@extends('layouts.backend.master')

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
			<h5 class="card-title">Order ID #{{$order->custom_order_id}}</h5>
			<div class="form-row">
				<div class="col-md-12">
					<div class="list_general order">
						<ul>
							<li>
								<figure>
								@if(isset($order->user->profile_image))
								<img src="@if($order->user->profile_image) {{ asset($order->user->profile_image) }} @else {{ asset(config('constants.DEFAULT_USER_IMAGE')) }} @endif" alt="{{$order->user->name}}" height="63px"></figure>
								@else
								<img src="{{ asset(config('constants.DEFAULT_USER_IMAGE')) }}" alt="{{$order->contact_person}}" height="63px"></figure>
								@endif
								<h4>{{$order->contact_person}} <button class="mb-2 mr-2 btn-pill btn btn-secondary active">{{$order->status}}</button></h4>
								<ul class="booking_list">
									<li><strong>Date and time</strong> {{$order->created_at}}</li>
									@if($order->table_id)<li><strong>Table No: </strong>1</li>@endif
									<li><strong>Order Type</strong> {{$order->order_type}}</li>
									<li><strong>Payment Mode</strong> {{$order->payment_mode}}</li>
									<li><strong>Contact Person</strong> {{$order->contact_person}}</li>
									<li><strong>Contact Number</strong> <a href="callto:{{$order->contact_number}}">{{$order->contact_number}}</a></li>
									<li><strong>Address</strong> {{$order->address}}</li>
								</ul>
								<ul class="buttons">
									@if($order->status == 'New')
									<li><button class="btn-wide mb-2 mr-2 btn-icon btn-pill btn btn-success" onclick="takeAction('Preparing')">Accept</button></li>
									<li><button class="btn-wide mb-2 mr-2 btn-icon btn-pill btn btn-danger" onclick="takeAction('Rejected')">Reject</button></li>
									@endif
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<hr>
		<div class="card-body">
			<h5 class="card-title">{{ trans('order.order_items') }}</h5>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Item</th>
							<th>Options</th>
							<th>Quantity</th>
							<th>Price</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>#</th>
							<th>Item</th>
							<th>Options</th>
							<th>Quantity</th>
							<th>Price</th>
						</tr>
					</tfoot>
					<tbody>
						@foreach($order->order_items as $key => $item)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$item->product->title}}</td>
							<td></td>
							<td>{{$item->quantity}}</td>
							<td>{{$item->price}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="row justify-content-end total_order">
				<div class="col-xl-3 col-lg-4 col-md-5">
					<ul>
						<li><span>Subtotal</span> {{$order->total}}</li>
						<li><span>Discount</span> {{$order->discount}}</li>
						<li><span>Delivery Fee</span> 0.00</li>
						<li><span>Grand Total</span> {{$order->grand_total}}</li>
					</ul>
					@if($order->status == 'New') <button class="mb-2 mr-2 btn-pill btn btn-gradient-success btn-block">Accept</button> @endif
				</div>
			</div>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script>

	$(document).ready(function(e) {
		
	});
	
	// Change Orders Status
	function takeAction(status='Preparing'){
		var data = new FormData();
		data.append('status', status);
		data.append('id', '{{$order->id}}');
		var response = adminAjax('{{route("orders_status")}}', data);
		if(response.status == '200'){
			if(response.data.status == 'success'){
				swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
				setTimeout(function(){ location.reload(); }, 2000)
			}
			else
			{
				swal.fire({title: response.message,type: 'error'});
			}
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection