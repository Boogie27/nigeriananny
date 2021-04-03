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
						<p>Info: <?= $settings->info_email ? $settings->info_email : ''; ?></p>
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
								<li><a href="#">Logout</a></li>
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
							<li class="list-inline-item"><a href="#" class="news_letter_open_btn">News letter</a></li>
							<li class="list-inline-item"><b>Business hours:</b> <?= $settings->business_hours ? $settings->business_hours : '';?></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-12 col-md-4 col-lg-3 col-xl-4 pb15 pt15">
					<div class="footer_social_widget mt15">
						<ul>
						<?php if($settings->facebook):?>
							<li class="list-inline-item"><a href="http://<?= $settings->facebook?>"><i class="fa fa-facebook"></i></a></li>
						<?php endif; ?>
						<?php if($settings->twitter):?>
							<li class="list-inline-item"><a href="http://<?= $settings->twitter?>"><i class="fa fa-twitter"></i></a></li>
						<?php endif; ?><?php if($settings->instagram):?>
							<li class="list-inline-item"><a href="http://<?= $settings->instagram?>"><i class="fa fa-instagram"></i></a></li>
						<?php endif; ?><?php if($settings->google):?>
							<li class="list-inline-item"><a href="http://<?= $settings->google?>"><i class="fa fa-google"></i></a></li>
						<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!-- news letter start -->
	<div class="news-dark-layer <?= !Cookie::has('remove_news_letter') ? 'news-dark-layer-js' : '' ?>" style="display: <?= Cookie::has('remove_news_letter') ? 'none' : '' ?>">
			<div class="news-letter-container">
				<div class="news-letter-form">
					<div class="newsletter-cancle-btn">
					    <button type="submit" id="news_letter_close_btn" title="close"><i class="fa fa-times"></i></button>
					</div>
					<div class="alert alert-danger text-center mb-2 page_alert_danger" style="display: none;"></div>
					<div class="alert alert-success text-center mb-2 page_alert_success" style="display: none;"></div>
					<div class="row">
						<div class="col-lg-4 col-md-5">
							<div class="updated-container">
								<div class="update-content">
									<h4>Stay updated</h4>
									<p>Join our and get the latest information, listings and career insights delivered straight to your inbox.</p>
									<div class="form-group news-checker text-center">
										<input type="checkbox" id="news_letter_stop" value="stop">
										<label for="">Stop seeing this</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-8 col-md-7 mt-3">
							<form action="<?= current_url() ?>" method="POST">
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<div class="alert_news alert_news_0 text-danger text-left"></div>
											<input type="text" id="news_letter_fullname" class="form-control h50" value="" placeholder="Full name">
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
											<div class="alert_news alert_news_1 text-danger text-left"></div>
											<input type="email" id="news_letter_email" class="form-control h50" value="" placeholder="Email">
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group" style="margin: 0px;">
											<div class="alert_news alert_news_2 text-danger"></div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group news-checker" style="margin: 0px;">
											<input type="checkbox" class="news-letter-checker" value="employee">
											<label for="">Job seeker</label>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group news-checker" tyle="margin: 0px;">
											<input type="checkbox" class="news-letter-checker" value="employer">
											<label for="">Employer</label>
										</div>
									</div>
									
									<div class="col-lg-12">
										<input type="hidden" id="client_type_input" value="">
										<button type="submit" id="submit_newsletter_request_btn" class="btn btn2 btn-block color-white bgc-gogle mb0">
											<i class="fa fa-envelope float-left mt5"></i> 
											<span class="news-letter-sub">SUBSCRIBE</span>
											<span class="news-letter-preloader" style="display: none;">Please wait...</span>
										</button>
									</div>
								</div>
							</form>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	<!-- news letter end -->



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


<a href="<?= url('/ajax.php') ?>" class="ajax_url_page_news_letter" style="display: none;"></a> <!-- ajax url-->



</div>
<!-- Wrapper End -->
<script data-cfasync="false" src="https://grandetest.com/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-migrate-3.0.0.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/popper.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery.mmenu.all.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/ace-responsive-menu.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/snackbar.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/simplebar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/parallax.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/scrollto.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-scrolltofixed-min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery.counterup.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/wow.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/progressbar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/slider.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/timepicker.js"></script>
<!-- Custom script for all pages --> 
<script type="text/javascript" src="<?= SITE_URL ?>/js/script.js"></script>












<script>
$(document).ready(function(){


// ========================================
// DISPLAY NEWS LETTER AFTER 5 SECONDS
// ========================================
function display_newsletter(){
	setTimeout(function(){
		$('.news-dark-layer-js').css({
			display: 'table'
		});
		$('.news-dark-layer-js').show();
	}, 5000);
}
display_newsletter();


// ========================================
// ASSIGN GENDER FIELD
// ========================================
var client = $(".news-letter-checker");
$.each($(".news-letter-checker"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < client.length; i++){
            if(index != i)
            {
               $($(client)[i]).prop('checked', false);
            }else{
                $($(client)[i]).prop('checked', true);
            }
        }
    });
});


$(client).click(function(){
    $("#client_type_input").val($(this).val());
});




// ===========================================
// NEWS LETTER CLOSE BTN
// ===========================================
$("#news_letter_close_btn").click(function(e){
	e.preventDefault();
	close_news_letter();
});


function close_news_letter(){
	init();
	$('.news-dark-layer').hide();
	$("#client_type_input").val('');
	$("#news_letter_email").val('');
	$("#news_letter_fullname").val('');
	$(".news-letter-checker").prop('checked', false);
}





// ===========================================================
// CLICK ON DARK BACKGROUND TO CLOSE NEWS LETTER
// ===========================================================
$(window).click(function(e){
	var container = $(e.target).hasClass('news-letter-container');
	if(container)
	{
		close_news_letter();
	}
});





// ========================================
// OPEN NEWS LETTER
// ========================================
$(".news_letter_open_btn").click(function(e){
    e.preventDefault();
	$('.news-dark-layer').css({
			display: 'table'
		});
	$('.news-dark-layer').show();
});






// ========================================
// SUBSCRIBE TO NEWS LETTER
// ========================================
$("#submit_newsletter_request_btn").click(function(e){
	e.preventDefault();
	$(".alert_news").html('');
	var url = $(".ajax_url_page_news_letter").attr('href');
	var full_name = $("#news_letter_fullname").val();
	var email = $("#news_letter_email").val();
	var client_type = $("#client_type_input").val();
	$(".news-letter-sub").hide();
	$(".news-letter-preloader").show();

    init();

	$.ajax({
        url: url,
        method: 'post',
        data: {
			email: email,
			client_type: client_type,
			full_name: full_name,
            subscribe_news_letter: 'subscribe_news_letter'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.error){
				get_newsletter_error(data.error)
			}else if(data.data){
				clear_fields();
				$(".page_alert_success").show();
				$(".page_alert_success").html('You have subscribed to newsletter successfully!');
			}
			$(".news-letter-sub").show();
			$(".news-letter-preloader").hide();
        },
		error: function(){
			$(".page_alert_danger").show();
			$(".page_alert_danger").html('*newwork error, try again later!');
		}
    });
});



// =========================================
// initialize
// =========================================
function init(){
	$(".page_alert_danger").html('');
	$(".page_alert_danger").hide();
	$(".page_alert_success").hide();
	$(".page_alert_success").hide('');
}


// ======================================
// CLEAR ALL FIELDS
// ======================================
function clear_fields(){
	$("#client_type_input").val('');
	$("#news_letter_email").val('');
	$("#news_letter_fullname").val('');
	$(".news-letter-checker").prop('checked', false);
}





function get_newsletter_error(error){
	$(".alert_news_0").html(error.full_name)
	$(".alert_news_1").html(error.email)
	$(".alert_news_2").html(error.client_type)
}







// ===========================================
// STOP SEEING NEWS LETTER FORM
// ===========================================
$("#news_letter_stop").click(function(){
	var url = $(".ajax_url_page_news_letter").attr('href');
	
	$.ajax({
        url: url,
        method: 'post',
        data: {
            stop_news_letter: 'stop_news_letter'
        },
        success: function(response){
            var data = JSON.parse(response);
			location.reload();
        }
    });
});





// end
});
</script>





</body>

<!-- Mirrored from grandetest.com/theme/edumy-html/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:05:06 GMT -->
</html>






