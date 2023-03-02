@extends('layouts.theme.master')

@section('content')
	<main>
		<div class="page_header blog element_to_stick">
		    <div class="container">
		    	<div class="row">
		    		<div class="col-xl-8 col-lg-7 col-md-7 d-none d-md-block">
		    			<h1>My Account</h1>
		    		</div>
		    		<div class="col-xl-4 col-lg-5 col-md-5">
		    			<div class="search_bar_list">
						    <input type="text" class="form-control" placeholder="Category, Item Name...">
						    <button type="submit"><i class="icon_search"></i></button>
						</div>
		    		</div>
		    	</div>	       
		    </div>
		</div>
		<!-- /page_header -->
		
		<div class="bg_gray">
			<div class="container margin_30_40">
				<div class="row">
					<div class="col-lg-4 col-md-4">
						<div class="box_topic">
							<figure class="user-img">
								<img src="{{asset('/themeAssets/img/default-user.jpg')}}" alt="{{ Auth::user()->name }}">
							</figure>
							<h3>{{ Auth::user()->name }}</h3>
							<ul class="account-nav">
								<li><a href="{{ route('myAccount')}}">Dashboard</a></li>
								<li><a href="{{ route('myOrders')}}">Past Orders</a></li>
								<li><a href="{{ route('profileSettings')}}">Settings</a></li>
							</ul>
						</div>
					</div>
					<div class="box_orders col-lg-8 col-md-8">
						
						@include('theme.myAccount.'.$page)

					</div>
				</div>
			</div>
		</div>
	</main>
    <!-- /main -->
@endsection