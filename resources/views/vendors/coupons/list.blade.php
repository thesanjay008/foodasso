@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">{{ trans('coupons.heading') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a>{{ trans('coupons.plural') }}</a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('coupons.titles')}}</div>
			<div class="btn-actions-pane-right text-capitalize">
				<a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" href="{{ route('coupons.create') }}" > {{trans('common.add_new')}} </a>
			</div>
		</div>
		<div class="card-body">
			<h5 class="card-title"></h5>
			<div class="table-responsive">
				<table class="mb-0 table table-striped">
					<thead>
						<tr>
						 <th>#</th>
						 <th>{{ trans('coupons.title') }}</th>
						 <th>{{ trans('coupons.code') }}</th>
						 <th>{{ trans('coupons.discount') }}</th>
						 <th>{{ trans('coupons.start_date') }}</th>
						 <th>{{ trans('coupons.end_date') }}</th>
						 <th>{{ trans('common.status') }}</th>
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
		
	});
	// GET LIST
	function getData(){
		var data = new FormData();
		var response = adminAjax('{{route("coupons_list")}}', data);
		if(response.status == '200'){
			if(response.data.length > 0){
				var htmlData = '';
				$.each(response.data, function( index, value ) {
					var discount;
					if(value.discount_type == 'amount')
					{
						discount = '$ '+value.discount;
					}
					else
					{
						discount = value.discount+' %';
					}
					htmlData+= '<tr>'+
									'<th scope="row">'+ value.id +'</th>'+
									'<td>'+ value.title +'</td>'+
									'<td>'+ value.code +'</td>'+
									'<td>'+ discount +'</td>'+
									'<td>'+ value.start_date +'</td>'+
									'<td>'+ value.end_date +'</td>'+
									'<td>'+ value.status +'</td>'+
									'<td>'+ value.action +'</td>'+
								'</tr>';
				})
				$('#data-list').html(htmlData);
			}
		}
	}

	// DELETE
	function deleteThis(item_id = ''){
		var data = new FormData();
		data.append('item_id', item_id);
		var response = adminAjax('{{route("delete_coupons")}}', data);
		if(response.status == '200'){
			// alert('Success!!');
			window.location.href = "{{route('coupons.index')}}";
		}else if(response.status == '201'){
			
		}
	}
</script>
@endsection