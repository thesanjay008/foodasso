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
						<div class="col-md-12">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Search</span>
								</div>
								<input id="searchBox" placeholder="Name or Email or Mobile Number" type="text" class="form-control">
							</div>
							<br/>
						</div>
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
								<input id="count" value="10" placeholder="Ex: 0 or 100" step="1" type="number" class="form-control">
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
										<input type="radio" id="active" value="active" name="statusRadio" class="custom-control-input">
										<label class="custom-control-label" for="active">Active</label>
									</div>
									<div class="custom-radio custom-control">
										<input type="radio" id="inactive" value="inactive" name="statusRadio" class="custom-control-input">
										<label class="custom-control-label" for="inactive">Inactive</label>
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
					<button type="button" class="btn btn-secondary filter-btn" data-dismiss="modal">Apply</button>
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
						<span class="d-inline-block">{{ trans('customer.heading') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a>{{ trans('customer.plural') }}</a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">User Subscriber</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<h5>Restaurant Subscriber</h5>
						<table class="mb-0 table table-striped">
							<thead>
								<tr>
								<th>#</th>
								<th>{{ trans('common.restauranttitle') }}</th>
								<th>{{ trans('common.dietname') }}</th>
								<th>{{ trans('common.payment_statuts') }}</th>
								<th>{{ trans('common.created_at') }}</th>
								<th>{{ trans('common.price') }}</th>
								<th>{{ trans('common.status') }}</th>
								</tr>
							</thead>
							<tbody id="subdata"></tbody>
						</table><br>
						<h5>Diet Subscriber</h5>
						<table class="mb-0 table table-striped">
							<thead>
								<tr>
								<th>#</th>
								<th>{{ trans('common.title') }}</th>
								<th>{{ trans('common.diat_plan_type') }}</th>
								<th>{{ trans('common.price') }}</th>
								<th>{{ trans('common.expiry_date') }}</th>
								<th>{{ trans('common.payment_mode') }}</th>
								<th>{{ trans('common.payment_statuts') }}</th>
								</tr>
							</thead>
							<tbody id="subdatadiet"></tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
						<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
					</div>
					</div>
				</div>
			</div>
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('customer.list')}}</div>
			<div class="btn-actions-pane-right text-capitalize">
				<button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" data-toggle="modal" data-target="#filterBox">{{trans('common.filters')}}</button>
				<!-- <a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" href="{{ route('user.management.add',[$role]) }}" > {{trans('common.add_new')}} </a> -->
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="mb-0 table table-striped">
					<thead>
						<tr>
						 <th>#</th>
						 <th>{{ trans('common.image') }}</th>
						 <th>{{ trans('common.customername') }}</th>
						 <th>{{ trans('customer.number') }}</th>
						 <th>{{ trans('customer.created_at') }}</th>
						 <th>{{ trans('common.status') }}</th>
						 <th>{{ trans('common.action') }}</th>
						</tr>
					</thead>
					<tbody id="data-list"></tbody>
				</table>
			</div>
		</div>
		<div class="btn-actions-pane-right mb-3 text-capitalize">
			<button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm previous-btn">{{trans('common.previous')}}</button>
			<button type="button" class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm next-btn">{{trans('common.next')}}</button>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script type="text/javascript" src="{{url('adminAssets/scripts/jquery-ui.min.js')}}" /></script>
<script>
	$(function(){
        $("#myModal").draggable();
    });
	$(document).ready(function() {
		var page = $('#filterBox #page').val();
		getData(page);
		
		$('.next-btn').click(function() {
			page = Number($('#filterBox #page').val()) + 1;
			getData(page);
		});
		
		$('.previous-btn').click(function() {
			page = Number($('#filterBox #page').val()) - 1;
			if(page == 0){ return false;}
			getData(page);
		});
		$('.filter-btn').click(function() {
			page = Number($('#filterBox #page').val());
			getData(page);
		});
		// UPDATE STATUS
		$(document).on('change','.change_status',function(){
			var data = new FormData();
			data.append('status', $(this).val());
			data.append('id', $(this).attr('id'));
			var response = adminAjax('{{route("ajax.user.change.status")}}', data);
			if(response.status == '200'){
				if(response.data.status == 'success'){
					swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
					setTimeout(function(){ location.reload(); }, 2000)
				}
			}
		});
	});
	
	// GET LIST
	function getData(page = 1){
		var data = new FormData();
		data.append('role', '{{$role}}');
		data.append('page', page);
		data.append('count', $('#filterBox #count').val());
		data.append('search', $('#filterBox #searchBox').val());
		data.append('status', $('input[name="statusRadio"]:checked').val());
		var response = adminAjax('{{route("ajax.user.management.list")}}', data);
		if(response.status == '200'){
			$('#data-list').empty();
			if(response.data.length > 0){
				var htmlData = '';
				$('#filterBox #page').val(page);
				var uni_index = $('#filterBox #count').val() * (page - 1);
				if(page > 1){ $('.previous-btn').show(); } else { $('.previous-btn').hide(); }
				$.each(response.data, function( index, value ) {
					htmlData+= '<tr>'+
							'<th scope="row">'+ value.id +'</th>'+
							'<td><img src="'+ value.image +'" height="50px" width="50px"></td>'+
							'<td>'+ value.name +'</td>'+
							'<td>'+ value.mobile_number +'</td>'+
							'<td>'+ value.created_at +'</td>'+
							'<td>'+ value.status +'</td>'+
							'<td>'+ value.action +'</td>'+
						'</tr>';
				})
				$('#data-list').html(htmlData);
			}
			else{
				if(page > 1){ $('.previous-btn').show(); } 
				$('#data-list').html('<tr><td colspan="7" style="text-align:center;">Data Not Found</td></tr>');
			}
		}
	}
	function notdelete()
	{
		alert('Contact developer to perfom this action'); return false;
	}
	// DELETE
	function deleteThis(item_id = ''){

		if(confirm("{{trans('common.delete_confirm')}}")){
			var data = new FormData();
			data.append('item_id', item_id);
			var response = adminAjax('{{route("ajax.user.management.delete")}}', data);
			if(response.status == '200'){
				swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
				setTimeout(function(){ location.reload(); }, 2000)
			}
		}
	} 
	$('.close').on('click',function(){
		$('#myModal').hide();       
	})
	$(function(){
        $("#myModal").draggable();
    });
</script>
@endsection