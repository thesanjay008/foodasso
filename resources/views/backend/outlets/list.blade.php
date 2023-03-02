@extends('layouts.backend.master')

@section('content')
				<div class="app-page-title app-page-title-simple">
					<div class="page-title-wrapper">
						<div class="page-title-heading">
							<div>
								<div class="page-title-head center-elem">
									<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
									<span class="d-inline-block">{{ trans('outlets.heading') }}</span>
								</div>
							</div>
						</div>
						<div class="page-title-actions">
							<div class="page-title-subheading opacity-10">
								<nav class="" aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
										<li class="breadcrumb-item"> <a>{{ trans('outlets.plural') }}</a></li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
				</div>
				<!-- CONTENT START -->
				<div class="main-card mb-3 card">
					<div class="card-header-tab card-header">
						<div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('outlets.outlet_list')}}</div>
						<div class="btn-actions-pane-right text-capitalize">
							<a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" href="{{ route('outlets.create') }}" > {{trans('common.add_new')}} </a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="mb-0 table table-striped">
								<thead>
									<tr>
									 <th>#</th>
									 <th>{{ trans('outlets.title') }}</th>
									 <th>{{ trans('outlets.email') }}</th>
									 <th>{{ trans('outlets.registered_on') }}</th>
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

		$(document).on('change','.change_status',function(){
			var data = new FormData();
			data.append('status', $(this).val());
			data.append('id', $(this).attr('id'));
			var response = adminAjax('{{route("ajax_change_outletStatus")}}', data);
			if(response.status == '200'){
				if(response.data.status == 'success'){
					swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
					setTimeout(function(){ location.reload(); }, 2000)
				}
				else
				{

				}
			}
		});
		
	});
	// GET LIST
	function getData(){
		var data = new FormData();
		var response = adminAjax('{{route("ajax_outlet_list")}}', data);
		if(response.status == '200'){
			if(response.data.length > 0){
				var htmlData = '';
				$.each(response.data, function( index, value ) {
					htmlData+= '<tr>'+
							'<th scope="row">'+ value.id +'</th>'+
							'<td>'+ value.title +'</td>'+
							'<td>'+ value.email +'</td>'+
							'<td>'+ value.created_at +'</td>'+
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