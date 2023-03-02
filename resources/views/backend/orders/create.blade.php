@extends('layouts.backend.master')

@section('content')
	<!--<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">Create New Order</span>
					</div>
				</div>
			</div>
		</div>
	</div>-->
	
	<style>
		.scroll-area-lg {height: 540px;}
		.table-area-fix {height: 280px;}
		.slt-payment-option .form-group{background: #ecebeb; padding: 12px; border: 1px solid#e6e6e6;}
	</style>
	<div class="row">
		<div class="col-sm-12 col-lg-8">
			<div class="card-hover-shadow-2x mb-3 card">
				<div class="card-header-tab card-header">
					<div class="btn-actions-pane-left text-capitalize actions-icon-btn">
						<div class="btn-group dropdown">
							<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
							<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-left rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">
								<button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-inbox"> </i><span>Category 1</span></button>
								<button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty"> </i><span>Category 2</span></button>
								<button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-book"> </i><span>Category 3</span></button>
								<div tabindex="-1" class="dropdown-divider"></div>
							</div>
						</div>
					</div>
					<div class="card-header-title  font-size-lg text-capitalize font-weight-normal" style="width: 100%;">
						<input placeholder="Search item" type="text" class="form-control-sm form-control">
					</div>
				</div>
				<div class="scroll-area-lg">
					<div class="scrollbar-container">
						<div class="p-2">
							<div id="data-list" class="row">
								<!-- listing -->
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 1</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 2</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 3</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 4</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 5</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 6</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 7</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 8</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-3">
									<div class="card-shadow-primary card-border mb-3 card">
										<div class="dropdown-menu-header">
											<div class="dropdown-menu-header-inner bg-light text-dark">
												<div class="menu-header-content">
													<div class="avatar-icon-wrapper avatar-icon-lg">
														<div class="avatar-icon rounded btn-hover-shine"><img src="{{asset(config('constants.DEFAULT_MENU_IMAGE'))}}" alt="Avatar 5"></div>
													</div>
													<div>
														<h6>Sample item 9</h6>
														<h6 class="menu-header-subtitle">₹ 122.00</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--<div class="card-footer">
					
				</div>-->
			</div>
		</div>
		
		<div class="col-sm-12 col-lg-4">
			<div class="main-card mb-3 card">
				<div class="card-body">
					<!--<h5 class="card-title">Order List</h5>-->
					<table class="mb-0 table table-hover table-area-fix">
						<thead>
							<tr>
								<th>#</th>
								<th>Items</th>
								<th>Qty.</th>
								<th>Price</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row"><i class="text-danger fa fa-close"></i></th>
								<td>Sample item 1</td>
								<td><button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-minus"></i></button> 1 <button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-plus"></i></button></td>
								<td>122.00</td>
							</tr>
							<tr>
								<th scope="row"><i class="text-danger fa fa-close"></i></th>
								<td>Sample item 2</td>
								<td><button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-minus"></i></button> 3 <button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-plus"></i></button></td>
								<td>122.00</td>
							</tr>
							<tr>
								<th scope="row"><i class="text-danger fa fa-close"></i></th>
								<td>Sample item 3</td>
								<td><button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-minus"></i></button> 1 <button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-plus"></i></button></td>
								<td>122.00</td>
							</tr>
							<tr>
								<th scope="row"><i class="text-danger fa fa-close"></i></th>
								<td>Sample item 4</td>
								<td><button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-minus"></i></button> 2 <button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-plus"></i></button></td>
								<td>122.00</td>
							</tr>
							<tr>
								<th scope="row"><i class="text-danger fa fa-close"></i></th>
								<td>Sample item 5</td>
								<td><button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-minus"></i></button> 2 <button class="mr-2 btn-icon btn-icon-only btn btn-sm btn-outline-secondary"><i class="text-secondary fa fa-plus"></i></button></td>
								<td>122.00</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<div class="row">
						<div class="col-sm-6 col-md-7">
							<p>Sub Total</p>
							<p>Discount</p>
							<p>Text</p>
							<p><b>Total</b></p>
						</div>
						<div class="col-sm-6 col-md-5">
							<p class="text-right">366.00</p>
							<p class="text-right">-50.00</p>
							<p class="text-right">15.00</p>
							<p class="text-right"><b>15.00</b></p>
						</div>
					</div>
					
					<div class="slt-payment-option">
						<div class="position-relative form-group">
							<div class="custom-radio custom-control custom-control-inline">
								<input type="radio" name="custom-radio" id="exampleCustomRadio" class="custom-control-input" checked>
								<label class="custom-control-label" for="exampleCustomRadio">Cash</label>
							</div>
							<div class="custom-radio custom-control custom-control-inline">
								<input type="radio" name="custom-radio" id="exampleCustomRadio2" class="custom-control-input">
								<label class="custom-control-label" for="exampleCustomRadio2">Card</label>
							</div>
							<div class="custom-radio custom-control custom-control-inline">
								<input type="radio" name="custom-radio" id="exampleCustomRadio3" class="custom-control-input">
								<label class="custom-control-label" for="exampleCustomRadio3">Other</label>
							</div>
						</div>
					</div>
					
					<div class="d-block text-center">
						<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger">Save</button>
						<button class="btn-wide btn btn-success">Save & Print</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script>
	$(document).ready(function(e) {
		getData();
	});
	
	
	// GET LIST
	function getData(){
		var data = new FormData();
		data.append('page', 1);
		data.append('count', 500);
		data.append('search', '');
		var response = adminAjax('{{route("productList")}}', data);
		if(response.status == '200'){
			$('#data-list').html('');
			if(response.data.length > 0){
				var htmlData = '';
				$.each(response.data, function( index, value ) {
					var row = index + 1;
					htmlData+= '<div class="col-md-3 col-lg-3 col-xl-3">'+
									'<div class="card-shadow-primary card-border mb-3 card">'+
										'<div class="dropdown-menu-header">'+
											'<div class="dropdown-menu-header-inner bg-light text-dark">'+
												'<div class="menu-header-content">'+
													'<div class="avatar-icon-wrapper avatar-icon-lg">'+
														'<div class="avatar-icon rounded btn-hover-shine"><img src="'+ value.image +'" alt="'+ value.title +'"></div>'+
													'</div>'+
													'<div>'+
														'<h6>'+ value.title +'</h6>'+
														'<h6 class="menu-header-subtitle">'+ value.price +'</h6>'+
													'</div>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</div>';
				})
				$('#data-list').html(htmlData);
			}
		}
	}
	
	// Change Orders Status
	function takeAction(order_id = 0, status='Accepted'){
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
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection