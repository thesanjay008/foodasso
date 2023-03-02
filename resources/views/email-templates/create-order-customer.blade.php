<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="{{ str_replace('_', '-', app()->getLocale()) }}" xml:lang="en"><head>
    <title>{{ config('constants.APP_NAME') }}</title>
    <link rel="shortcut icon" href="{{ asset('themeAssets/img/logo.png')}}" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta content="initial-scale=1.0" name="viewport">
    <meta http-equiv="X-UA-Compatible">
    <meta name="robots" content="no index">
    <style>
      a,
      u+#body a {color: inherit;text-decoration: none;font-size: inherit;font-family: inherit;font-weight: inherit;line-height: inherit;}
      .appleLinks a {color: #000000;}
      .appleLinksHEADLINE a {color: #2c2e2f;text-decoration: none;}
      .appleLinksBODY a {color: #6c7378;text-decoration: none;}
      .appleLinksWHITE a {color: #ffffff;text-decoration: none;}
      .appleLinksGRAY2 a {color: #77787b;text-decoration: none;}
      .appleLinksDKGRAY a {color: #595959;text-decoration: none;}
      .appleLinksBLUE a {color: #0073ae;text-decoration: none;}
    </style>
    <style type="text/css">
		div,
		p,
		a,
		li,
		td {-webkit-text-size-adjust: none;}
		td {text-decoration: none !important;}
		td {border-bottom: none;}
		a[x-apple-data-detectors] {color: inherit !important;text-decoration: none !important;font-size: inherit !important;font-family: inherit !important;font-weight: inherit !important;line-height: inherit !important;}

		.boxshadow {box-shadow: 0 1px 4px 0 #eeeeee;border: none !important;}
		.call-to-action-btn{color:#000!important; border: solid #f4f4f4 1px!important; border-radius: 5px; padding: 10px 10px 10px 10px; background: #f4f4f4!important;}
		
		@media all and (min-width: 480px) {
			.hide {display: block !important;width: auto !important;overflow: auto !important;max-height: inherit !important;}
		}

		@media all and (max-width: 480px) {
			.mmhide {width: auto !important;}
			.mob-margin-auto {margin: auto !important;}
			.mob-hide {display: none !important;}
			.mob-showcell {display: table-cell !important;}
			.mobpadtop25 {padding: 25px 35px 0px 35px !important;}
			.mobpadbot25 {padding: 20px 35px 25px 35px !important;}
			.view-online_ltr {text-align: left !important;padding-left: 10px !important;padding-top: 0 !important;}
			.mob_fix {width: 100vw !important;}
			.table {width: 100% !important;max-width: 100% !important;}
			.table-main {width: 90% !important;}
			.table-sm {width: 80% !important;}
			.table-xsm {width: 70% !important;}
			.table-full {width: 100% !important;min-width: 100% !important;}
			.img {width: 100% !important;height: auto !important;}
			.header-border {width: 90% !important;height: 1px !important;}
			.pad20 {padding: 20px 20px 20px 20px !important;}
			.pad2035 {padding: 20px 35px 20px 35px !important;}
			.pad2035nobot {padding: 20px 35px 0px 35px !important;}
			.pad1235 {padding: 12px 35px 12px 35px !important;}
			.padside35 {padding: 0px 35px 0px 35px !important;}
			.pad2035noborder {padding: 20px 35px 20px 35px !important;border: none !important;}
			.pad2035follow {padding: 0px 35px 20px 35px !important;}
			.pad2035follownoborder {padding: 0px 35px 20px 35px !important;border: none !important;}
			.pad3035 {padding: 30px 35px 0 35px !important;}
			.padallsides {padding: 30px 20px 30px 20px !important;}
			.pad302020 {padding: 30px 20px 0px 20px !important;}
			.pad201020 {padding: 0px 20px 10px 20px !important;}
			.pad302030 {padding: 30px 20px 30px 20px !important;}
			.pad20sides {padding: 0px 20px 0px 20px !important;}
			.pad20sidestop20 {padding: 20px 20px 0px 20px !important;}
			.pad4035 {padding: 40px 35px 40px 35px !important;}
			.pad5035 {padding: 50px 35px 50px 35px !important;}
			.nopad {padding: 0px 0px 0px 0px !important;}
			.nopadtop {padding-top: 0px !important;}
			.padbot20 {padding-bottom: 20px !important;}
			.padbot20border {padding-bottom: 20px !important;border-bottom: solid 1px #dddddd !important;text-align: center !important;}
			.bordernone {border: none !important;}
			.centertext {text-align: center !important;}

			.hide {
			  max-height: 0;
			  /* Gmail*/
			  display: none;
			  /* Generic*/
			  mso-hide: all;
			  /* Outlook clients*/
			  overflow: hidden;
			  /* Generic */
			}
			
			.mobile-only {display: block !important;width: 100% !important;max-height: none !important;color: #000000 !important;font-size: 32px !important;}
			.mobileonlyblock {display: inline-block !important;}
			.padtop20 {padding: 20px 0px 0px 0px !important;}
			.padbottom20 {padding: 0px 0px 20px 0px !important;}
			.mobpadtopbot {padding-top: 25px !important;padding-bottom: 25px !important;}
			.mobtoppad {padding-top: 10px !important;}
			.mobhtwt {width: 250px !important;height: 110px !important;}
			.mobwt270 {width: 270px !important;}
			.mobwt115 {width: 115px !important;}
			.mobwtht11580 {width: 115px !important;height: 90px !important;}
			.noheight {height: auto !important;}
			.full {width: 100% !important;border: none !important;padding-left: 0px !important;padding-right: 0px !important;}
			a[class="full_border"] {width: 100% !important;padding-left: 0px !important;padding-right: 0px !important;}
			.display-block,.displayBlock {width: 100% !important;display: block !important;margin-bottom: 4px;}
			.mob_280 {width: 280px !important;}
			.mobpadbtm20 {padding-bottom: 20px;}
			.coldrop {display: block !important;float: left;width: 100% !important;}
			.coldropheader {display: table-header-group !important;width: 100% !important;}
			.coldropfooter {display: table-footer-group !important;width: 100% !important;}
			table th {margin: 0 !important;padding: 0 !important;vertical-align: top;font-weight: normal;}
			.mob_50per {width: 50% !important;margin: auto;}
			.mob_50per img {margin: auto;}
			.checked-mob-hide {width: 100% !important;height: auto !important;}
			.mob_align_left {text-align: left !important;}
			.mob_align_right {text-align: right !important;}
			.pad20noside {padding: 20px 0px 20px 0px !important;}
			.pad20nosidefollow {padding: 0px 0px 20px 0px !important;}
			.mob_pad203540 {padding: 20px 35px 40px 35px !important;}
			.padtop20bottom10 {padding: 20px 0px 10px 0px !important;}
			.pad303530 {padding: 30px 35px 30px 35px !important;}
			.table-three {width: 33% !important;}
			.coldropnoborder {display: block !important;float: left;width: 100% !important;min-width: 100% !important;border: 0px solid #fff !important;}
			.coldrop50_left {width: 50% !important;border: 0px solid #fff !important;border-radius: 10px 0px 0px 10px;min-width: 50% !important;vertical-align: middle;}
			.borderradiusleft {border-radius: 10px 0px 0px 10px;}
			.coldrop50_right {width: 50% !important;border-bottom: 1px solid #ccc !important;border-radius: 0px 10px 10px 0px;min-width: 50% !important;vertical-align: middle;border-left: 0px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;}
			.padtopbot {padding: 30px 0px 10px 0px !important;}
			.pad2020 {padding: 0px 0px 0px 0px !important;}
			.mrgtop30 {margin-top: 30px;width: 100% !important;max-width: 100% !important;}
		}
    </style>
  </head>
  <body style="margin: 0 auto; padding:0px;" bgcolor="#f2f2f2" id="body">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" role="presentation">
      <tbody>
        <tr>
          <td align="center" valign="middle" bgcolor="#f2f2f2">
            <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="table-full" role="presentation" style="min-width:320px; max-width:600px;">
              <tbody id="base">
                <tr>
                  <td align="center" valign="middle" bgcolor="#ffffff" style="background-color:#ffffff;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="min-width:320px;" role="presentation">
                      <tbody>
                        <tr>
                          <td align="center" valign="middle" style="padding:0px 0px 0px 0px;">
                            <table border="0" cellpadding="0" cellspacing="0" align="center" role="presentation">
                              <tbody>
                                <tr>
                                  <td align="center" valign="middle" width="600" height="542" style="vertical-align: middle;">
                                    <a href="{{ url('/') }}" target="_blank" style="text-decoration: none;">
                                      <img src="{{asset('emailTemplate/header.png')}}" alt="{{ config('constants.APP_NAME') }}" title="{{ config('constants.APP_NAME') }}" width="620" height="542" border="0" style="display:block; font-family:'pp-sans-big-medium', Tahoma, Arial, sans-serif; font-size:32px; color:#003288;"></a>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align="center" valign="middle" style="padding:0px 0px 0px 0px; background-color:#fab314;" bgcolor="#fab314">
                    <table width="509" border="0" cellpadding="0" cellspacing="0" align="center" class="table" style="max-width: 509px;" role="presentation">
                      <tbody>
                        <tr>
                          <td align="center" valign="middle" style="padding: 0px 9px 0px 9px; background-color:#fff;" class="pad2035nobot" bgcolor="#fff">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" role="presentation">
                              <tbody>
								<tr>
								  <td style="padding:10px 0px 10px 0px;">
									<table border="0" cellpadding="0" cellspacing="0" role="presentation">
									  <tbody>
										<tr>
										  <td align="left" valign="middle" width="250px;" style="vertical-align: middle;">
											<img src="{{ $template_data->restaurant_logo }}" height="60px"/>
											<p style="margin: 0px; font-family:'pp-sans-small-regular', Tahoma, Arial, sans-serif; font-size:14px; mso-line-height-rule:exactly; line-height:1.5; color:#6c7378;">
												Delivering to:
												<br>
												{{ $template_data->shipping_address }}
												<br>
												{{ $template_data->user->country_code .' '. $template_data->user->phone_number }}
											</p>
										  </td>
										  
										  <td align="right" valign="right" width="250px;">
											<p style="margin: 0px; font-family:'pp-sans-small-regular', Tahoma, Arial, sans-serif; font-size:14px; mso-line-height-rule:exactly; line-height:1.5; color:#6c7378;">
												Date: {{ $template_data->date }}
												<br>
												Order Number: {{ $template_data->order_id }}
												<br>
												Time: {{ $template_data->time }}
												<br>
												Estimated Delivery Time: 30:00 Min
											</p>
										  </td>
										</tr>
									  </tbody>
									</table>
								  </td>
								</tr>
							  
								<tr>
								  <td style="padding:10px 0px 10px 0px;">
									<table border="0" cellpadding="0" cellspacing="0" role="presentation">
									  <tbody>
										<tr>
										  <hr>
										</tr>
									  </tbody>
									</table>
								  </td>
								</tr>
								
								<!-- items -->
								@foreach($template_data->order_items as $list)
								<tr>
								  <td style="padding:10px 0px 10px 0px;">
									<table border="0" cellpadding="0" cellspacing="0" role="presentation">
									  <tbody>
										<tr>
										  <td align="left" valign="middle" style="vertical-align: middle;">
											<img src="{{ $list['image'] }}" width="80px" height="80px"/>
										  </td>
										  <td align="left" width="300px;" valign="left" style="vertical-align: middle; width:300px;">
											<p style="margin: 0px 0px 0px 20px; font-family:'pp-sans-small-regular', Tahoma, Arial, sans-serif; font-size:16px; mso-line-height-rule:exactly; line-height:1.5; text-align:left;">{{ $list['title'] }}</p>
										  </td>
										  <td align="right" valign="right" style="vertical-align: middle;">
										  {{ $list['price'] }}
										  </td>
										</tr>
									  </tbody>
									</table>
								  </td>
								</tr>
								@endforeach
								
								<tr>
                                  <td align="right" valign="middle" style="padding:0px 0px 0px 0px;">
                                    <p style="margin: 0px; font-family:'pp-sans-small-regular', Tahoma, Arial, sans-serif; font-size:14px; mso-line-height-rule:exactly; line-height:1.5; color:#6c7378;">
										Subtotal: <strong>{{ $template_data->total }}</strong>
										<br>
										Delivery Charge: <strong>{{ $template_data->shipping_charge }}</strong>
										<hr>
										Total: {{ $template_data->grand_total }}
									</p>
                                  </td>
                                </tr>
								
								<tr>
								  <td align="center" valign="middle" style="padding:0px 0px 0px 0px;">
									<table border="0" cellpadding="0" cellspacing="0" align="center" role="presentation">
									  <tbody>
										<tr>
										  <td align="center" valign="middle" height="60" style="vertical-align: middle;">
											<p style="margin: 10px 0px 10px opx; font-family:'pp-sans-small-regular', Tahoma, Arial, sans-serif; font-size:16px; mso-line-height-rule:exactly; line-height:1.5; text-align:center;"><a class="call-to-action-btn" style="text-decoration: none;" href="#">Payment Method: {{ $template_data->payment_method }}</a><p>
										  </td>
										</tr>
									  </tbody>
									</table>
								  </td>
								</tr>
								
								<tr>
								  <td align="center" valign="middle" style="padding:0px 0px 0px 0px;">
									<table border="0" cellpadding="0" cellspacing="0" align="center" role="presentation">
									  <tbody>
										<tr>
										  <td align="center" valign="middle" width="490" height="212" style="vertical-align: middle;">
											<a href="{{ url('/') }}" target="_blank" style="text-decoration: none;">
											  <img src="{{asset('emailTemplate/footer.png')}}" alt="{{ config('constants.APP_NAME') }}" title="{{ config('constants.APP_NAME') }}" width="490" height="212" border="0" style="display:block; font-family:'pp-sans-big-medium', Tahoma, Arial, sans-serif; font-size:32px; color:#003288;"/>
											</a>
										  </td>
										</tr>
									  </tbody>
									</table>
								  </td>
								</tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>