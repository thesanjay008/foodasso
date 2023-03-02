<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content=""/>
    <meta name="msapplication-tap-highlight" content="no">
	<link rel="stylesheet" href="{{asset('authAssets/main.d810cf0ae7f39f28f336.css')}}">
	<link rel="stylesheet" href="{{asset('authAssets/custom.css')}}" />
	<script>var token = '{{ csrf_token() }}'; </script>
	<style>#showRestaurant{display:none;}</style>
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 no-gutters row">
                    <div class="h-100 d-md-flex d-sm-block bg-white justify-content-center align-items-center col-md-12 col-lg-7">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <div class="app-logo"></div>
                            <h4>
                                <div>Welcome,</div>
                                <span>It only takes a <span class="text-success">few seconds</span> to create your account</span>
                            </h4>
                            <div>
                              @if ($errors->any())
                                <div class="alert alert-danger">
                                  <b>{{trans('common.whoops')}}</b>
                                  <ul>
                                    @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                    @endforeach
                                  </ul>
                                </div>
                              @endif
                                <form class="ai-signup" action="javascript:void(0);" method="POST" onsubmit="registerUsers();">
                                  @csrf
                                    <div class="form-row">
                                        <div class="col-md-8">
                                            <div class="position-relative form-group">
                                                <label for="name" class=""><span class="text-danger">*</span> Name</label>
                                                <input name="name" id="name" placeholder="Name here..." type="text" class="form-control">
												<div class="validation-div val-name"></div>
                                            </div>
                                        </div>
                                    </div>
									<div class="form-row">	
										<div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="email" class=""><span class="text-danger">*</span> Email</label>
                                                <input name="email" id="email" placeholder="Email here..." type="email" class="form-control">
												<div class="validation-div val-email"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="mobile_number" class="">Mobile Number</label>
                                                <input name="mobile_number" id="mobile_number" placeholder="Phone Number here..." type="text" class="form-control">
												<div class="validation-div val-mobile_number"></div>
                                            </div>
                                        </div>
										
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="password" class=""><span class="text-danger">*</span> Password</label>
                                                <input name="password" id="password" placeholder="Password here..." type="password" class="form-control">
												<div class="validation-div val-password"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="password_confirmation" class=""><span class="text-danger">*</span> Repeat Password</label>
                                                <input name="password_confirmation" id="password_confirmation" placeholder="Repeat Password here..." type="password" class="form-control">
												<div class="validation-div val-password_confirmation"></div>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="mt-3 position-relative form-check">
                                        <input name="check" id="exampleCheck" type="checkbox" class="form-check-input">
                                        <label for="exampleCheck" class="form-check-label">Accept our <a class="text-theme" href="{{ url('terms') }}">Terms and Conditions</a>.</label>
                                    </div>
                                    <div class="mt-4 d-flex align-items-center">
                                        <h5 class="mb-0">Already have an account? <a href="{{ url('login') }}" class="text-theme text-primary">Sign in</a></h5>
                                        <div class="ml-auto">
                                            <button class="btn-theme btn-wide btn-pill btn-shadow btn-hover-shine btn btn-primary btn-lg">Create Account </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="d-lg-flex d-xs-none col-lg-5">
                        <div class="slider-light">
                            <div class="slick-slider slick-initialized">
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-premium-dark"
                                        tabindex="-1">
                                        <div class="slide-img-bg"
                                            style="background-image: url('{{asset('themeAssets/images/auth/registration.jpg')}}');"></div>
                                        <div class="slider-content">
                                            <h3>Scalable, Modular, Consistent</h3>
                                            <p>Register for lightning fast delivery to your doorstep</p>
                                        </div>
                                    </div>
                                </div>
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
	<script src="{{ asset('authAssets/sweetalert/sweetalert2.js') }}"></script>
	<script>
		var hmrl 	= '{{route("firstPage")}}';
		var lgnurl  = '{{route("loginUser")}}';
		var regurl  = '{{route("registerUser")}}';
		$(document).ready(function(e) {
			$('#is_restaurant').click(function() {
			  if ($(this).is(':checked')) {
				$('#showRestaurant').show();
			  }else{
				$('#showRestaurant').hide();
			  }
			});
		});
	</script>
	<!-- custom js -->
    <script src="{{asset('authAssets/custom.js')}}"></script>
</body>
</html>