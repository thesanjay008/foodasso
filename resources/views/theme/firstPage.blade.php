@extends('layouts.theme.master')

@section('content')
	<main>
        <div class="hero_single version_1">
            <div class="opacity-mask">
                <div class="container">
                    <div class="row align-items-center justify-content-lg-start">
                        <div class="col-12 col-lg-8 col-xl-6">
                            <h1>Delivery or Takeaway Food</h1>
                            <p>The best restaurants at the best price</p>
                            <form method="get" action="javascript:void(0);">
                                <div class="row no-gutters custom-search-input">
                                    <div class="col-lg-10">
                                        <div class="form-group">
                                            <input class="form-control no_border_r" type="text" name="s" id="autocomplete" placeholder="Category, Item Name...">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <button class="btn_1 gradient" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
						<div class="col-12 col-xl-6">
							<div class="hero-banner-img">
								<img src="{{ asset('themeAssets/img/web-main-img.svg') }}" alt="hero banner" class="img-fluid"/>
							</div>
						</div>
                    </div>
                    <!-- /row -->
                </div>
            </div>
            <div class="wave hero"></div>
        </div>
        <!-- /hero_single -->
		
		@if($top_categories)
        <!--<div class="container margin_30_60">
            <div class="main_title center">
                <span><em></em></span>
                <h2>Popular Categories</h2>
                <p>Cum doctus civibus efficiantur in imperdiet deterruisset</p>
            </div>
            <div class="owl-carousel owl-theme categories_carousel">
                @foreach($top_categories as $clist)
				<div class="item_version_2">
                    <a href="{{ url('menu') }}">
                        <figure>
                            <span>98</span>
                            <img src="{{ asset('themeAssets/img/location_list_1.jpg') }}" data-src="{{ asset('themeAssets/img/location_list_1.jpg') }}" alt="{{ $clist->title }}" class="owl-lazy" width="350" height="450">
                            <div class="info">
                                <h3>{{ $clist->title }}</h3>
                            </div>
                        </figure>
                    </a>
                </div>
				@endforeach
            </div>
        </div>-->
		@endif
        <!-- /container -->

        <div class="bg_gray">
            <div class="container margin_60_40">
                <div class="main_title">
                    <span><em></em></span>
                    <h2>Book a Table</h2>
                </div>
				<div class="row home-book-box">
					<div class="col-lg-2">
						<div class="styled-select currency-selector">
							<select id="guest">
								<option value="1">1 Guest</option>
								<option value="2">2 Guest</option>
								<option value="3">3 Guest</option>
								<option value="4">4 Guest</option>
								<option value="5">5 Guest</option>
								<option value="6">6 Guest</option>
								<option value="7">7 Guest</option>
								<option value="8">8 Guest</option>
								<option value="9">9 Guest</option>
								<option value="10">10 Guest</option>
							</select>
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="dropdown day">
							<a href="#" data-toggle="dropdown">Day <span id="selected_day"></span></a>
							<div class="dropdown-menu">
								<div class="dropdown-menu-content">
									<h4>Select A Day</h4>
									<div class="radio_select chose_day">
										<ul>
											<li>
												<input type="radio" id="Today" name="booking_day" value="Today">
												<label for="Today">Today<em>{{ date('d-m-Y')}}</em></label>
											</li>
											<li>
												<input type="radio" id="Tomorrow" name="booking_day" value="Tomorrow">
												<label for="Tomorrow">Tomorrow<em>{{ date("d-m-Y", strtotime("+1 day")) }}</em></label>
											</li>
										</ul>
									</div>
									<!-- /people_select -->
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="dropdown time">
							<a href="#" data-toggle="dropdown">Time <span id="selected_time"></span></a>
							<div class="dropdown-menu">
								<div class="dropdown-menu-content">
									<h4>Choose an available time slot</h4>
									<div class="radio_select add_bottom_15">
										<ul>
											<?php
												$start_time = strtotime(date('Y-m-d 09:00:00'));
												$end_time = strtotime(date('Y-m-d 22:00:00'));
												$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +45 minutes');
												for ($i=0; $slot <= $end_time; $i++) { 
													echo'<li>
															<input type="radio" id="'. date('H:i', $start_time) .'" name="booking_time" value="'. date('H:i', $start_time) .'">
															<label for="'. date('H:i', $start_time) .'">'. date('H:i', $start_time) .'<em>--</em></label>
														</li>';
													$start_time = $slot;
													$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +45 minutes');
												}
											?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="btn_1_mobile">
						<a href="javascript:void(0);" onclick="createBooking();" class="btn_1 gradient full-width mb_5">Checkout</a>
					</div>
				</div>
				
				<div class="main_title">
                    <span><em></em></span>
                    <h2>Top Menu List</h2>
                    <a href="{{ url('/menu') }}">View All &rarr;</a>
                </div>
				@if($top_list)
                <div class="row add_bottom_25">
                    @foreach($top_list as $tlist)
					<div class="col-lg-6">
                        <div class="list_home">
                            <ul>
                                <li>
                                    <a href="{{ url('menu') }}">
                                        <figure>
                                            <img src="@if($tlist->image) {{ asset($tlist->image) }} @else {{ asset(config('constants.DEFAULT_MENU_IMAGE'))}} @endif" data-src="@if($tlist->image) {{ asset($tlist->image) }} @else {{ asset(config('constants.DEFAULT_MENU_IMAGE'))}} @endif" alt="{{ $tlist->title }}" class="lazy" width="350" height="233">
                                        </figure>
                                        <!--<div class="score"><strong>9.5</strong></div>-->
                                        <!--<em>Italian</em>-->
                                        <h3>{{ $tlist->title }}</h3>
                                        <small>{{ $tlist->description }}</small>
                                        <ul>
                                            <!--<li><span class="ribbon off">-30%</span></li>-->
                                            <li>{{ $tlist->price }}</li>
                                        </ul>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
					@endforeach
                </div>
                @endif
				<!-- /row -->
				
                <div class="banner lazy" data-bg="url({{ asset('themeAssets/img/banner_bg_desktop.jpg') }})">
                    <div class="wrapper d-flex align-items-center opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.3)">
                        <div>
                            <h3>We Deliver to your Place</h3>
                            <p>Enjoy a tasty food in minutes!</p>
                            <a href="{{ url('menu') }}" class="btn_1 gradient">Start Now!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /bg_gray -->

        <div class="shape_element_2">
            <div class="container margin_60_0">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="box_how">
                                    <figure><img src="{{asset('themeAssets/img/easily-order.svg')}}" data-src="{{asset('themeAssets/img/easily-order.svg')}}" alt="" width="150" height="167" class="lazy"></figure>
                                    <h3>Easly Order</h3>
                                    <p>Faucibus ante, in porttitor tellus blandit et. Phasellus tincidunt metus lectus sollicitudin.</p>
                                </div>
                                <div class="box_how">
                                    <figure><img src="{{asset('themeAssets/img/quick-delivery.svg')}}" data-src="{{asset('themeAssets/img/quick-delivery.svg')}}" alt="" width="130" height="145" class="lazy"></figure>
                                    <h3>Quick Delivery</h3>
                                    <p>Maecenas pulvinar, risus in facilisis dignissim, quam nisi hendrerit nulla, id vestibulum.</p>
                                </div>
                            </div>
                            <div class="col-lg-6 align-self-center">
                                <div class="box_how">
                                    <figure><img src="{{asset('themeAssets/img/enjoy-food.svg')}}" data-src="{{asset('themeAssets/img/enjoy-food.svg')}}" alt="" width="150" height="132" class="lazy"></figure>
                                    <h3>Enjoy Food</h3>
                                    <p>Morbi convallis bibendum urna ut viverra. Maecenas quis consequat libero, a feugiat eros.</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-center mt-3 d-block d-lg-none"><a href="{{ url('/register') }}" class="btn_1 medium gradient pulse_bt mt-2">Register Now!</a></p>
                    </div>
                    <div class="col-lg-5 offset-lg-1 align-self-center">
                        <div class="intro_txt">
                            <div class="main_title">
                                <span><em></em></span>
                                <h2>Start Ordering Now</h2>
                            </div>
                            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet libero id nisi euismod, sed porta est consectetur deserunt.</p>
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            <p><a href="{{ url('/menu') }}" class="btn_1 medium gradient pulse_bt mt-2">Order Now</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- /main -->
@endsection

@section('js')
<script>
  
</script>
@endsection
