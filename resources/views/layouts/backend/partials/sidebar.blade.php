		<div class="app-sidebar sidebar-shadow">
			<div class="scrollbar-sidebar">
				<div class="app-sidebar__inner">
					<ul class="vertical-nav-menu">
						<li class="app-sidebar__heading">
							<a class="{{ Request::is('backend/dashboard*') ? 'mm-active' : '' }}" href="{{ route('dashboard') }}"><i class="metismenu-icon pe-7s-graph2"></i>{{trans('sidebar.dashboard')}}</a>
						</li>
						
						@if(in_array(Auth::user()->user_type, ['Restaurant']))
						<li class="app-sidebar__heading">
							<a class="{{ Request::is('backend/create-order*') ? 'mm-active' : '' }}" href="{{ route('create.new.order') }}"><i class="metismenu-icon pe-7s-plus"></i>{{trans('sidebar.create_new_order')}}</a>
						</li>
						@endif
						
						<li class="app-sidebar__heading">
							<a href="javascript:void(0);"> <i class="metismenu-icon pe-7s-shopbag"></i> Orders <span class="badge badge-pill badge-danger ml-0 mr-2 nav-order-count">0</span> <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
							<ul>
								<li><a class="{{ Request::is('backend/open-orders*') ? 'mm-active' : '' }}" href="{{ route('open.order.list') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.open_orders')}}</a></li>
								<li><a class="{{ Request::is('backend/orders*') ? 'mm-active' : '' }}" href="{{ route('orders.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.all_orders')}}</a></li>
								<li><a class="{{ Request::is('backend/bookings*') ? 'mm-active' : '' }}" href="{{ route('bookings.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>Reservations</a></li>
							</ul>
						</li>
						
						<li class="app-sidebar__heading">
							<a href="javascript:void(0);"><i class="metismenu-icon pe-7s-menu"></i> MENU MANAGEMENT<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
							<ul>
								<li><a class="{{ Request::is('backend/menu_categories*') ? 'mm-active' : '' }}" href="{{ route('menu_categories.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.menu_category')}}</a></li>
								<li><a class="{{ Request::is('backend/products*') ? 'mm-active' : '' }}" href="{{route('products.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.menu')}}</a></li>
								<!--<li><a class="{{ Request::is('backend/variation_groups*') ? 'mm-active' : '' }}" href="{{ route('variation_groups.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>Variation Groups</a></li>
								<li><a class="{{ Request::is('backend/variations*') ? 'mm-active' : '' }}" href="{{ route('variations.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.variations')}}</a></li>
								<li><a class="{{ Request::is('backend/addon_groups*') ? 'mm-active' : '' }}" href="{{ route('addon_groups.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.addon_groups')}}</a></li>
								<li><a class="{{ Request::is('backend/addons*') ? 'mm-active' : '' }}" href="{{ route('addons.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.addons')}}</a></li>-->
							</ul>
						</li>
						
						<li class="app-sidebar__heading">
							<a class="{{ Request::is('backend/table*') ? 'mm-active' : '' }}" href="{{ route('table.index') }}"> <i class="metismenu-icon pe-7s-safe"></i> Table</a>
						</li>
						
						<!--<li class="app-sidebar__heading">
							<a href="javascript:void(0);"> <i class="metismenu-icon pe-7s-ticket"></i> Discount<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
							<ul>
								<li><a class="{{ Request::is('backend/offers*') ? 'mm-active' : '' }}" href="{{ route('offers.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.offers')}}</a> </li>
								<li><a class="{{ Request::is('backend/coupons*') ? 'mm-active' : '' }}" href="{{ route('coupons.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.coupons')}}</a> </li>
							</ul>
						</li>-->
						
						<li class="app-sidebar__heading">
							<a class="{{ Request::is('backend/manage/Customer*') ? 'mm-active' : '' }}" href="{{ route('user.management',['Customer']) }}"><i class="metismenu-icon pe-7s-user"></i>Customers</a>
						</li>
						
						@if(in_array(Auth::user()->user_type, ['superAdmin']))
						<li class="app-sidebar__heading">
							<a class="{{ Request::is('backend/manage/Restaurant*') ? 'mm-active' : '' }}" href="{{ route('user.management',['Restaurant']) }}"><i class="metismenu-icon pe-7s-wine"></i> Restaurant</a>
						</li>
						@endif
						
						<!--<li class="app-sidebar__heading">
							<a href="javascript:void(0);"> <i class="metismenu-icon pe-7s-note2"></i> Reports<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
							<ul>
								<li><a class="{{ Request::is('backend/reports*') ? 'mm-active' : '' }}" href="{{ route('coupons.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.reports')}}</a> </li>
							</ul>
						</li>-->
						
						<!--<li class="app-sidebar__heading">
							<a class="{{ Request::is('backend/outlets*') ? 'mm-active' : '' }}" href="{{ route('outlets.index') }}"> <i class="metismenu-icon pe-7s-network"></i> Outlets</a>
						</li>-->
						
						@if(in_array(Auth::user()->user_type, ['superAdmin']))
						<li class="app-sidebar__heading">
							<a href="javascript:void(0);"><i class="metismenu-icon pe-7s-map-marker"></i>LOCATION MANAGEMENT<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
							<ul>
								<li><a class="{{ Request::is('backend/countries*') ? 'mm-active' : '' }}" href="{{ route('countries.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.countries')}}</a></li>
								<li><a class="{{ Request::is('backend/states*') ? 'mm-active' : '' }}" href="{{ route('states.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.states')}}</a></li>
								<li><a class="{{ Request::is('backend/cities*') ? 'mm-active' : '' }}" href="{{ route('cities.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.cities')}}</a></li>
								<li><a class="{{ Request::is('backend/areas*') ? 'mm-active' : '' }}" href="{{ route('areas.index') }}"> <i class="metismenu-icon pe-7s-graph"></i>{{trans('sidebar.areas')}}</a></li>
							</ul>
						</li>
						@endif
						
						<li class="app-sidebar__heading">
							<a href="javascript:void(0);"> <i class="metismenu-icon pe-7s-settings"></i> Settings<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
							<ul>
								<li><a class="{{ Request::is('backend/general-settings*') ? 'mm-active' : '' }}" href="{{ route('general-settings') }}"><i class="metismenu-icon"></i>General Settings</a></li>
								<li><a class="{{ Request::is('backend/paymentGateway*') ? 'mm-active' : '' }}" href="{{ route('paymentGateways') }}"><i class="metismenu-icon"></i>Payment Gateway</a></li>
								<!--<li><a class="{{ Request::is('backend/tax-settings*') ? 'mm-active' : '' }}" href="javascript:void(0);"><i class="metismenu-icon"></i>Tax Settings</a></li>
								<li><a class="{{ Request::is('backend/order-timings*') ? 'mm-active' : '' }}" href="javascript:void(0);"><i class="metismenu-icon"></i>Tax Settings</a></li>
								<li><a class="{{ Request::is('backend/delivery*') ? 'mm-active' : '' }}" href="javascript:void(0);"><i class="metismenu-icon"></i>Delivery</a></li>-->
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>