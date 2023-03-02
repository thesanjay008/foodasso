@extends('layouts.theme.master')

@section('content')
	<main>
		<div class="container margin_detail_2">
	        <div class="row">
	            <div class="col-lg-12">
	                <div class="detail_page_head clearfix">
	                    <div class="title">
	                        <h1>Contact Us</h1>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

		<div class="bg_gray">
		    <div class="container margin_60_40">
		        <div class="row justify-content-center">
		            <div class="col-lg-4">
		                <div class="box_contacts">
		                    <i class="icon_lifesaver"></i>
		                    <h2>Help Center</h2>
		                    <a href="javascript:void(0);">{{ Settings::get('contact_no') }}</a> - <a href="javascript:void(0);">{{ Settings::get('site_email') }}</a>
		                </div>
		            </div>
		            <div class="col-lg-4">
		                <div class="box_contacts">
		                    <i class="icon_pin_alt"></i>
		                    <h2>Address</h2>
		                    <div>{{ Settings::get('address') }}</div>
		                </div>
		            </div>
		            <div class="col-lg-4">
		                <div class="box_contacts">
		                    <i class="icon_cloud-upload_alt"></i>
		                    <h2>Submissions</h2>
		                    <a href="javascript:void(0);">{{ Settings::get('contact_no') }}</a> - <a href="javascript:void(0);">{{ Settings::get('site_email') }}</a>
		                    <small>{{ Settings::get('timing') }}</small>
		                </div>
		            </div>
		        </div>
		        <!-- /row -->
		    </div>
		    <!-- /container -->
		</div>
		<!-- /bg_gray -->

		<div class="container margin_60_20">
		    <h5 class="mb_5">Drop Us a Line</h5>
		    <div class="row">
		        <div class="col-lg-4 col-md-4 add_bottom_25">
		            <div id="message-contact"></div>
			            <form method="post" action="javascript:void(0);" id="contactform" autocomplete="off">
			                <div class="form-group">
			                    <input class="form-control" type="text" placeholder="Name" id="name_contact" name="name_contact">
			                </div>
			                <div class="form-group">
			                    <input class="form-control" type="email" placeholder="Email" id="email_contact" name="email_contact">
			                </div>
							<div class="form-group">
			                    <input class="form-control" type="text" placeholder="Contact Number" id="phone_number" name="phone_number">
			                </div>
			                <div class="form-group">
			                    <textarea class="form-control" style="height: 150px;" placeholder="Message" id="message_contact" name="message_contact"></textarea>
			                </div>
			                <div class="form-group">
			                    <input class="btn_1 gradient full-width" type="submit" value="Submit" id="submit-contact">
			                </div>
			            </form>
			        </div>
		            <div class="col-lg-8 col-md-8 add_bottom_25">
		                <iframe class="map_contact" style="width:100%; min-height:360px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8787.016747005708!2d71.6435043703519!3d22.183650618657285!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3958ddaa9751b9f9%3A0xa7ca643677ada516!2sReliance%20Fresh%20%26%20Mart!5e0!3m2!1sen!2sin!4v1633552992029!5m2!1sen!2sin" allowfullscreen=""></iframe>
		            </div>
		        </div>
		    </div>
	</main>
    <!-- /main -->
@endsection
