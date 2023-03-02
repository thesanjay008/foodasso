						<h4><i class="icon-clock_2"></i> Settings</h4>
						<div class="row">
							<div class="col-md-12">
								<br>
								<form method="post" action="javascript:void(0);" id="updateProfile" onsubmit="updateProfile();">
									<h6>Personal data</h6>
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="Full Name" id="name" value="{{ Auth::user()->name }}">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<input type="email" class="form-control" placeholder="Email Address" id="email" value="{{ Auth::user()->email }}">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="Phone Nmber" id="phone_number" value="{{ Auth::user()->phone_number }}">
											</div>
										</div>
									</div>
									<div class="form-group"><input type="submit" class="btn_1 gradient" value="Update"></div>
								</form>
								
								<br>
								<br>
								<form method="post" action="" id="updateAddress">
									<h6>Address Data</h6>
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="Home No / Office No" id="address_line1">
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="Address Line 2" id="address_line2">
											</div>
										</div>
									</div>
									<!-- /row -->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="Landmark" id="landmark">
											</div>
										</div>
									</div>
									<!-- /row -->
									<div class="row add_bottom_15">
										<div class="col-md-3">
											<div class="form-group">
												<div class="custom_select submit">
													<select name="country_register" id="country_register" class="form-control wide" style="display: none;">
														<option value="">Country</option>
														<option value="Europe">Europe</option>
														<option value="Asia">Asia</option>
														<option value="Unated States">Unated States</option>
														<option value="Oceania">Oceania</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<div class="custom_select submit">
													<select name="country_register" id="country_register" class="form-control wide" style="display: none;">
														<option value="">State</option>
														<option value="Europe">Europe</option>
														<option value="Asia">Asia</option>
														<option value="Unated States">Unated States</option>
														<option value="Oceania">Oceania</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<div class="custom_select submit">
													<select name="country_register" id="country_register" class="form-control wide" style="display: none;">
														<option value="">City</option>
														<option value="Europe">Europe</option>
														<option value="Asia">Asia</option>
														<option value="Unated States">Unated States</option>
														<option value="Oceania">Oceania</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="Zip Code" id="zip_code">
											</div>
										</div>
									</div>
									<div class="form-group"><input type="submit" class="btn_1 gradient" value="Update Address" id="submit-register"></div>
								</form>
							</div>
						</div>