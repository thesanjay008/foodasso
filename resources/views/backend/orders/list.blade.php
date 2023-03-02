@extends('layouts.backend.master')

@section('filter')
	<!-- FILTERS -->
	<div class="modal fade" id="filterBox" tabindex="-1" role="dialog" aria-labelledby="filterBoxTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="filterTitle">{{trans('common.filters')}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Page</span>
								</div>
								<input id="page" value="1" placeholder="Ex: 0 or 100" step="1" type="number" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Count</span>
								</div>
								<input id="count" value="20" placeholder="Ex: 0 or 100" step="1" type="number" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<br>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Search</span>
								</div>
								<input id="search" placeholder="Search with order ID or customer name" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<br>
							<h5 class="card-title">Status</h5>
							<div class="position-relative form-group">
								<div>
									<div class="custom-radio custom-control">
										<input type="radio" id="new" value="new" name="statusRadio" class="custom-control-input">
										<label class="custom-control-label" for="new">New</label>
									</div>
									<div class="custom-radio custom-control">
										<input type="radio" id="accepted" value="accepted" name="statusRadio" class="custom-control-input">
										<label class="custom-control-label" for="accepted">Accepted</label>
									</div>
									<div class="custom-radio custom-control">
										<input type="radio" id="all" value="all" name="statusRadio" class="custom-control-input" checked>
										<label class="custom-control-label" for="all">All</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" onclick="getData();" data-dismiss="modal">Apply</button>
				</div>
			</div>
		</div>
	</div>
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
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('order.title')}}</div>
			<div class="btn-actions-pane-right text-capitalize">
				<button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" data-toggle="modal" data-target="#filterBox">{{trans('common.filters')}}</button>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="mb-0 table table-striped">
					<thead>
						<tr>
						 <th>#</th>
						 <th>{{ trans('order.order_number') }}</th>
						 <th>{{ trans('order.order_date') }}</th>
						 <th>{{ trans('order.contact_person') }}</th>
						 <th>{{ trans('order.total') }}</th>
						 <th>{{ trans('order.payment_mode') }}</th>
						 <th>{{ trans('order.status') }}</th>
						 <th>{{ trans('common.action') }}</th>
						</tr>
					</thead>
					<tbody id="data-list"></tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection
@section('js')
<script>

	$(document).ready(function(e) {
		getData();
		
		$(document).on('change','.status',function(){
			var data = new FormData();
			data.append('status', $(this).val());
			data.append('id', $(this).attr('id'));
			var response = adminAjax('{{route("orders_status")}}', data);
			if(response.status == '200'){
				if(response.data.status == 'success'){
					swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
					setTimeout(function(){ location.reload(); }, 2000)
				}
			}
		});
		
	});
	
	// GET LIST
	function getData(){
		var data = new FormData();
		data.append('page', $('#filterBox #page').val());
		data.append('count', $('#filterBox #count').val());
		data.append('search', $('#filterBox #search').val());
		data.append('status', $('input[name="statusRadio"]:checked').val());
		var response = adminAjax('{{route("orderList")}}', data);
		if(response.status == '200'){
			$('#data-list').html('');
			if(response.data.length > 0){
				var htmlData = '';
				$.each(response.data, function( index, value ) {
					var row = index + 1;
					htmlData+= '<tr>'+
									'<th scope="row">'+ row +'</th>'+
									'<th scope="row">'+ value.custom_order_id +'</th>'+
									'<td>'+ value.order_date +'</td>'+
									'<td>'+ value.contact_person +'</td>'+
									'<td>'+ value.grand_total +'</td>'+
									'<td>'+ value.payment_mode +'</td>'+
									'<td>'+ value.status +'</td>'+
									'<td>'+ value.action +'</td>'+
								'</tr>';
				})
				$('#data-list').html(htmlData);
			}
		}
	}
</script>
@endsection