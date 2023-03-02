@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('booking.heading') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a>{{ trans('booking.plural') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('booking.details') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<h5 class="card-title">Order ID #{{$data->custom_order_id}}</h5>
			<div class="form-row">
				<div class="col-md-12">
					<div class="list_general order">
						<ul>
							<li>
								@if($data->user_id)
									<figure><img src="@if($data->user->profile_image) {{ asset($data->user->profile_image) }} @else {{ asset(config('constants.DEFAULT_USER_IMAGE')) }} @endif" alt="{{$data->user->name}}" height="63px"></figure>
								@else
									<figure><img src="{{ asset(config('constants.DEFAULT_USER_IMAGE')) }}" alt="{{$data->name}}" height="63px"></figure>
								@endif
								<h4>{{$data->name}} <button class="mb-2 mr-2 btn-pill btn btn-secondary active">{{$data->status}}</button></h4>
								<ul class="booking_list">
									<li><strong>Date and time</strong> {{$data->created_at}}</li>
									<li><strong>Client Contacts</strong> <a href="javascript:void(0);">{{$data->phone_number}}</a> - <a href="mailto:{{$data->email}}">{{$data->email}}</a></li>
									<li><strong>Payment Mode</strong> {{$data->payment_method}}</li>
								</ul>
								<ul class="buttons">
									@if($data->status == 'New')
									<li><button class="btn-wide mb-2 mr-2 btn-icon btn-pill btn btn-success" onclick="takeAction('Accepted')">Accept</button></li>
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
			<h5 class="card-title">{{ trans('order.details') }}</h5>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Order ID</th>
							<th>Table ID</th>
							<th>Name</th>
							<th>Guest</th>
							<th>Payment Method</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>1</th>
							<th>{{$data->custom_order_id}}</th>
							<th>{{$data->table_id}}</th>
							<th>{{$data->name}}</th>
							<th>{{$data->quantity}}</th>
							<th>{{$data->payment_method}}</th>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th>#</th>
							<th>Order ID</th>
							<th>Table ID</th>
							<th>Name</th>
							<th>Guest</th>
							<th>Payment Method</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="row justify-content-end total_order">
				<div class="col-xl-3 col-lg-4 col-md-5">
					<ul>
						<li><span>Subtotal</span> {{$data->total}}</li>
						<li><span>Discount</span> {{$data->discount}}</li>
						<li><span>Grand Total</span> {{$data->grand_total}}</li>
					</ul>
					@if($data->status == 'New') <button class="mb-2 mr-2 btn-pill btn btn-gradient-success btn-block">Accept</button> @endif
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
	function takeAction(status='Accepted'){
		var data = new FormData();
		data.append('status', status);
		data.append('id', '{{$data->id}}');
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