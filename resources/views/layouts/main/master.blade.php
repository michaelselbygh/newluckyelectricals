<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic page needs
			============================================ -->
		<title>{{ config('app.name') }} - @yield('page-title')</title>
		<meta charset="utf-8">
		<meta name="keywords" content="Electricals, Lighting, Pipes" />
        <meta name="author" content="Michael Selby">
        <meta name="description" content="Welcome to New Lucky Electricals, where quality costs less. Your one stop Shop for all your electrical items and accessories.">
		<!-- Mobile specific metas
			============================================ -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<!-- Favicon
			============================================ -->
            <link rel="apple-touch-icon" href="{{ url('portal/images/ico/apple-icon-120.png') }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ url('portal/images/ico/favicon-32.png') }}">
		<!-- Google web fonts
			============================================ -->
        <link href="{{ url('https://fonts.googleapis.com/css?family=Nanum+Gothic&display=swap') }}" rel="stylesheet"> 
		<!-- Libs CSS
			============================================ -->
		<link rel="stylesheet" href="main/css/bootstrap/css/bootstrap.min.css">
		<link href="main/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="main/js/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
		<link href="main/js/owl-carousel/owl.carousel.css" rel="stylesheet">
		<link href="main/css/themecss/lib.css" rel="stylesheet">
		<link href="main/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		<!-- Theme CSS
			============================================ -->
		<link href="main/css/themecss/so_megamenu.css" rel="stylesheet">
		<link href="main/css/themecss/so-categories.css" rel="stylesheet">
		<link href="main/css/themecss/so-listing-tabs.css" rel="stylesheet">
		<link href="main/css/footer.css" rel="stylesheet">
		<link href="main/css/header.css" rel="stylesheet">
		<link id="color_scheme" href="main/css/home.css" rel="stylesheet">
		<link href="main/css/responsive.css" rel="stylesheet">
	</head>
	<body class="common-home res layout-home1">
		<div id="wrapper" class="wrapper-full banners-effect-7">
			<!-- Header Container  -->
			<header id="header" class=" variantleft type_2">
				<!-- Header Top -->
				<div class="header-top" style="background-color: #197852; color: white;">
					<div class="container">
						<div class="row">
							<marquee behavior="scroll" direction="left">{{ $settings['scrolling_text'] }}</marquee>
						</div>
					</div>
				</div>
				<!-- //Header Top -->
				<!-- Header center -->
				<div class="header-center right">
					<div class="container">
						<div class="row">
							<!-- Logo -->
							<div class="navbar-logo col-md-3 col-sm-12 col-xs-12" style="padding-top: 5px;">
								<a href="{{ route('home') }}"><img src="{{ url('portal/images/logo/logo.png') }}" style="height: 25px;" title="New Lucky Logo" alt="New Lucky Logo" /></a>
							</div>
							<!-- //end Logo -->
							<!-- Main Menu -->
							<div class="megamenu-hori navbar-menu col-lg-9 col-md-9 col-sm-6 col-xs-6" >
								<div class="responsive so-megamenu ">
									<nav class="navbar-default">
										<div class=" container-megamenu  horizontal">
											<div class="navbar-header">
												<button type="button" id="show-megamenu" data-toggle="collapse" class="navbar-toggle">
												<span class="icon-bar"></span>
												<span class="icon-bar"></span>
												<span class="icon-bar"></span>
												</button>
												Navigation		
											</div>
											<div class="megamenu-wrapper">
												<span id="remove-megamenu" class="fa fa-times"></span>
												<div class="megamenu-pattern">
													<div class="container">
														<ul class="megamenu " data-transition="slide" data-animationtime="250">
															<li class="">
																<p class="close-menu"></p>
																<a href="{{ route('home') }}" class="clearfix">
																<strong>Home</strong>
																<span class="label"></span>
																</a>
															</li>
															<li class="with-sub-menu hover">
																<p class="close-menu"></p>
																<a href="" class="clearfix">
																<strong>Categories</strong>
																<b class="caret"></b>
																</a>
																<div class="sub-menu" style="width: 100%; right: auto;">
																	<div class="content" >
																		<div class="row">
                                                                            @for ($i = 0; $i < sizeof($categories); $i++)
                                                                                <div class="col-md-3">
                                                                                    <div class="column">
                                                                                        <a href="{{ route('category', $categories[$i]['slug']) }}" class="title-submenu">{{ $categories[$i]["description"] }}</a>
                                                                                        <div>
                                                                                            <ul class="row-list">
                                                                                                @for ($j = 0; $j < sizeof($categories[$i]["children"]); $j++)
                                                                                                    <li><a href="{{ route('category', $categories[$i]['children'][$j]['slug']) }}">{{ $categories[$i]['children'][$j]["description"] }}</a></li>
                                                                                                @endfor
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                </div>
                                                                                @if ($i%3 == 0 && $i != 0)
                                                                                    </div>
                                                                                    <div class="row">
                                                                                @endif
                                                                            @endfor
																		</div>
																	</div>
																</div>
															</li>
															<li class="">
																<p class="close-menu"></p>
																<a href="{{ route('shop') }}" class="clearfix">
																<strong>Shop</strong>
																<span class="label"></span>
																</a>
															</li>
															<li class="">
																<p class="close-menu"></p>
																<a href="{{ route('about-us') }}" class="clearfix">
																<strong>About New Lucky Electricals</strong>
																<span class="label"></span>
																</a>
															</li>
															<li class="">
																<p class="close-menu"></p>
																<a href="{{ route('contact-us') }}" class="clearfix">
																<strong>Contact Us</strong>
																<span class="label"></span>
																</a>
															</li>
															<li class="">
																<p class="close-menu"></p>
																<a href="{{ route('locate-a-store') }}" class="clearfix">
																<strong>Locate a Store</strong>
																<span class="label"></span>
																</a>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</nav>
								</div>
							</div>
							<!-- //Main Menu -->
							
						</div>
					</div>
				</div>
				<!-- //Header center -->
			</header>
			<!-- //Header Container  -->
			<!-- Block Spotlight3  -->
			@yield('page-content')
			
			<!-- Footer Container -->
			<footer class="footer-container type_footer1">
				<!-- Footer Top Container -->
				<section class="footer-top">
					<div class="container content">
						<div class="row">
							<div class="col-sm-6 col-md-4 box-information">
								<div class="module clearfix">
									<div>
                                        <a href="{{ route('home') }}"><img src="{{ url('portal/images/logo/logo.png') }}" style="height: 25px;" title="New Lucky Logo" alt="New Lucky Logo" /></a>
                                    </div>
                                    <p><i>...Where Quality Costs Less</i></p>
                                    <br>
                                    <h3 class="modtitle" style="margin-bottom: 5px;">Working Hours</h3>
                                    Monday - Fridays, 8am - 6pm<br>
                                    Saturdays, 8am - 4pm
								</div>
							</div>
							<div class="col-sm-6 col-md-3 box-service">
								<div class="module clearfissx">
									<h3 class="modtitle">Customer Service</h3>
									<div class="modcontent">
										<ul class="menu">
											<li><a href="{{ route('home') }}">Home</a></li>
											<li><a href="{{ route('shop') }}">Shop</a></li>
                                            <li><a href="{{ route('about-us') }}">About Us</a></li>
                                            <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
										</ul>
									</div>
								</div>
                            </div>
                            
							<div class="col-sm-6 col-md-3 collapsed-block ">
								<div class="module clearfix">
									<h3 class="modtitle">Contact Us	</h3>
									<div class="modcontent">
										<ul class="contact-address">
											<li><span class="fa fa-phone">&nbsp;</span><a href="tel:0279694445">0279694445 </a><br><a href="tel:0242139117">0242139117</a></li>
											<li><span class="fa fa-envelope-o"></span><a href="mailto:info@newluckyelectricals.com.gh"> info@newluckyelectricals.com.gh</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-md-2 box-account">
								<div class="module clearfix">
									<h3 class="modtitle">Portal</h3>
									<div class="modcontent">
										<ul class="menu">
                                            <li><a href="{{ route('manager.login') }}" target="_blank">Manager</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- /Footer Top Container -->
				<!-- Footer Bottom Container -->
				<div class="footer-bottom-block ">
					<div class=" container">
						<div class="row">
							<div class="col-sm-12 copyright-text" style="text-align:center"> © {{ date('Y') }} New Lucky Electricals. All Rights Reserved. </div>
							<div class="col-sm-12" style="text-align:center">
                                Designed and Built by <a href="{{ url('https://www.michaelselby.me') }}" target="_blank" style="color: #ffdd1a;">Michael Selby Creative Studio</a>
							</div>
							<!--Back To Top-->
							<div class="back-to-top"><i class="fa fa-angle-up"></i><span> Top </span></div>
						</div>
					</div>
				</div>
				<!-- /Footer Bottom Container -->
			</footer>
			<!-- //end Footer Container -->
		</div>
		<!-- Include Libs & Plugins
			============================================ -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script type="text/javascript" src="{{ url('main/js/jquery-2.2.4.min.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/owl-carousel/owl.carousel.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/themejs/libs.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/unveil/jquery.unveil.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/countdown/jquery.countdown.min.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/dcjqaccordion/jquery.dcjqaccordion.2.8.min.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/datetimepicker/moment.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/jquery-ui/jquery-ui.min.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/modernizr/modernizr-2.6.2.min.js') }}"></script>
		<!-- Theme files
			============================================ -->
		<script type="text/javascript" src="{{ url('main/js/themejs/application.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/themejs/homepage.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/themejs/so_megamenu.js') }}"></script>
		<script type="text/javascript" src="{{ url('main/js/themejs/addtocart.js') }}"></script>	
	</body>
</html>
