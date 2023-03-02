<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content=""/>
    <meta name="msapplication-tap-highlight" content="no">
	<link rel="stylesheet" href="{{asset('authAssets/main.d810cf0ae7f39f28f336.css')}}">
	<link rel="stylesheet" href="{{asset('authAssets/custom.css')}}" />
	<script>var token = '{{ csrf_token() }}'; </script>
	<style>.slick-slider .slide-img-bg{opacity: 0.9;}</style>
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 no-gutters row">
                    <div class="d-none d-lg-block col-lg-4">
                        <div class="slider-light">
                            <div class="slick-slider">
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center" tabindex="-1">
                                        <div class="slide-img-bg" style="background-image: url('{{asset('themeAssets/images/auth/login-slide-1.jpg')}}');"></div>
                                        <!--<div class="slider-content">
                                            <h3>Slide One</h3>
                                            <p>Lorem ipsom dolor sit amet... Lorem ipsom dolor sit amet... Lorem ipsom dolor sit amet...</p>
                                        </div>-->
                                    </div>
                                </div>
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center" tabindex="-2">
                                        <div class="slide-img-bg" style="background-image: url('{{asset('themeAssets/images/auth/login-slide-2.jpg')}}');"></div>
                                        <!--<div class="slider-content">
                                            <h3>Slide Two</h3>
                                            <p>Lorem ipsom dolor sit amet... Lorem ipsom dolor sit amet... Lorem ipsom dolor sit amet...</p>
                                        </div>-->
                                    </div>
                                </div>
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center" tabindex="-3">
                                        <div class="slide-img-bg" style="background-image: url('{{asset('themeAssets/images/auth/login-slide-3.jpg')}}');"></div>
                                        <!--<div class="slider-content">
                                            <h3>Slide Three</h3>
                                            <p>Lorem ipsom dolor sit amet... Lorem ipsom dolor sit amet... Lorem ipsom dolor sit amet...</p>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <div class="app-logo"></div>
                            <!--<h4 class="mb-0">
                                <span class="d-block">Welcome back,</span>
                                <span>Please sign in to your account.</span>
                            </h4>-->
                            <h6 class="mt-3">No account? <a href="{{ url('register') }}" class="text-theme text-primary">Sign up now</a></h6>
                            <div class="divider row"></div>
                            <div>
                                <form method="POST" action="javascript:void(0);" onsubmit="loginUser()">
									@csrf
                                    <div class="form-row ai-signin">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="username" class="">Username</label>
                                                <input id="username" placeholder="Mobile No. or Email Address" type="text" class="form-control" required>
												<div class="validation-div" id="val-username"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="examplePassword" class="">Password</label>
                                                <input name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-relative form-check">
                                        <input name="check" id="exampleCheck" type="checkbox" class="form-check-input">
                                        <label for="exampleCheck" class="form-check-label">Keep me logged in</label>
                                    </div>
                                    <div class="divider row"></div>
                                    <div class="d-flex align-items-center">
                                        <div class="ml-auto">
                                            <a href="javascript:void(0);" class="text-theme btn-lg btn btn-link">Recover Password</a>
                                            <button class="btn-theme btn btn-primary btn-lg">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script src="{{asset('authAssets/main.d810cf0ae7f39f28f336.js') }}"></script>
	<script src="{{asset('authAssets/jquery.min.js')}}"></script>
	<!-- Sweetalert -->
	<script src="{{ asset('/authAssets/sweetalert/sweetalert2.js') }}"></script>
	<script>
		var hmrl 	= '{{route("firstPage")}}';
		var lgnurl  = '{{route("loginUser")}}';
	</script>
    <script src="{{asset('authAssets/custom.js')}}"></script>
</body>
</html>