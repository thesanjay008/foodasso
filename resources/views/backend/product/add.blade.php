@extends('layouts.backend.master')
@section('css')
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
						<span class="d-inline-block">{{ trans('product.add') }}</span>
					</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="page-title-subheading opacity-10">
					<nav class="" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
							<li class="breadcrumb-item"> <a  href="{{ route('products.index') }}">{{ trans('product.singular') }}</a></li>
							<li class="active breadcrumb-item" aria-current="page">{{ trans('product.add') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- CONTENT START -->
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form action="javascript:void(0);" onsubmit="saveProduct()" enctype="multipart/form-data">
				<div class="form-row">
					<div class="col-md-4">
						<div class="position-relative form-group">
							<label for="title" class="">{{ trans('product.title') }}</label>
							<input type="text" id="title" placeholder="{{ trans('product.placeholder.enter_title') }}" class="form-control">
							<div class="validation-div" id="val-title"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="position-relative form-group">
							<label for="price" class="">{{ trans('product.price') }}</label>
							<input id="price" type="text" class="form-control">
							<div class="validation-div" id="val-price"></div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="position-relative form-group">
										<label for="choice" class="content-label">{{trans('product.choice')}}</label>
										<select class="form-control" id="choice">
											<option value=''>{{trans('product.select_choice')}} </option>
											<option value= "veg">{{trans('product.choices.veg')}}</option>
											<option value= "nonveg">{{trans('product.choices.nonveg')}}</option>
											<option value= "egg">{{trans('product.choices.egg')}}</option>
											<option value= "vegan">{{trans('product.choices.vegan')}}</option>
										 </select>
									  <div class="validation-div" id="val-choice"></div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="position-relative form-group">
										<label for="menu_category_id" class="content-label">{{trans('product.category')}}</label>
										<select class="form-control" id="menu_category_id">
											<option value=''>{{trans('product.select_category')}} </option>
											@foreach ($categories as $category)
											<option value= "{{$category->id}}"
											@if(old('category') ==  $category->id) selected @endif>
											{{$category->title}}
											</option>
											@endforeach
										 </select>
									  <div class="validation-div" id="val-menu_category_id"></div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="position-relative form-group">
										<label for="is_taxable" class="content-label">{{trans('product.is_taxable')}}</label>
										<select class="form-control" id="is_taxable">
											<option value=''>{{trans('product.is_taxable')}} </option>
											<option value= "yes">{{trans('product.yes')}}</option>
											<option value= "no" selected>{{trans('product.no')}}</option>
										 </select>
									  <div class="validation-div" id="val-is_taxable"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="description" class="">{{ trans('product.description') }}</label>
									<textarea name="description" id="description" rows="4" class="form-control"></textarea>
									<div class="validation-div" id="val-description"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-row">
							<div class="col-md-12">
								<div class="position-relative form-group">
									<label for="exampleFile" class="">{{ trans('product.image') }}</label>
									<input name="file" id="image" type="file" class="form-control-file item-img" accept="image/*">
									<div class="validation-div" id="val-image"></div>
									<div class="image-preview"><img id="image-src" src="" width="100%"/></div>
									<input type="hidden" id="img-blob">
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<hr>
				<!-- Addons -->
				<div class="form-row">
					<div class="col-md-12">
		            	<h5 class="card-title">{{trans('product.addons')}}</h5>
						<div class="position-relative form-group">
							<div>
								@foreach ($addonGrps as $addon_group)
								<div class="custom-checkbox custom-control">
									<input type="checkbox" id="addons-{{$addon_group->id}}" class="custom-control-input" name="addon-group" value="{{$addon_group->id}}">
									<label class="custom-control-label" for="addons-{{$addon_group->id}}">{{$addon_group->title}}</label>
								</div>
								@endforeach
							</div>
						</div>
						<div class="validation-div" id="val-addons"></div>
		        	</div>
		        </div>
				
				<hr>
				<!-- Variations -->
				<div class="form-row">
		        	<div class="col-md-12">
						<h5 class="card-title">{{trans('product.variations')}}</h5>
						
						@if ($variations)
						  <ul id="ft-id-1" class="ui-fancytree fancytree-container fancytree-plain" tabindex="0" role="tree" aria-multiselectable="true" aria-activedescendant="ui-id-1">
							@foreach ($variations as $var)
							<li role="treeitem" class="pb-2">
								<span class="fancytree-node fancytree-active fancytree-expanded fancytree-folder fancytree-has-children fancytree-exp-e fancytree-ico-ef">
									<span role="button" class="fancytree-expander"></span>
									<input type="checkbox" id="variation-group-{{$var->id}}" class="variation-group custom-control-input" name="variation-group" value="{{$var->id}}">
									<label class="custom-control-label" for="variation-group-{{$var->id}}">{{$var->title}}</label>
								</span>
								@if ($var->list)
								<ul role="group" id="vgl-{{$var->id}}" style="display:none;">
									@foreach ($var->list as $row)
									<li role="treeitem" aria-selected="false">
										<div class="form-row">
											<div class="col-md-2"><p>{{$row->title}} :</p></div>
											<div class="col-md-5">
												<div class="input-group">
													<input placeholder="Enter Price" id="{{$row->id}}" type="number" name="variation" class="form-control">
												</div>
											</div>
											<div class="validation-div" id="val-variations"></div>
										</div>
									</li>
									<br>
									@endforeach
								</ul>
								@endif
							</li>
							@endforeach
						  </ul>
						@endif
		        	</div>
				</div>
				
				<hr>
				<div class="form-row">
	            	<div class="col-md-2">
		                <div class="form-group">
		                    <select  class="form-control" id="status">
		                      <option value="active">{{trans('common.active')}}</option>
		                      <option value="inactive ">{{trans('common.inactive')}}</option>
		                    </select>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
  	$(document).ready(function(e) {
		$(document).on('change','.variation-group',function(){
			if ($(this).is(':checked')) {
				$('#vgl-' + $(this).val()).show();
			}else{
				$('#vgl-' + $(this).val()).hide();
			}
			
		});
	});	
	
	// CREATE
  	var img_blob;
  	function saveProduct(){
		var data = new FormData();
		data.append('title', $('#title').val());
		data.append('description', $('#description').val());
		data.append('price', $('#price').val());
		// data.append('image', $('#image')[0].files[0]);
		if(img_blob != undefined){
			data.append('image', img_blob);
		}
		data.append('menu_category_id', $('#menu_category_id').val());
		data.append('delivery_type', $('#delivery_type').val());
		data.append('is_taxable', $('#is_taxable').val());
		data.append('choice', $('#choice').val());
		data.append('status', $('#status').val());
		
		var addonGrops = [];
		$("input:checkbox[name=addon-group]:checked").each(function(){
			addonGrops.push($(this).val());
		});
		data.append('addon_groups', addonGrops);
		
		var variationGrops = [];
		var variations = [];
		$("input:checkbox[name=variation-group]:checked").each(function(){
			$("#vgl-"+ $(this).val() +" input[name=variation]").each(function(){
				variations.push($(this).attr('id') +'::'+ $(this).val());
			});
			variationGrops.push($(this).val());
		});
		data.append('variations_grops', variationGrops);
		data.append('variations', variations);
		
		var response = adminAjax('{{route("saveProduct")}}', data);
		if(response.status == '200'){
			swal.fire({ type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
			setTimeout(function(){ location.href ='{{route("products.index")}}'; }, 2000)
			
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
			width: 400,
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
			size: {width: 400, height: 320}
		}).then(function (resp) {
			img_blob = resp;
			$('#image-src').attr('src', URL.createObjectURL(resp, { oneTimeOnly: true }));
			$('#cropImagePop').modal('hide');
		});
	});
	// End image cropping
</script>
@endsection