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
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
				<li class="nav-item"><a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0" aria-selected="false"><span>New</span></a></li>
				<li class="nav-item"><a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2" aria-selected="false"><span>Preparing</span></a></li>
				<li class="nav-item"><a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3" aria-selected="true"><span>Dispatched</span></a></li>
				<li class="nav-item"><a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4" aria-selected="true"><span>Out For Delivery</span></a></li>
			</ul>
			
			<div class="tab-content">
				<!-- New List -->
				<div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
					<div class="row">
						@if(count($new) > 0)
						@foreach($new as $order)
						<div class="col-md-12 col-lg-3 col-xl-3">
							<div class="card-shadow-primary card-border mb-3 card">
								<div class="dropdown-menu-header">
									<div class="dropdown-menu-header-inner bg-danger">
										<div class="menu-header-content">
											<!--<div class="avatar-icon-wrapper avatar-icon-lg">
												<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset('default/default-user.jpg')}}" alt="Avatar 5"></div>
											</div>-->
											<div>
												<h5 class="menu-header-title">{{ $order->contact_person }}</h5>
												<h6 class="menu-header-subtitle">{{ $order->address }}</h6>
											</div>
											<div class="menu-header-btn-pane"><a class="mr-2 btn btn-dark btn-sm" href="{{ route('orders.show',[$order->id]) }}">View Order</a></div>
										</div>
									</div>
								</div>
								<div class="scroll-area-sm">
									<div class="scrollbar-container ps ps--active-y">
										<ul class="list-group list-group-flush">
											@if(count($order->order_items) > 0)
											@foreach($order->order_items as $item)
											<li class="list-group-item">
												<div class="widget-content p-0">
													<div class="widget-content-wrapper">
														<div class="widget-content-left"><div class="widget-heading">{{ $item->quantity }} X {{ $item->title }}</div></div>
													</div>
												</div>
											</li>
											@endforeach
											@endif
										</ul>
									</div>
								</div>
								<div class="text-center d-block card-footer">
									<button class="btn-shadow-primary btn btn-success btn-lg" onclick="takeAction({{ $order->id }}, 'Preparing')">Accept</button>
									<button class="mr-2 text-danger btn btn-link btn-sm"  onclick="takeAction({{ $order->id }}, 'Rejected')">Reject</button>
								</div>
							</div>
						</div>
						@endforeach
						@endif
					</div>
				</div>
				
				<!-- Preparing List -->
				<div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
					<div class="row">
						@if(count($preparing) > 0)
						@foreach($preparing as $order)
						<div class="col-md-12 col-lg-3 col-xl-3">
							<div class="card-shadow-primary card-border mb-3 card">
								<div class="dropdown-menu-header">
									<div class="dropdown-menu-header-inner bg-warning">
										<div class="menu-header-content">
											<!--<div class="avatar-icon-wrapper avatar-icon-lg">
												<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset('default/default-user.jpg')}}" alt="Avatar 5"></div>
											</div>-->
											<div>
												<h5 class="menu-header-title">{{ $order->contact_person }}</h5>
												<h6 class="menu-header-subtitle">{{ $order->address }}</h6>
											</div>
											<div class="menu-header-btn-pane"><a class="mr-2 btn btn-dark btn-sm" href="{{ route('orders.show',[$order->id]) }}">View Order</a></div>
										</div>
									</div>
								</div>
								<div class="scroll-area-sm">
									<div class="scrollbar-container ps ps--active-y">
										<ul class="list-group list-group-flush">
											@if(count($order->order_items) > 0)
											@foreach($order->order_items as $item)
											<li class="list-group-item">
												<div class="widget-content p-0">
													<div class="widget-content-wrapper">
														<div class="widget-content-left"><div class="widget-heading">{{ $item->quantity }} X {{ $item->title }}</div></div>
													</div>
												</div>
											</li>
											@endforeach
											@endif
										</ul>
									</div>
								</div>
								<div class="text-center d-block card-footer">
									<button class="btn-shadow-primary btn btn-success btn-lg" onclick="takeAction({{ $order->id }}, 'Dispatched')">Ready For Delivery</button>
									<button class="mr-2 text-danger btn btn-link btn-sm"  onclick="takeAction({{ $order->id }}, 'Rejected')">Reject</button>
								</div>
							</div>
						</div>
						@endforeach
						@endif
					</div>
				</div>
				
				<!-- Dispatched List -->
				<div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
					<div class="row">
						@if(count($dispatched) > 0)
						@foreach($dispatched as $order)
						<div class="col-md-12 col-lg-3 col-xl-3">
							<div class="card-shadow-primary card-border mb-3 card">
								<div class="dropdown-menu-header">
									<div class="dropdown-menu-header-inner bg-info">
										<div class="menu-header-content">
											<!--<div class="avatar-icon-wrapper avatar-icon-lg">
												<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset('default/default-user.jpg')}}" alt="Avatar 5"></div>
											</div>-->
											<div>
												<h5 class="menu-header-title">{{ $order->contact_person }}</h5>
												<h6 class="menu-header-subtitle">{{ $order->address }}</h6>
											</div>
											<div class="menu-header-btn-pane"><a class="mr-2 btn btn-dark btn-sm" href="{{ route('orders.show',[$order->id]) }}">View Order</a></div>
										</div>
									</div>
								</div>
								<div class="scroll-area-sm">
									<div class="scrollbar-container ps ps--active-y">
										<ul class="list-group list-group-flush">
											@if(count($order->order_items) > 0)
											@foreach($order->order_items as $item)
											<li class="list-group-item">
												<div class="widget-content p-0">
													<div class="widget-content-wrapper">
														<div class="widget-content-left"><div class="widget-heading">{{ $item->quantity }} X {{ $item->title }}</div></div>
													</div>
												</div>
											</li>
											@endforeach
											@endif
										</ul>
									</div>
								</div>
								<div class="text-center d-block card-footer">
									<button class="btn-shadow-primary btn btn-success btn-lg" onclick="takeAction({{ $order->id }}, 'Out-For-Delivery')">Out For Delivery</button>
								</div>
							</div>
						</div>
						@endforeach
						@endif
					</div>
				</div>
				
				<!-- Dispatched List -->
				<div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">
					<div class="row">
						@if(count($outForDelivery) > 0)
						@foreach($outForDelivery as $order)
						<div class="col-md-12 col-lg-3 col-xl-3">
							<div class="card-shadow-primary card-border mb-3 card">
								<div class="dropdown-menu-header">
									<div class="dropdown-menu-header-inner bg-info">
										<div class="menu-header-content">
											<!--<div class="avatar-icon-wrapper avatar-icon-lg">
												<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset('default/default-user.jpg')}}" alt="Avatar 5"></div>
											</div>-->
											<div>
												<h5 class="menu-header-title">{{ $order->contact_person }}</h5>
												<h6 class="menu-header-subtitle">{{ $order->address }}</h6>
											</div>
											<div class="menu-header-btn-pane"><a class="mr-2 btn btn-dark btn-sm" href="{{ route('orders.show',[$order->id]) }}">View Order</a></div>
										</div>
									</div>
								</div>
								<div class="scroll-area-sm">
									<div class="scrollbar-container ps ps--active-y">
										<ul class="list-group list-group-flush">
											@if(count($order->order_items) > 0)
											@foreach($order->order_items as $item)
											<li class="list-group-item">
												<div class="widget-content p-0">
													<div class="widget-content-wrapper">
														<div class="widget-content-left"><div class="widget-heading">{{ $item->quantity }} X {{ $item->title }}</div></div>
													</div>
												</div>
											</li>
											@endforeach
											@endif
										</ul>
									</div>
								</div>
								<div class="text-center d-block card-footer">
									<button class="btn-shadow-primary btn btn-success btn-lg" onclick="takeAction({{ $order->id }}, 'Delivered')">Delivery</button>
								</div>
							</div>
						</div>
						@endforeach
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script>
	// Change Orders Status
	function takeAction(order_id = 0, status=''){
		var data = new FormData();
		data.append('status', status);
		data.append('id', order_id);
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
		}
	}
</script>
@endsection