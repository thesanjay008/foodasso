@extends('layouts.backend.master')

@section('content')
				<div class="app-page-title app-page-title-simple">
					<div class="page-title-wrapper">
						<div class="page-title-heading">
							<div>
								<div class="page-title-head center-elem">
									<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
									<span class="d-inline-block">{{ trans('product.add') }}</span>
								</div>
							</div>
						</div>
						<div class="page-title-actions">
							<div class="page-title-subheading opacity-10">
								<nav class="" aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
										<li class="breadcrumb-item"> <a>{{ trans('product.products') }}</a></li>
										<li class="active breadcrumb-item" aria-current="page">{{ trans('product.add') }}</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
				</div>
				
				<!-- CONTENT START -->
				<div class="main-card mb-3 card">
					<div class="card-body">
						<h5 class="card-title">Grid Rows</h5>
						<form class="">
							<div class="form-row">
								<div class="col-md-6">
									<div class="position-relative form-group">
										<label for="exampleEmail11" class="">Email</label>
										<input name="email" id="exampleEmail11" placeholder="with a placeholder" type="email" class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<div class="position-relative form-group">
										<label for="examplePassword11" class="">Password</label>
										<input name="password" id="examplePassword11" placeholder="password placeholder" type="password" class="form-control">
									</div>
								</div>
							</div>
							<div class="position-relative form-group">
								<label for="exampleAddress"class="">Address</label>
								<input name="address" id="exampleAddress" placeholder="1234 Main St" type="text" class="form-control">
							</div>
							<div class="position-relative form-group">
								<label for="exampleAddress2" class="">Address 2</label>
								<input name="address2" id="exampleAddress2" placeholder="Apartment, studio, or floor" type="text" class="form-control">
							</div>
							<div class="form-row">
								<div class="col-md-6">
									<div class="position-relative form-group">
										<label for="exampleCity" class="">City</label>
										<input name="city" id="exampleCity" type="text" class="form-control">
									</div>
								</div>
								<div class="col-md-4">
									<div class="position-relative form-group">
										<label for="exampleState" class="">State</label>
										<input name="state" id="exampleState" type="text" class="form-control">
									</div>
								</div>
								<div class="col-md-2">
									<div class="position-relative form-group">
										<label for="exampleZip" class="">Zip</label>
										<input name="zip" id="exampleZip" type="text" class="form-control">
									</div>
								</div>
							</div>
							<div class="position-relative form-check">
								<input name="check" id="exampleCheck" type="checkbox" class="form-check-input">
								<label for="exampleCheck" class="form-check-label">Check me out</label>
							</div>
							<button class="mt-2 btn btn-primary">Sign in</button>
						</form>
					</div>
				</div>
				<!-- CONTENT OVER -->
@endsection