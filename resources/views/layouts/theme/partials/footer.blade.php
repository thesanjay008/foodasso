	<footer>
        <div class="wave footer"></div>
        <div class="container margin_60_40 fix_mobile">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <h3 data-target="#collapse_1">Quick Links</h3>
                    <div class="collapse dont-collapse-sm links" id="collapse_1">
                        <ul>
                            <li><a href="{{ url('/about-us') }}">About us</a></li>
							@if(Auth::user())
                            <li><a href="{{ url('/my-account') }}">My account</a></li>
							@else
							<li><a class="modal_dialog" href="#sign-in-dialog">My account</a></li>
							@endif
                            <li><a href="{{ url('/qr-code') }}">QR Code</a></li>
                            <li><a href="{{ url('/contact-us') }}">Contacts</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 data-target="#collapse_2">LEGAL</h3>
                    <div class="collapse dont-collapse-sm links" id="collapse_2">
                        <ul>
                            <li><a href="{{ url('/terms') }}">Terms</a></li>
                            <li><a href="{{ url('/privacy') }}">Privacy</a></li>
                            <li><a href="{{ url('/refund') }}">Refund</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                        <h3 data-target="#collapse_3">Contacts</h3>
                    <div class="collapse dont-collapse-sm contacts" id="collapse_3">
                        <ul>
                            <li><i class="icon_house_alt"></i>{{ Settings::get('address') }}</li>
                            <li><i class="icon_mobile"></i>{{ Settings::get('contact_no') }}</li>
                            <li><i class="icon_mail_alt"></i><a href="{{ Settings::get('site_email') }}">{{ Settings::get('site_email') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
					<h3 data-target="#collapse_4">Follow Us</h3>
                    <div class="collapse dont-collapse-sm" id="collapse_4">
                        <!--<div id="newsletter">
                            <div id="message-newsletter"></div>
                            <form method="post" action="javascript:void(0);" name="newsletter_form" id="newsletter_form">
                                <div class="form-group">
                                    <input type="email" name="email_newsletter" id="email_newsletter" class="form-control" placeholder="Your email">
                                    <button type="submit" id="submit-newsletter"><i class="arrow_carrot-right"></i></button>
                                </div>
                            </form>
                        </div>-->
                        <div class="follow_us">
                            <!--<h5>Follow Us</h5>-->
                            <ul>
                                <li><a href="javascript:void(0);"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="{{asset('themeAssets/img/twitter_icon.svg')}}" alt="" class="lazy"></a></li>
                                <li><a href="javascript:void(0);"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="{{asset('themeAssets/img/facebook_icon.svg')}}" alt="" class="lazy"></a></li>
                                <li><a href="javascript:void(0);"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="{{asset('themeAssets/img/instagram_icon.svg')}}" alt="" class="lazy"></a></li>
                                <li><a href="javascript:void(0);"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="{{asset('themeAssets/img/youtube_icon.svg')}}" alt="" class="lazy"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /row-->
            <hr>
            <div class="row add_bottom_25">
                <div class="col-lg-6">
                    <ul class="footer-selector clearfix">
                        <li>
                            <div class="styled-select lang-selector">
                                <select>
                                    <option value="English" selected>English</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="styled-select currency-selector">
                                <select>
                                    <option value="Indian Rupees" selected>₹ INR</option>
                                </select>
                            </div>
                        </li>
                        <li><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="{{asset('themeAssets/img/cards_all.svg')}}" alt="" width="230" height="35" class="lazy"></li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <ul class="additional_links">
                        <li><span>© {{ Settings::get('copy_rights_year') }} <a href="javascript:void(0);" target="_blank">{{ Settings::get('copy_rights_credit_line') }}</a></span></li>
                        <li><span>Powered by <a href="https://www.foodasso.com" target="_blank">Foodasso</a></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!--/footer-->