@extends('layouts.theme.master')

@section('content')
	<main class="bg_gray">
		<div class="container margin_60_40">
		    <div class="row justify-content-center">
		        <div class="col-lg-4">
		        	<div class="box_order_form">
		                <div class="head text-center">
		                    <!--<h3>Pizzeria da Alfredo</h3>-->
							<a href="{{url('my-account')}}">Track Order</a>
		                </div>
		                <!-- /head -->
		                <div class="main">
		                	<div id="confirm">
								<div class="icon icon--order-success svg add_bottom_15">
									<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
										<g fill="none" stroke="#8EC343" stroke-width="2">
											<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
											<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
										</g>
									</svg>
								</div>
								<h3>Order Confirmed!</h3>
								<p><a href="{{url('my-account')}}">Track Now</a></p>
							</div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</main>
@endsection
