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
				
				<div class="mbg-3 alert alert-info alert-dismissible fade show" role="alert">
					<span class="pr-2"><i class="fa fa-question-circle"></i></span>
					Warning error will show here
				</div>
				
				<div class="row">
					<div class="col-md-6 col-lg-3">
						<div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
							<div class="widget-chat-wrapper-outer">
								<div class="widget-chart-content">
									<div class="widget-title opacity-5 text-uppercase">Total Orders</div>
									<div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
										<div class="widget-chart-flex align-items-center">
											<div>
												<small class="opacity-10 text-success pr-2"><i class="fa fa-chart-pie"></i></small>47
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3">
						<div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
							<div class="widget-chat-wrapper-outer">
								<div class="widget-chart-content">
									<div class="widget-title opacity-5 text-uppercase">Open Orders</div>
									<div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
										<div class="widget-chart-flex align-items-center">
											<div>
												<small class="opacity-10 text-danger pr-2"><i class="fa fa-shopping-cart"></i></small>7
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3">
						<div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-success card">
							<div class="widget-chat-wrapper-outer">
								<div class="widget-chart-content">
									<div class="widget-title opacity-5 text-uppercase">Total Menu</div>
									<div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
										<div>
											<small class="opacity-10 text-primary pr-2"><i class="fa fa-list"></i></small>7
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3">
						<div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-warning card">
							<div class="widget-chat-wrapper-outer">
								<div class="widget-chart-content">
									<div class="widget-title opacity-5 text-uppercase">Total Revenue</div>
									<div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
										<div class="widget-chart-flex align-items-center">
											<div><small class="opacity-5 pr-1">$</small>1,45M</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-7 col-lg-8">
						<div class="mb-3 card">
							<div class="card-header-tab card-header">
								<div class="card-header-title font-size-lg text-capitalize font-weight-normal">Traffic Sources</div>
								<div class="btn-actions-pane-right text-capitalize">
									<button class="btn btn-warning">Actions</button>
								</div>
							</div>
							<div class="pt-0 card-body">
								<div id="chart-combined"></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-5 col-lg-4">
						<div class="mb-3 card">
							<div class="card-header-tab card-header">
								<div class="card-header-title font-size-lg text-capitalize font-weight-normal">Income</div>
								<div class="btn-actions-pane-right text-capitalize actions-icon-btn">
									<div class="btn-group">
										<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"> <i class="lnr-cog btn-icon-wrapper"></i>
										</button>
										<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu dropdown-menu-right">
											<h6 tabindex="-1" class="dropdown-header">
												Header
											</h6>
											<button type="button" tabindex="0" class="dropdown-item"> <i class="dropdown-icon lnr-inbox"> </i><span>Menus</span>
											</button>
											<button type="button" tabindex="0" class="dropdown-item"> <i class="dropdown-icon lnr-file-empty"> </i><span>Settings</span>
											</button>
											<button type="button" tabindex="0" class="dropdown-item"> <i class="dropdown-icon lnr-book"> </i><span>Actions</span>
											</button>
											<div tabindex="-1" class="dropdown-divider"></div>
											<div class="p-1 text-right">
												<button class="mr-2 btn-shadow btn-sm btn btn-link">View Details</button>
												<button class="mr-2 btn-shadow btn-sm btn btn-primary">Action</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="p-0 card-body">
								<div id="chart-radial"></div>
								<div class="widget-content pt-0 w-100">
									<div class="widget-content-outer">
										<div class="widget-content-wrapper">
											<div class="widget-content-left pr-2 fsize-1">
												<div class="widget-numbers mt-0 fsize-3 text-warning">32%</div>
											</div>
											<div class="widget-content-right w-100">
												<div class="progress-bar-xs progress">
													<div class="progress-bar bg-warning" role="progressbar" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100" style="width: 32%;"></div>
												</div>
											</div>
										</div>
										<div class="widget-content-left fsize-1">
											<div class="text-muted opacity-6">Spendings Target</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
					
@endsection