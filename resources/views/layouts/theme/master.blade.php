<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<base href="{{env('APP_URL')}}">
    <title>@if(isset($page_title) && $page_title != '' ) {{ $page_title.' - '}} @endif {{ config('constants.APP_NAME') }}</title>
	<meta name="author" content="Foodasso">
    <meta name="keywords" content="" />
    <meta name="description" content=""/>
	
	<!-- Favicons-->
    <link rel="shortcut icon" href="{{ asset('themeAssets/favicon.png')}}" type="image/x-icon">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="{{asset('themeAssets/css/bootstrap_customized.min.css')}}" rel="stylesheet">
    <link href="{{asset('themeAssets/css/style.css')}}" rel="stylesheet">
	
	@if($page == 'home')
    <!-- Home CSS -->
    <link href="{{asset('themeAssets/css/home.css')}}" rel="stylesheet">
	@endif
	
	<!-- SPECIFIC CSS -->
	<link href="{{asset('themeAssets/css/detail-page.css')}}" rel="stylesheet">
	

	<!-- CUSTOM CSS -->
    <link href="{{asset('themeAssets/custom.css')}}" rel="stylesheet">
	
	<!-- Confirm order css-->
	<link href="{{asset('themeAssets/css/order-sign_up.css')}}" rel="stylesheet">
	
	<script>var user_id = ''; @if(Auth::user()) var user_id = '{{ Auth::user()->id }}'; @endif var token = '{{ csrf_token() }}'; var SITE_URL = '{{ url("") }}';</script>
	@include('layouts.theme.partials.custom-css')
	@yield('css')
</head>
<body>
	
        
	@include('layouts.theme.partials.header')
		
	@include('layouts.theme.partials.navigation')
        
	@yield('content')
        
	@include('layouts.theme.partials.footer')

	<div id="toTop"></div><!-- Back to top button -->
    
	<!-- Sign In Modal -->
	<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">
		<div class="modal_header">
			<h3>Sign In</h3>
		</div>
		<form action="javascript:void(0);" id="loginForm" onsubmit="loginProtal();">
			<div class="sign-in-wrapper">
				<!--<a href="#0" class="social_bt facebook">Login with Facebook</a>
				<a href="#0" class="social_bt google">Login with Google</a>
				<div class="divider"><span>Or</span></div>-->
				<div class="form-group">
					<label>Username</label>
					<input type="text" class="form-control" id="username" required placeholder="Enter email or mobile number">
					<i class="icon_mail_alt"></i>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" id="password" required>
					<i class="icon_lock_alt"></i>
				</div>
				<div class="clearfix add_bottom_15">
					<div class="checkboxes float-left">
						<label class="container_check">Remember me
							<input type="checkbox">
							<span class="checkmark"></span>
						</label>
					</div>
					<div class="float-right"><a id="forgot" href="{{ url('forgot-password')}}">Forgot Password?</a></div>
				</div>
				<div class="text-center">
					<input type="submit" value="Log In" class="btn_1 full-width mb_5">
					Don’t have an account? <a href="{{ url('register') }}">Sign up</a>
				</div>
				<div id="forgot_pw">
					<div class="form-group">
						<label>Please confirm login email below</label>
						<input type="email" class="form-control" name="email_forgot" id="email_forgot">
						<i class="icon_mail_alt"></i>
					</div>
					<p>You will receive an email containing a link allowing you to reset your password to a new preferred one.</p>
					<div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
				</div>
			</div>
		</form>
		<!--form -->
	</div>
	<!-- /Sign In Modal -->
	
	<!-- COMMON SCRIPTS -->
	<script src="{{asset('themeAssets/js/common_scripts.min.js')}}"></script>
	<script src="{{asset('themeAssets/js/common_func.js')}}"></script>
	<script src="{{asset('themeAssets/assets/validate.js')}}"></script>
	
	@if($page != 'home')
	<!-- SPECIFIC SCRIPTS -->
	<script src="{{asset('themeAssets/js/sticky_sidebar.min.js')}}"></script>
	<script src="{{asset('themeAssets/js/sticky-kit.min.js')}}"></script>
	<script src="{{asset('themeAssets/js/specific_detail.js')}}"></script>
    @endif
	
	<!-- Sweetalert -->
	<script src="{{asset('themeAssets/sweetalert/sweetalert2.js')}}"></script>

    <!-- custom js -->
    <script src="{{asset('themeAssets/custom.js')}}"></script>
	<script> var checkNewOrderURL = "{{route('checkNewOrder')}}"; var cartListURL = "{{route('cartList')}}"; </script>
	
	@yield('js')
</body>	
</html>