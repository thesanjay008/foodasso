@extends('layouts.backend.master')
@section('css')
	<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
 	<link rel='stylesheet prefetch' href='https://foliotek.github.io/Croppie/croppie.css'>
	<style type="text/css">
	    #map {
	      width: 100%;
	      height: 400px;
	    }
	    .mapControls {
	      margin-top: 10px;
	      border: 1px solid transparent;
	      border-radius: 2px 0 0 2px;
	      box-sizing: border-box;
	      -moz-box-sizing: border-box;
	      height: 32px;
	      outline: none;
	      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
	    }
	    #searchMapInput {
	      background-color: #fff;
	      font-family: Roboto;
	      font-size: 15px;
	      font-weight: 300;
	      margin-left: 12px;
	      padding: 0 11px 0 13px;
	      text-overflow: ellipsis;
	      width: 50%;
	    }
	    #searchMapInput:focus {
	      border-color: #4d90fe;
	    }
	     #departments {
	     border:1px solid #ccc; width:1400px; height: 70px; overflow-y: scroll;
	     }
	     #ins_companies {
	     border:1px solid #ccc; width:1400px; height: 70px; overflow-y: scroll;
	    }
	    #cropImagePop .modal-body {
 			height: 400px;
 		}
 		#cropImagePop .modal-content{
 			width: 600px;
 		}
	</style>
@endsection
@section('popup')
	<!-- CROP MODAL -->
	<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  	<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body">
		            <div id="upload-demo" class="center-block"></div>
		            <input type="hidden" id="image_type">
		      	</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
				</div>
			</div>
		</div>
	</div>
	<!-- CROP MODAL OVER -->
@endsection
@section('content')
		<div class="app-page-title app-page-title-simple">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div>
						<div class="page-title-head center-elem">
							<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
							<span class="d-inline-block">{{ trans('restaurants.create') }}</span>
						</div>
					</div>
				</div>
				<div class="page-title-actions">
					<div class="page-title-subheading opacity-10">
						<nav class="" aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
								<li class="breadcrumb-item"> <a>{{ trans('restaurants.plural') }}</a></li>
								<li class="active breadcrumb-item" aria-current="page">{{ trans('restaurants.add') }}</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>

		<!-- CONTENT START -->
		<div class="main-card mb-3 card">
			<div class="card-body">
				<!-- <h5 class="card-title">Grid Rows</h5> -->
				<form class="" action="javascript:void(0);" onsubmit="saveRestaurants();">
					<input name="_method" type="hidden" value="PUT">
					<div class="form-row">
						@foreach(config('app.locales') as $lk=>$lv)
							<div class="col-md-4">
								<div class="position-relative form-group">
									<label for="title_{{$lk}}" class="">{{ trans('restaurants.title_'.$lk) }}</label>
									<input name="title_{{$lk}}" id="title_{{$lk}}" value="{{old('title_:'.$lk)}}" placeholder="{{ trans('restaurants.title_'.$lk) }}" type="text" class="form-control">
									<div class="validation-div" id="val-title_{{$lk}}"></div>
								</div>
							</div>
						@endforeach
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="email" class="">{{ trans('restaurants.email') }}</label>
								<input name="email" id="email" value="{{old('email')}}"  placeholder="{{ trans('restaurants.email') }}" type="email" class="form-control">
								<div class="validation-div" id="val-email"></div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="phone_number" class="">{{ trans('restaurants.phone_number') }}</label>
								<input name="phone_number" id="phone_number"  value="{{old('phone_number')}}" placeholder="{{ trans('restaurants.phone_number') }}" type="number" class="form-control">
								<div class="validation-div" id="val-phone_number"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="password" class="">{{ trans('restaurants.password') }}</label>
								<input name="password" id="password"  value="{{old('password')}}" placeholder="{{ trans('restaurants.password') }}" type="password" class="form-control">
								<div class="validation-div" id="val-password"></div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="position-relative form-group">
								<label for="flat_discount" class="">{{ trans('restaurants.flat_discount') }}</label>
								<input name="flat_discount" id="flat_discount" value="{{old('flat_discount')}}"placeholder="{{ trans('restaurants.flat_discount') }}" type="number" class="form-control">
								<div class="validation-div" id="val-flat_discount"></div>
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="country_id" class="">{{trans('restaurants.country')}}</label>
								<select id="country_id" class="form-control">
									<option value="">Select</option>
									@if($country)
										@foreach($country as $clist)
											<option value="{{ $clist->id }}" >{{ $clist->country_name}}</option>
										@endforeach
									@endif
								</select>
								<div class="validation-div" id="val-country_id"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="state_id" class="">{{trans('restaurants.state')}}</label>
								<select id="state_id" class="form-control"></select>
								<div class="validation-div" id="val-state_id"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="city_id" class="">{{trans('restaurants.city')}}</label>
								<select id="city_id" class="form-control"></select>
								<div class="validation-div" id="val-city_id"></div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="position-relative form-group">
								<label for="zip_code" class="">{{trans('restaurants.zip')}}</label>
								<input name="zip_code"  value="{{old('zip_code')}}" id="zip_code" type="text" class="form-control">
								<div class="validation-div" id="val-zip_code"></div>
							</div>
						</div>
					</div>
					<!-- Address -->
					<div class="form-row">
             	@foreach(config('app.locales') as $lk=>$lv)
              <div class="col-md-4">
                <div class="form-group">
                  <label for="address:{{$lk}}">{{ trans('restaurants.address_'.$lk) }}</label>
                  <textarea class="form-control"  id="address_{{$lk}}" placeholder="1234 Main St" name="address:{{$lk}}">{{old('address_:'.$lk)}}</textarea>
                  <div class="validation-div" id="val-address_{{$lk}}"></div>
                </div>
              </div>
              @endforeach
						</div>
					<div class="form-row">
						<div class="col-md-6">
						  <div class="form-group">
							{{Form::label('location', trans('restaurants.enter_address'),['class' => 'content-label'])}}
							<input id="searchMapInput" class="mapControls" type="text" placeholder="Enter a location">
							<div id="map"></div>
						  </div> 	
						</div>	
						<div class="col-md-6">
							<div class="form-row">
								<div class="col-md-4">
								  <div class="position-relative form-group">
									{{Form::label('status', trans('common.status'),['class' => 'content-label'])}}<br>
									<select  class="form-control" minlength="2" maxlength="255" id="status" name="status">
									  <option value="active">{{trans('common.active')}}</option>
									  <option value="inactive ">{{trans('common.inactive')}}</option>
									</select>
									@if ($errors->has('status')) 
									<strong class="help-block">{{ $errors->first('status') }}</strong>
									@endif
								  </div>
								</div>
								
								<!--LATTITUDE  -->
								<div class="col-md-4">
								  <div class="position-relative form-group">
									{{Form::label('latitude', trans('common.latitude'),['class' => 'content-label'])}}<br>
									<input type="text" class="form-control"  name="latitude" id="latitude" value="40.069099">
									@error('latitude')
									  <div class="help-block">{{ $message }}</div>
									@enderror
								  </div>
								</div>
								
								<!-- LONGITUDE -->
								<div class="col-md-4">
								  <div class="position-relative form-group">
									  {{Form::label('longitude', trans('common.longitude'),['class' => 'content-label'])}}<br>
									  <input type="text"  class="form-control" name="longitude" id="longitude" value="45.038189">
									@error('longitude')
									  <div class="help-block">{{ $message }}</div>
									@enderror
								  </div>
								</div>
								
							</div>
							<div class="form-row">
								<div class="col-md-12">
									<div class="position-relative form-group">
										{{Form::label('categories', trans('restaurants.categories'),['class' => 'content-label'])}}<br>
										<select multiple="multiple" class="multiselect-dropdown form-control" name="categories[]" id="categories">
											<option value="">{{trans('common.select')}}</option>
											@foreach($categories as $category)
                                            <option value="{{$category->id}}" @if(old($category->id, $category->id) ) selected @endif>{{$category->title}}</option>
                                            @endforeach
	                                    </select>
										<div class="validation-div" id="val-categories"></div>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6">
								  <div class="position-relative form-group">
									<label for="exampleFile" class="">{{ trans('restaurants.image') }}</label>
									<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
									<div class="image-preview"><img id="image-src" src="" width="100%"/></div>
									<div class="validation-div" id="val-image"></div>
									<input type="hidden" id="img-blob">
								  </div>
								</div>
								<div class="col-md-6">
								  <div class="position-relative form-group">
									<label for="exampleFile" class="">{{ trans('restaurants.banner_image') }}</label>
									<input name="file" name="banner_image" id="banner_image" type="file" class="form-control-file item-img" accept="image/*">
									<div class="image-preview"><img id="banner_image-src" src="" width="100%"/></div>
									<div class="validation-div" id="val-banner_image"></div>
									<input type="hidden" id="banner_image_blob">
								  </div>
								</div>
							</div>
						</div>
			        </div>
	  				<button class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
				</form>
			</div>
		</div>
		<!-- CONTENT OVER -->
@endsection

@section('js')
<script src='https://foliotek.github.io/Croppie/croppie.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
<script>
  	// CREATE
  	var img_blob;
  	var banner_image_blob;
  	function saveRestaurants(){
		var data = new FormData();
		data.append('title_en', $('#title_en').val());
		data.append('email', $('#email').val());
		data.append('phone_number', $('#phone_number').val());
		data.append('password', $('#password').val());
		data.append('flat_discount', $('#flat_discount').val());
		data.append('city_id', $('#city_id').val());
		data.append('state_id',$('#state_id').val());
		data.append('country_id',$('#country_id').val());
		data.append('zip_code', $('#zip_code').val());
		data.append('address_en', $('#address_en').val());
		data.append('status', $('#status').val());
		data.append('latitude', $('#latitude').val());
		data.append('longitude', $('#longitude').val());
		data.append('banner_image', banner_image_blob);
		data.append('image', img_blob);
		data.append('categories', $('#categories').val());
		
		
		var response = adminAjax('{{route("saveRestaurants")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.reload(); }, 2000)
			
		}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
			
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'error'});
		}
  	}

  	/*code start for autocomplete map*/
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 22.3038945, lng: 70.80215989999999},
          zoom: 13
        });
        var input = document.getElementById('searchMapInput');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
       
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
      
        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29),
            draggable:true,
            //animation: google.maps.Animation.DROP
        });
      
        autocomplete.addListener('place_changed', function() {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
        
            /* If the place has a geometry, then present it on a map. */
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setIcon(({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
          
            var address = '';
            if (place.address_components) {
                address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }
          
            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
            
            /* Location details */
            $("textarea[name='address']").val(place.formatted_address);
            $('input[name="lattitude"]').val(place.geometry.location.lat())
            $('input[name="longitude"]').val(place.geometry.location.lng())
        });
    }

  	// Start image cropping
	var $uploadCrop,
	tempFilename,
	rawImg,
	imageId;
	function readFile(input) {
		if (input.files && input.files[0]) {
	     	var reader = new FileReader();
	        reader.onload = function (e) {
				$('.upload-demo').addClass('ready');
				$("#image_type").val($(input).attr('id'));
				$('#cropImagePop').modal('show');
	            rawImg = e.target.result;
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	    else {
	        swal("Sorry - you're browser doesn't support the FileReader API");
	    }
	}

	$uploadCrop = $('#upload-demo').croppie({
		viewport: {
			width: 508,
			height: 320,
		},
		enforceBoundary: false,
		enableExif: true
	});
	$('#cropImagePop').on('shown.bs.modal', function(){
		// alert('Shown pop');
		$uploadCrop.croppie('bind', {
			url: rawImg
		}).then(function(){
			console.log('jQuery bind complete');
		});
	});

	$('.item-img').on('change', function () { 
		imageId = $(this).data('id'); 
		tempFilename = $(this).val();
		$('#cancelCropBtn').data('id', imageId); 
		readFile(this); 
	});
	$('#cropImageBtn').on('click', function (ev) {
		$uploadCrop.croppie('result', {
			type: 'blob',
			format: 'jpeg',
			size: {width: 508, height: 320}
		}).then(function (resp) {
			
			var image_type = $("#image_type").val();
			if(image_type == 'image')
			{
				img_blob = resp;
				$('#image-src').attr('src', URL.createObjectURL(resp, { oneTimeOnly: true }));
			}
			else
			{
				banner_image_blob = resp;
				$('#banner_image-src').attr('src', URL.createObjectURL(resp, { oneTimeOnly: true }));
			}
			$('#cropImagePop').modal('hide');
		});
	});
	// End image cropping

	// ON Country Change
	$('#country_id').on('change', function (e) {
		get_state( this.value );
	});
	
	// ON State Change
	$('#state_id').on('change', function (e) {
		get_city( this.value );
	});

		function get_state(country_id = ''){
		var data = new FormData();
		data.append('country_id', country_id);
		var response = adminAjax('{{asset("state/list")}}',data);
		if(response.status == '200'){		
			var htmlData = '<option value="">Select</option>';
			$.each(response.data, function( index, value ) {
				htmlData+= '<option value="'+ value.id +'">'+ value.name +'</option>';
			})
			$('#state_id').html(htmlData);
		}
	}
	
	function get_city(state_id = ''){
		var data = new FormData();
		data.append('state_id', state_id);
		var response = adminAjax('{{asset("city/list")}}', data);
		if(response.status == '200'){
			var htmlData = '<option value="">Select</option>';
			$.each(response.data, function( index, value ) {
				htmlData+= '<option value="'+ value.id +'">'+ value.name +'</option>';
			})
			$('#city_id').html(htmlData);
		}
	}
</script>
@endsection