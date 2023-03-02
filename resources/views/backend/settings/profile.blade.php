@extends('layouts.backend.master')
@section('content')
	<div class="app-page-title app-page-title-simple">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div>
					<div class="page-title-head center-elem">
						<span class="d-inline-block pr-2"><i class="lnr-apartment opacity-6"></i></span>
						<span class="d-inline-block">Profile</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="active breadcrumb-item"> <a>Profile Management</a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	
	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">{{trans('settings.information')}}</div>
			<div class="btn-actions-pane-right text-capitalize">
				<!--<a class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm" href="">{{trans('order.add')}}</a>-->
			</div>
		</div>
		<div class="card-body">
			<form id="save-general-settings" action="javascript:void(0);" onsubmit="saveSettingsusername()">
				
				<div class="position-relative row form-group">
					<label for="username" class="col-sm-3 col-form-label">Store Name</label>
					<div class="col-sm-6">
						<input type="text" id="username" name="username" class="form-control" value="{{$user->name}}"/>
						<div class="validation-div" id="val-username"></div>
					</div>
				</div>
				<div class="position-relative row form-group">
					<label for="email" class="col-sm-3 col-form-label">Email</label>
					<div class="col-sm-6">
						<input type="text" id="email" name="email" class="form-control" value="{{$user->email}}" disabled>
						<div class="validation-div" id="val-email"></div>
					</div>
				</div>
				<div class="position-relative row form-group">
					<label for="phone_number" class="col-sm-3 col-form-label">Phone Number</label>
					<div class="col-sm-6">
						<input type="text" id="phone_number" class="form-control" value="{{$user->mobile_number}}" disabled>
						<div class="validation-div" id="val-phone_number"></div>
					</div>
				</div>
				<div class="position-relative row form-group">
					<label for="email" class="col-sm-3 col-form-label"></label>
					<div class="col-md-6">
						Address:
						@if(isset($userInfo->address))
						<input id="searchTextField" value="{{$userInfo->address}}"  class="form-control"  type="text" size="50" style="text-align: left;direction: ltr;">
						@else
						<input id="searchTextField" value=""  class="form-control"  type="text" size="50" style="text-align: left;direction: ltr;">
						@endif
						<div class="validation-div" id="val-address"></div>
						<br>
						<div class="row">
							<div class="col-md-6">
								Latitude:
								@if(isset($userInfo->latitude) && !empty($userInfo->latitude))
								<input name="latitude" id="latitude" class="MapLat form-control" value="{{$userInfo->latitude}}" type="text" placeholder="Latitude"  disabled>
								@else
								<input name="latitude" id="latitude" class="MapLat form-control" value="" type="text" placeholder="Latitude"  disabled>
								@endif
								<div class="validation-div" id="val-latitude"></div>
							</div>
							<div class="col-md-6">
								Longitude:
								@if(isset($userInfo->longitude))
								<input name="longitude" id="longitude" class="MapLon form-control" value="{{$userInfo->longitude}}" type="text" placeholder="Longitude"  disabled>
								@else
								<input name="longitude" id="longitude" class="MapLon form-control" value="" type="text" placeholder="Longitude"  disabled>
								@endif
								<div class="validation-div" id="val-longitude"></div>
							</div>
						</div>
						<br/>
						<div id="map_canvas" style="height:350px;width:100%;"></div>
					</div>
				</div>
				
				<button type="submit" class="mt-2 btn btn-primary">{{ trans('common.submit') }}</button>
			</form>
		</div>
	</div>
	
	<div class="main-card mb-3 card">
		<div class="card-header-tab card-header">
			<div class="card-header-title font-size-lg text-capitalize font-weight-normal">Logo</div>
		</div>
		<div class="card-body">
			<form id="save-qr-code" action="javascript:void(0);" onsubmit="saveLogo()">
				<div class="box-header with-border">
					<div class="col-sm-10">
						<img id="image-src" style="max-height: 180px;" src="@if($user->profile_image){{ asset($user->profile_image) }} @endif"/>
						<input name="file" id="image" type="file" class="form-control-file">
						<small class="form-text text-muted">Max size 300 KB</small>
					</div>
				</div>
				<br>
				<br>
				<br>
				<button type="submit" class="mt-2 btn btn-primary">Save Logo</button>
			</form>
		</div>
	</div>
	<!-- CONTENT OVER -->
@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key={{Settings::get('google_map_api_key')}}&libraries=places"></script>

<script>
	google.maps.event.addDomListener(window, 'load', function () {
            var inputAddressField = document.getElementById("txtFullAddress");
            var places = new google.maps.places.Autocomplete(inputAddressField);
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();
                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();
                var mesg = "Address: " + address;
                mesg += "\nLatitude: " + latitude;
                mesg += "\nLongitude: " + longitude;
            });
        });
	$(function () {			
		var lat = 23.076099;
		var lng = 72.508408;
		var latlng = new google.maps.LatLng(lat, lng);
		var image = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
		<?php if(isset($userInfo) && !empty($userInfo)) { ?>
		
			var lat = '{{$userInfo->latitude}}';
			var lng = '{{$userInfo->longitude}}';
		
		<?php } ?>
		//zoomControl: true,
		//zoomControlOptions: google.maps.ZoomControlStyle.LARGE,

		var mapOptions = {
             center: new google.maps.LatLng(lat, lng),
             zoom: 13,
             mapTypeId: google.maps.MapTypeId.ROADMAP,
             panControl: true,
             panControlOptions: {
                 position: google.maps.ControlPosition.TOP_RIGHT
             },
             zoomControl: true,
             zoomControlOptions: {
                 style: google.maps.ZoomControlStyle.LARGE,
                 position: google.maps.ControlPosition.TOP_left
             }
		},
		map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions),
			marker = new google.maps.Marker({
				position: latlng,map: map,icon: image
			});

		var input = document.getElementById('searchTextField');
		var autocomplete = new google.maps.places.Autocomplete(input, {types: ["geocode"]});

		autocomplete.bindTo('bounds', map);
		var infowindow = new google.maps.InfoWindow();

		google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
			infowindow.close();
			var place = autocomplete.getPlace();
			if (place.geometry.viewport) {
                 map.fitBounds(place.geometry.viewport);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);
			}

			 moveMarker(place.name, place.geometry.location);
			 $('.MapLat').val(place.geometry.location.lat());
			 $('.MapLon').val(place.geometry.location.lng());
         });
		
		google.maps.event.addListener(map, 'click', function (event) {
			$('.MapLat').val(event.latLng.lat());
			$('.MapLon').val(event.latLng.lng());
			alert(event.latLng.place.name)
		});
		$("#searchTextField").focusin(function () {
			$(document).keypress(function (e) {
				if (e.which == 13) {
                     return false;
                     infowindow.close();
                     var firstResult = $(".pac-container .pac-item:first").text();
                     var geocoder = new google.maps.Geocoder();
                     geocoder.geocode({
                         "address": firstResult
                     }, function (results, status) {
                         if (status == google.maps.GeocoderStatus.OK) {
                             var lat = results[0].geometry.location.lat(),
                                 lng = results[0].geometry.location.lng(),
                                 placeName = results[0].address_components[0].long_name,
                                 latlng = new google.maps.LatLng(lat, lng);

                             moveMarker(placeName, latlng);
                             $("input").val(firstResult);
                             alert(firstResult)
                         }
                     });
                 }
             });
         });

         function moveMarker(placeName, latlng) {
             marker.setIcon(image);
             marker.setPosition(latlng);
             infowindow.setContent(placeName);
             //infowindow.open(map, marker);
         }
	});
</script>

<script>
	$(document).ready(function(e) {
		$("#image").change(function() {
			readURL(this);
		});
	});
	// Image View
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				jQuery('#image-src').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	function saveData(){
		var data = new FormData();
		data.append('name', $('#name').val());
		data.append('email', $('#email').val());
		data.append('phone_number', $('#phone_number').val());
		data.append('address', $('#address').val());
		data.append('latitude', $('#latitude').val());
		data.append('longitude', $('#longitude').val());
		
		var response = adminAjax('{{route("ajax.profile.update")}}', data);
		if(response.status == '200' && response.success == '1'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.reload(); }, 2000)
			
		}else if(response.status == '422'){
			$('.validation-div').text('');
			$.each(response.error, function( index, value ) {
				$('#val-'+index).text(value);
			});
			
		} else if(response.status == '201'){
			swal.fire({title: response.message,type: 'success'});
		}
  	}
	
	function saveLogo(){
		var data = new FormData();
		data.append('image', $('#image')[0].files[0]);
		var response = adminAjax('{{route("ajax.update.profile.image")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
		} else{
			swal.fire({title: response.message,type: 'error'});
		}
	}
</script>
@endsection