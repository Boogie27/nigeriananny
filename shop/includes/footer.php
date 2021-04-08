<?php
$settings = $connection->select('settings')->where('id', 1)->first();
?>



	<!-- Our Footer -->
	<section class="footer_one">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-md-6 col-lg-6">
					<div class="footer_contact_widget">
						<h4>CONTACT</h4>
						<p>Phone: <?= $settings->phone ? $settings->phone : ''; ?></p>
						<p>Address: <?= $settings->address ? $settings->address : ''; ?></p>
						<p>City: <?= $settings->city ? $settings->city : ''; ?></p>
						<p>State: <?= $settings->state ? $settings->state : ''; ?></p>
						<p>Country: <?= $settings->country ? $settings->country : ''; ?></p>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-md-3 col-lg-2">
					<div class="footer_company_widget">
						<h4>NANNY WORK</h4>
						<ul class="list-unstyled">
							<li><a href="<?= url('/') ?>">Home</a></li>
							<li><a href="<?= url('/form') ?>">Forms</a></li>
							<li><a href="<?= url('/jobs') ?>">Employees</a></li>
							<li><a href="<?= url('/subscription') ?>">Subscription</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-md-3 col-lg-2">
					<div class="footer_company_widget">
						<h4>NANNY SHOP</h4>
						<ul class="list-unstyled">
							<li><a href="<?= url('/shop') ?>">Home</a></li>
							<li><a href="">Categories</a></li>
							<li><a href="<?= url('/shop/account') ?>">My account</a></li>
							<li><a href="<?= url('/shop/order') ?>">My order</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-md-3 col-lg-2">
					<div class="footer_program_widget">
						<h4>FEATURES</h4>
						<ul class="list-unstyled">
							<li><a href="<?= url('/shop/order-cancle') ?>">Order cancle</a></li>
							<li><a href="<?= url('/shop/reviewed') ?>">Reviews</a></li>
							<li><a href="<?= url('/shop/address-book') ?>">Address book</a></li>
							<?php if(!Auth::is_loggedin()): ?>
								<li><a href="<?= url('/shop/login') ?>">Login</a></li>
								<li><a href="<?= url('/shop/register') ?>">Register</a></li>
							<?php else: ?>
								<li><a href="<?= url('/shop/logout') ?>">Logout</a></li>
							<?php endif;?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Our Footer Middle Area -->
	<section class="footer_middle_area p0">
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 pb15 pt15">
					<div class="logo-widget home2">
					   <?php if($settings->footer_logo): ?>
						<img class="img-fluid" src="<?= asset($settings->footer_logo)?>" alt="logo.png">
						 <?php endif;?>
						<span><?= $settings->app_name ? $settings->app_name : '' ?></span>
					</div>
				</div>
				<div class="col-sm-8 col-md-5 col-lg-6 col-xl-6 pb25 pt25 brdr_left_right">
					<div class="footer_menu_widget">
						<ul>
							<li class="list-inline-item"><a href="<?= url('/shop') ?>">Home</a></li>
							<li class="list-inline-item"><a href="#">Privacy</a></li>
							<li class="list-inline-item"><a href="#">Terms</a></li>
							<li class="list-inline-item"><b>Business hours:</b> <?= $settings->business_hours ? $settings->business_hours : '';?></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-12 col-md-4 col-lg-3 col-xl-4 pb15 pt15">
					<div class="footer_social_widget mt15">
						<ul>
						<?php if($settings->facebook):?>
							<li class="list-inline-item"><a href="<?= $settings->facebook?>"><i class="fa fa-facebook"></i></a></li>
						<?php endif; ?>
						<?php if($settings->twitter):?>
							<li class="list-inline-item"><a href="<?= $settings->twitter?>"><i class="fa fa-twitter"></i></a></li>
						<?php endif; ?><?php if($settings->instagram):?>
							<li class="list-inline-item"><a href="<?= $settings->instagram?>"><i class="fa fa-instagram"></i></a></li>
						<?php endif; ?><?php if($settings->linkedin):?>
							<li class="list-inline-item"><a href="<?= $settings->linkedin?>"><i class="fa fa-linkedin"></i></a></li>
						<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Our Footer Bottom Area -->
	<section class="footer_bottom_area pt25 pb25">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 offset-lg-3">
					<div class="copyright-widget text-center">
						<p><?= $settings->alrights ? $settings->alrights : '' ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>
<!-- Wrapper End -->
<script data-cfasync="false" src="https://grandetest.com/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script type="text/javascript" src="js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery-migrate-3.0.0.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/popper.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery.mmenu.all.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/ace-responsive-menu.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/isotop.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/snackbar.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/simplebar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/parallax.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/scrollto.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery-scrolltofixed-min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery.counterup.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/wow.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/progressbar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/slider.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/timepicker.js"></script>
<!-- Custom script for all pages --> 
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/script.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/dashboard-script.js"></script>

<!-- main script-->
<script  type="text/javascript" src="<?= SITE_URL ?>/shop/js/main-script.js"></script>
</body>

<!-- Mirrored from grandetest.com/theme/edumy-html/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:05:06 GMT -->
</html>
