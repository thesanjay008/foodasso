@extends('layouts.theme.master')

@section('content')
	<main>
		<div class="container margin_detail_2">
	        <div class="row">
	            <div class="col-lg-12">
	                <div class="detail_page_head clearfix">
	                    <div class="title">
	                        <h1>QR Code</h1>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

		<div class="bg_gray">
		    <div class="container margin_60_40">
		        <div class="row justify-content-center">
		            <div class="col-lg-4">
		                <img src="@if(Settings::get('qr_code')){{ Settings::get('qr_code') }} @endif" class="qr-img">
		            </div>
		        </div>
		    </div>
		</div>
	</main>
    <!-- /main -->
@endsection
