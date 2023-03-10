		<div class="app-header header-shadow">
			<div class="app-header__logo">
				<a href="{{ url('/') }}" target="_blank"><img style="max-width: 140px; max-height: 50px;" src="@if(Settings::get('logo')){{ asset(Settings::get('logo')) }} @endif"></a>
				<div class="header__pane ml-auto">
					<div>
						<button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
					</div>
				</div>
			</div>
			<div class="app-header__mobile-menu">
				<div>
					<button type="button" class="hamburger hamburger--elastic mobile-toggle-nav"> <span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
				</div>
			</div>
			<div class="app-header__menu">
				<span>
					<button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
						<span class="btn-icon-wrapper"><i class="fa fa-ellipsis-v fa-w-6"></i></span>
					</button>
				</span>
			</div>
			<div class="app-header__content">
				<div class="app-header-left">
					<!--<div class="search-wrapper">
						<div class="input-holder">
							<input type="text" class="search-input" placeholder="Type to search">
							<button class="search-icon"><span></span></button>
						</div>
						<button class="close"></button>
					</div>-->
					<div class="search-wrapper">
						<select  class="form-control" id="status">
						  <option value="active">All Outlets</option>
						  <!--<option value="1">Iscon (Ahmedabad)</option>
						  <option value="2">Rajnagar (Rajkot)</option>
						  <option value="2">Bopal (Ahmedabad)</option>-->
						</select>
					</div>
				</div>
				<div class="app-header-right">
					<div class="header-btn-lg pr-0">
						<div class="widget-content p-0">
							<div class="widget-content-wrapper">
								<div class="widget-content-left">
									<div class="btn-group">
										<a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
											<img width="42" class="rounded-circle" src="@if(Auth::user()->profile_image) {{ asset(Auth::user()->profile_image) }} @else {{ asset(config('constants.DEFAULT_USER_IMAGE')) }} @endif" alt=""> <i class="fa fa-angle-down ml-2 opacity-8"></i>
										</a>
										<div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
											<div class="dropdown-menu-header">
												<div class="dropdown-menu-header-inner bg-info">
													<div class="menu-header-image opacity-2" style="background-image: url('{{asset('adminAssets/images/dropdown-header/city3.jpg')}}');"></div>
													<div class="menu-header-content text-left">
														<div class="widget-content p-0">
															<div class="widget-content-wrapper">
																<div class="widget-content-left mr-3">
																	<img width="42" class="rounded-circle" src="{{ asset(config('constants.DEFAULT_USER_IMAGE')) }}" alt="">
																</div>
																<div class="widget-content-left">
																	<div class="widget-heading">{{ Auth::user()->name }}</div>
																	<!-- <div class="widget-subheading opacity-8">A short profile description</div> -->
																</div>
																<div class="widget-content-right mr-2">
																	<a class="btn-pill btn-shadow btn-shine btn btn-focus" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
																	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
																		@csrf
																	</form>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="scroll-area-xs" style="height: 180px;">
												<div class="scrollbar-container ps">
													<ul class="nav flex-column">
														<li class="nav-item-header nav-item">Profile</li>
														<li class="nav-item"><a href=" {{route('my-profile') }}" class="nav-link">My Account</a></li>
														<li class="nav-item"><a href="{{route('change_password') }}" class="nav-link">Change Password</a></li>
													</ul>
												</div>
											</div>
											<!--<ul class="nav flex-column">
												<li class="nav-item-divider mb-0 nav-item"></li>
											</ul>
											<div class="grid-menu grid-menu-2col">
												<div class="no-gutters row">
													<div class="col-sm-6">
														<button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning"> <i class="pe-7s-chat icon-gradient bg-amy-crisp btn-icon-wrapper mb-2"></i> Message Inbox</button>
													</div>
													<div class="col-sm-6">
														<button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger"> <i class="pe-7s-ticket icon-gradient bg-love-kiss btn-icon-wrapper mb-2"></i>
															<b>Support Tickets</b>
														</button>
													</div>
												</div>
											</div>-->
										</div>
									</div>
								</div>
								<div class="widget-content-left  ml-3 header-user-info">
									<div class="widget-heading">{{Auth::user()->name}}</div>
									<div class="widget-subheading">{{Auth::user()->user_type}}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>