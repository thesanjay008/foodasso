@extends('layouts.backend.master')

@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">Dashboard</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!--<div class="mbg-3 alert alert-info alert-dismissible fade show" role="alert">
		<span class="pr-2"><i class="fa fa-question-circle"></i></span>
		Warning error will show here
	</div>-->
	
	<div class="tabs-animation">
		<div class="mb-3 card">
			<div class="card-header-tab card-header">
				<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
					<i class="header-icon lnr-charts icon-gradient bg-happy-green"></i> Statistics
				</div>
				<!--<div class="btn-actions-pane-right text-capitalize">
					<button class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm">View All</button>
				</div>-->
			</div>
			<div class="no-gutters row">
				<div class="col-sm-6 col-md-4 col-xl-4">
					<div class="card no-shadow rm-border bg-transparent widget-chart text-left">
						<div class="icon-wrapper rounded-circle">
							<div class="icon-wrapper-bg opacity-10 bg-warning"></div>
							<i class="lnr-laptop-phone text-dark opacity-8"></i>
						</div>
						<div class="widget-chart-content">
							<div class="widget-subheading">TOTAL ORDERS</div>
							<div class="widget-numbers">0</div>
						</div>
					</div>
					<div class="divider m-0 d-md-none d-sm-block"></div>
				</div>
				<div class="col-sm-6 col-md-4 col-xl-4">
					<div class="card no-shadow rm-border bg-transparent widget-chart text-left">
						<div class="icon-wrapper rounded-circle">
							<div class="icon-wrapper-bg opacity-9 bg-danger"></div>
							<i class="lnr-graduation-hat text-white"></i>
						</div>
						<div class="widget-chart-content">
							<div class="widget-subheading">OPEN ORDERS</div>
							<div class="widget-numbers"><span>0</span></div>
						</div>
					</div>
					<div class="divider m-0 d-md-none d-sm-block"></div>
				</div>
				<div class="col-sm-12 col-md-4 col-xl-4">
					<div class="card no-shadow rm-border bg-transparent widget-chart text-left">
						<div class="icon-wrapper rounded-circle">
							<div class="icon-wrapper-bg opacity-9 bg-success"></div>
							<i class="lnr-apartment text-white"></i>
						</div>
						<div class="widget-chart-content">
							<div class="widget-subheading">TOTAL REVENUE</div>
							<div class="widget-numbers text-success"><span>0</span></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 col-md-6 col-lg-6">
			<div class="card-shadow-primary card-border mb-3 card">
				<div class="dropdown-menu-header">
					<div class="dropdown-menu-header-inner bg-primary">
						<div class="menu-header-image opacity-4"
							style="background-image: url({{ asset('public/themeAssets/images/dropdown-header/abstract2.jpg') }});"></div>
						<div class="menu-header-content">
							<h5 class="menu-header-title text-capitalize mb-0 fsize-3">Order Status</h5>
							<h6 class="menu-header-subtitle mb-3">Lorem ipsom dolor sit amet...</h6>
						</div>
					</div>
				</div>
				<ul class="list-group list-group-flush">
					<li class="p-0 list-group-item">
						<div class="row">
							<div class="center-elem col-md-6">
								<div class="center-elem w-100">
									<canvas id="doughnut-chart-3"></canvas>
								</div>
							</div>
							<div class="col-md-6">
								<div class="widget-chart">
									<div class="widget-chart-content">
										<div class="widget-numbers mt-0 text-danger">
											<small>Rs.</small>
											158
											<small class="opacity-5 pl-2">
												<i class="fa fa-angle-up"></i>
											</small>
										</div>
										<div class="widget-subheading">Sales Today</div>
									</div>
								</div>
								<div class="divider mt-0 mb-0 mr-2"></div>
								<div class="widget-chart">
									<div class="widget-chart-content">
										<div class="widget-numbers mt-0 text-primary">
											<small>Rs.</small>
											346
											<small class="opacity-5 pl-2">
												<i class="fa fa-angle-down"></i>
											</small>
										</div>
										<div class="widget-subheading">Sales this Month</div>
									</div>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-sm-12 col-md-6 col-lg-6">
			<div class="mb-3 card">
				<div class="card-header">
					<div>
						<h5 class="menu-header-title text-capitalize text-primary">Recent Orders</h5>
					</div>
					<div class="btn-actions-pane-right">
						<div role="group" class="btn-group-sm btn-group">
							<button class="active btn btn-outline-dark">Last Week</button>
							<button class="btn btn-outline-dark">All Month</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="align-middle mb-0 table table-borderless table-striped table-hover">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th>Name</th>
								<th class="text-center">Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center text-muted">#345</td>
								<td>
									<div class="widget-content p-0">
										<div class="widget-content-wrapper">
											<div class="widget-content-left mr-3">
												<div class="widget-content-left">
													<img width="32" class="rounded" src="{{ asset('public/adminAssets/images/avatars/1.jpg') }}" alt="">
												</div>
											</div>
											<div class="widget-content-left flex2">
												<div class="widget-heading">John Doe</div>
												<div class="widget-subheading opacity-7">Web Developer</div>
											</div>
										</div>
									</div>
								</td>
								<td class="text-center">
									<div class="badge badge-pill pl-2 pr-2 badge-warning">Pending</div>
								</td>
								<td class="text-center">
									<button type="button" class="btn-icon btn-icon-only btn btn-light btn-sm">
										<i class="icon ion-eye"></i>
									</button>
								</td>
							</tr>
							loop
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="mb-3 card">
				<div class="card-header-tab card-header">
					<div class="card-header-title font-size-lg text-capitalize font-weight-normal">Traffic Sources</div>
				</div>
				<div class="pt-0 card-body">
					<div id="chart-combined"></div>
				</div>
			</div>
		</div>
	</div>
@endsection