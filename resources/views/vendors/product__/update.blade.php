@extends('layouts.backend.master')

@section('css')
 	<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
 	<link rel='stylesheet prefetch' href='https://foliotek.github.io/Croppie/croppie.css'>
 	<style type="text/css">
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
									<span class="d-inline-block">{{ trans('product.update') }}</span>
								</div>
							</div>
						</div>
						<div class="page-title-actions">
							<div class="page-title-subheading opacity-10">
								<nav class="" aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a><i aria-hidden="true" class="fa fa-home"></i></a></li>
										<li class="breadcrumb-item"> <a>{{ trans('product.plural') }}</a></li>
										<li class="active breadcrumb-item" aria-current="page">{{ trans('product.edit') }}</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
				</div>
				
				<!-- CONTENT START -->
				<div class="main-card mb-3 card">
					<div class="card-body">
						<h5 class="card-title">{{trans('product.details')}}</h5>
						<form class="" action="javascript:void(0);" onsubmit="saveProduct()">
							<input name="_method" type="hidden" value="PUT">
							<div class="form-row">
								<div class="col-md-4">
									<div class="position-relative form-group">
										<label for="title" class="">{{ trans('product.title') }}</label>
										<input type="text" id="title" placeholder="{{ trans('product.placeholder.enter_title') }}" value="{{$product->translate('en')->title}}" class="form-control">
										<div class="validation-div" id="val-title"></div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="position-relative form-group">
										<label for="price" class="">{{ trans('product.price') }}</label>
										<input id="price" value="{{$product->price}}" type="text" class="form-control">
										<div class="validation-div" id="val-price"></div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<div class="position-relative form-group">
										<label for="menu_category_id" class="content-label">{{trans('product.category')}}</label>
										<select class="form-control" id="menu_category_id" required>
											<option value=''>{{trans('product.select_category')}} </option>
											@foreach ($categories as $category)
											  <option value='{{$category->id}}' 
												@if($product->menu_category_id ==  $category->id) selected @endif>
												{{$category->title}}
											  </option>
											@endforeach
										</select>
										</div>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-4">
									<div class="position-relative form-group">
										<label for="description" class="">{{ trans('product.description') }}</label>
										<textarea name="description" id="description" rows="4" class="form-control">{{$product->translate('en')->description}}</textarea>
										<div class="validation-div" id="val-description"></div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
					            		<div class="position-relative form-group">
						                  	<label for="choice" class="content-label">{{trans('product.choice')}}</label>
						                  	<select class="form-control" id="choice">
						                  	 	<option value=''>{{trans('product.select_choice')}} </option>
						                  		
												<option value= "veg"
						                        @if($product->choice ==  'veg') selected @endif>{{trans('product.choices.veg')}}</option>
						                        <option value= "nonveg"
						                        @if($product->choice ==  'nonveg') selected @endif>{{trans('product.choices.nonveg')}}</option>
						                        <option value= "egg"
						                        @if($product->choice ==  'egg') selected @endif>{{trans('product.choices.egg')}}</option>
						                        <option value= "vegan"
						                        @if($product->choice ==  'vegan') selected @endif>{{trans('product.choices.vegan')}}</option>

						                 	 </select>
						                  <div class="validation-div" id="val-choice"></div>
						              	</div>
						          	</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
					            		<div class="position-relative form-group">
						                  	<label for="delivery_type" class="content-label">{{trans('product.delivery_type')}}</label>
						                  	<select class="form-control" id="delivery_type">
						                  	 	<option value=''>{{trans('product.select_delivery_type')}} </option>
						                  		
												<option value= "delivery"
						                        @if($product->delivery_type ==  'delivery') selected @endif>{{trans('product.delivery')}}</option>
						                        <option value= "pickup"
						                        @if($product->delivery_type ==  'pickup') selected @endif>{{trans('product.pickup')}}</option>

						                 	 </select>
						                  <div class="validation-div" id="val-delivery_type"></div>
						              	</div>
						          	</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-4">
					            	<div class="form-group">
					            		<div class="position-relative form-group">
						                  	<label for="addon_group_id" class="content-label">{{trans('product.addon_group')}}</label>
						                  	<select class="form-control" id="addon_group_id" multiple>
						                  	 	<option value=''>{{trans('product.select_addon_group')}} </option>
						                  		@foreach ($addonGrps as $addon_group)
												<option value= "{{$addon_group->id}}"
						                        @if(old('addon_group') ==  $addon_group->id) selected @endif>
						                        {{$addon_group->group_name}}
						                      	</option>
						                    	@endforeach
						                 	 </select>
						                  <div class="validation-div" id="val-addon_group_id"></div>
						              	</div>
						          	</div>
					        	</div>
					        	<div class="col-md-4">
					            	<div class="form-group">
					            		<div class="position-relative form-group">
						                  	<label for="variation_id" class="content-label">{{trans('product.variation')}}</label>
						                  	<select class="form-control" id="variation_id" multiple>
						                  	 	<option value=''>{{trans('product.select_variation')}} </option>
						                  		@foreach ($variations as $var)
												<option value= "{{$var->id}}"
						                        @if(old('var') ==  $var->id) selected @endif>
						                        {{$var->variation_name}}
						                      	</option>
						                    	@endforeach
						                 	 </select>
						                  <div class="validation-div" id="val-variation_id"></div>
						              	</div>
						          	</div>
					        	</div>
							</div>
							<div class="form-row">
								<div class="col-md-4">
									<div class="form-group">
					            		<div class="position-relative form-group">
						                  	<label for="is_taxable" class="content-label">{{trans('product.is_taxable')}}</label>
						                  	<select class="form-control" id="is_taxable">
						                  	 	<option value=''>{{trans('product.is_taxable')}} </option>
						                  		
												<option value= "yes"
						                        @if($product->is_taxable ==  'yes') selected @endif>{{trans('product.yes')}}</option>
						                        <option value= "no"
						                        @if($product->is_taxable ==  'no') selected @endif>{{trans('product.no')}}</option>

						                 	 </select>
						                  <div class="validation-div" id="val-is_taxable"></div>
						              	</div>
						          	</div>
								</div>
								<div class="col-md-4">
								  	<div class="form-group">
										<label for="status" class="content-label">{{trans('common.status')}}</label>
										<select class="form-control" name="status" id="status" required>
										  	<option value="active" 
											@if($product->status == 'active') selected @endif>
												{{trans('common.active')}}
										  	</option>
										  	<option value="inactive" 
											@if($product->status == 'inactive') selected @endif>
												{{trans('common.inactive')}}
										  	</option>
										</select>
								  	</div>
								</div>
								<div class="col-md-4">
								  	<div class="position-relative form-group">
										<label for="exampleFile" class="">{{ trans('product.image') }}</label>
										<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
										<div class="validation-div" id="val-image"></div>
										<div class="image-preview"><img id="image-src" src="{{asset('public/'. $product->image)}}" width="100%"/></div>
										<input type="hidden" id="img-blob">
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
  	function saveProduct(){
		var data = new FormData();
		data.append('item_id', '{{$product->id}}');
		data.append('title', $('#title').val());
		data.append('description', $('#description').val());
		data.append('price', $('#price').val());
		//data.append('image', $('#image')[0].files[0]);
		if(img_blob != undefined){
			data.append('image', img_blob);
		}
		data.append('menu_category_id', $('#menu_category_id').val());
		data.append('addon_group_id', $('#addon_group_id').val());
		data.append('variation_id', $('#variation_id').val());
		data.append('delivery_type', $('#delivery_type').val());
		data.append('is_taxable', $('#is_taxable').val());
		data.append('choice', $('#choice').val());
		data.append('status', $('#status').val());

		var response = adminAjax('{{route("saveProduct")}}', data);
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
		$('#cancelCropBtn').data('id', imageId); readFile(this); 
	});
	$('#cropImageBtn').on('click', function (ev) {
		$uploadCrop.croppie('result', {
			type: 'blob',
			format: 'jpeg',
			size: {width: 508, height: 320}
		}).then(function (resp) {
			img_blob = resp;
			$('#image-src').attr('src', URL.createObjectURL(resp, { oneTimeOnly: true }));
			$('#cropImagePop').modal('hide');
		});
	});
	// End image cropping
</script>
@endsection