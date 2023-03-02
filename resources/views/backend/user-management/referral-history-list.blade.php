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
								<input id="searchBox" placeholder="Enter Title to Search" type="text" class="form-control">
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
						<span class="d-inline-block">Referral history</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a>Referral history</a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">Referral List</div>
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
						 <th>Title</th>
						 <th width="15%">Refer by</th>
						 <th width="15%">Refer To</th>
						 <th width="15%">Amount</th>
						 <th>Referral Code</th>
						 <th>Created at</th>
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
<script>
	$(document).ready(function() {
		var page = $('#filterBox #page').val();
		getData(page);
		
		$('.next-btn').click(function() {
			page = Number($('#filterBox #page').val()) + 1;
			getData(page);
		});
		
		$('.previous-btn').click(function() {
			pagepre = Number($('#filterBox #page').val()) - 1;
			// if(pagepre == 0){ return false;}
			getData(pagepre);
		});
		$('.filter-btn').click(function() {
			pagefilter = Number($('#filterBox #page').val());
			getData(pagefilter);
		});
	});
	
	// GET LIST
	function getData(page = 1){
		var data = new FormData();
		data.append('page', page);
		data.append('count', $('#filterBox #count').val());
		data.append('search', $('#filterBox #searchBox').val());
		data.append('status', $('input[name="statusRadio"]:checked').val());
		var response = adminAjax('{{route("ajax.refreel-history")}}', data);
		if(response.status == '200'){
			if(response.data.length > 0){
				var htmlData = '';
				$('#filterBox #page').val(page);
				var uni_index = $('#filterBox #count').val() * (page - 1);
				if(page > 1){ $('.previous-btn').show(); } else { $('.previous-btn').hide(); }
				$.each(response.data, function( index, value ) {
					htmlData+= '<tr>'+
									'<th scope="row">'+ value.id +'</th>'+
									'<td>'+ value.title +'</td>'+
									'<td>'+ value.refer_by +'</td>'+
									'<td width="30%">'+ value.refer_to +'</td>'+
									'<td>'+ value.amount +'</td>'+
									'<td>'+ value.referral_code +'</td>'+
									'<td>'+ value.created_at +'</td>'+
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
</script>
@endsection