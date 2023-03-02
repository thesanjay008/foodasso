@extends('layouts.theme.master')

@section('css')
<style>
  .poplr-dish > img{border-radius: inherit;}
  .rih > img{max-width: 50px;}
</style>
@endsection

@section('content')
	<main>
		<div class="bg_gray">
		    <div class="container margin_detail">
		        <div class="row">
		            <div class="col-lg-3">
						
		            </div>
		            <!-- /col -->

		            <div class="col-lg-6" id="sidebar_fixed">
		                <div class="box_order">
		                    <div class="head">
		                        <h3>Book a Table</h3>
		                        <a href="javascript:void(0);" class="close_panel_mobile"><i class="icon_close"></i></a>
		                    </div>
		                    <!-- /head -->
		                    <div class="main">
		                        <div class="custom_select submit">
									<select name="guest" id="guest" class="form-control wide" style="display: none;">
										<option value="1">1 Guest</option>
										<option value="2">2 Guest</option>
										<option value="3">3 Guest</option>
										<option value="4">4 Guest</option>
										<option value="5">5 Guest</option>
										<option value="6">6 Guest</option>
										<option value="7">7 Guest</option>
										<option value="8">8 Guest</option>
										<option value="9">9 Guest</option>
										<option value="10">10 Guest</option>
									</select>
									<div class="nice-select form-control wide" tabindex="0">
										<span class="current">1 Guest</span>
										<ul class="list">
											<li data-value="1" class="option selected focus"><i class="fa fa-facebook"></i>1 Guest</li>
											<li data-value="2" class="option">2 Guest</li>
											<li data-value="3" class="option">3 Guest</li>
											<li data-value="4" class="option">4 Guest</li>
											<li data-value="5" class="option">5 Guest</li>
											<li data-value="6" class="option">6 Guest</li>
											<li data-value="7" class="option">7 Guest</li>
											<li data-value="8" class="option">8 Guest</li>
											<li data-value="9" class="option">9 Guest</li>
											<li data-value="10" class="option">10 Guest</li>
										</ul>
									</div>
								</div>
								
								<br>
								<br>
								<br>
								<div class="dropdown day">
									<a href="#" data-toggle="dropdown">Day <span id="selected_day"></span></a>
									<div class="dropdown-menu">
										<div class="dropdown-menu-content">
											<h4>Select A Day</h4>
											<div class="radio_select chose_day">
												<ul>
													<li>
														<input type="radio" id="Today" name="booking_day" value="Today">
														<label for="Today">Today<em>{{ date('d-m-Y') }}</em></label>
													</li>
													<li>
														<input type="radio" id="Tomorrow" name="booking_day" value="Tomorrow">
														<label for="Tomorrow">Tomorrow<em>{{ date("d-m-Y", strtotime("+1 day")) }}</em></label>
													</li>
												</ul>
											</div>
											<!-- /people_select -->
										</div>
									</div>
								</div>
								
								<br>
								<!-- /dropdown -->
								<div class="dropdown time">
									<a href="#" data-toggle="dropdown">Time <span id="selected_time"></span></a>
									<div class="dropdown-menu">
										<div class="dropdown-menu-content">
											<h4>Choose an available time slot</h4>
											<div class="radio_select add_bottom_15">
												<ul>
													<?php
														$start_time = strtotime(date('Y-m-d 09:00:00'));
														$end_time = strtotime(date('Y-m-d 22:00:00'));
														$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +45 minutes');
														for ($i=0; $slot <= $end_time; $i++) { 
															echo'<li>
																	<input type="radio" id="'. date('H:i', $start_time) .'" name="booking_time" value="'. date('H:i', $start_time) .'">
																	<label for="'. date('H:i', $start_time) .'">'. date('H:i', $start_time) .'<em>--</em></label>
																</li>';
															$start_time = $slot;
															$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +45 minutes');
														}
													?>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<!-- /dropdown -->
								
								<hr>
		                        <div class="btn_1_mobile">
		                            <a href="javascript:void(0);" onclick="createBooking();" class="btn_1 gradient full-width mb_5">Checkout</a>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <!-- /row -->
		    </div>
		    <!-- /container -->
		</div>
	</main>
@endsection

@section('js')
<script>
	$(document).ready(function(e) {
		
	});
</script>
@endsection
