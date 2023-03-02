	<header class="@if(isset($page) && $page == 'home' ) {{ 'header black_nav clearfix element_to_stick' }} @else {{ 'header_in clearfix' }} @endif ">
        <div class="container">
            <div id="logo">
                <a href="{{ url('/') }}">
                    <img src="@if(Settings::get('logo')){{ asset(Settings::get('logo')) }} @endif" style="max-height:55px" alt="{{ config('constants.APP_NAME') }}">
                </a>
            </div>
            <div class="layer"></div>
			@if(Auth::user())
            <ul id="top_menu" class="drop_user">
                <li>
                    <div class="dropdown user clearfix">
                        <a href="javascript:void(0);" data-toggle="dropdown" aria-expanded="false">
                            <figure><img src="{{ asset('themeAssets/img/default-user.jpg')}}" alt=""></figure><span>{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu" style="">
                            <div class="dropdown-menu-content">
                                <ul>
                                    <li><a href="{{ url('/my-account') }}"><i class="icon_cog"></i>My Account</a></li>
                                    <li><a href="{{ url('/my-account/orders') }}"><i class="icon_document"></i>Orders</a></li>
                                    <!--<li><a href="{{ url('/my-account/wish-list') }}"><i class="icon_heart"></i>Wish List</a></li>-->
                                    <li><a href="{{ url('/checkout') }}"><i class="icon_cart"></i>Checkoout</a></li>
                                    <li><a href="javascript:void(0);" id="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a></li>
									<form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">@csrf</form>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /dropdown -->
                </li>
            </ul>
			@else
			<ul id="top_menu">
                <li><a href="#sign-in-dialog" id="sign-in" class="login">Sign In</a></li>
            </ul>
			@endif
            <!-- /top_menu -->
            <a href="javascript:void(0);" class="open_close">
                <i class="icon_menu"></i><span>Menu</span>
            </a>
           <nav class="main-menu">
                <div id="header_menu">
                    <a href="javascript:void(0);" class="open_close">
                        <i class="icon_close"></i><span>Menu</span>
                    </a>
                    <a href="{{ url('/') }}"><img src="@if(Settings::get('logo')){{ asset(Settings::get('logo')) }} @endif" height="35" alt=""></a>
                </div>
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/about-us') }}">About Us</a></li>
					<li><a href="{{ url('menu') }}" class="order-now-btn ">Order Now</a></li>
					<li><a href="{{ url('booking') }}">Book a Table</a></li>
                    <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
                </ul>
           </nav>
        </div>
    </header>
    <!-- /header -->