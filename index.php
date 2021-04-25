<?php include('Connection.php');  ?>

<?php



// ==================================
// FACEBOOK LOGIN AUTH
// ==================================
if(Session::has('facebook_auth') && Input::exists('get') && Input::get('code'))
{
	$facebook = new Facebook();
	$user_data = $facebook->user_data();
	
	// LOGIN AN EMPLOYEE
	if(Session::has('fb_employee_login'))
	{
		Session::delete('fb_employee_login');
		$employee_login = Input::facebook_employee_login($user_data['email']);
		if($employee_login == 'deactivated')
		{
			Session::delete('facebook_auth');
			Session::flash('error', '*This account has been deactivated, please contact the admin.');
			return view('/employee/login');
		}else if($employee_login == 'login'){
			Session::delete('facebook_auth');
			Session::flash('success', 'You have loggedin successfully!');
			return view('/');
		}else{
			Session::delete('old_url');
			Session::delete('facebook_auth');
			Session::flash('success', 'You have loggedin successfully!');
			return view($employee_login);
		}
	}



	// LOGIN AN EMPLOYER
	if(Session::has('fb_employer_login'))
	{
		Session::delete('fb_employer_login');
		$employer_login = Input::facebook_employer_login($user_data['email']);
		if($employer_login == 'deactivated')
		{
			Session::delete('facebook_auth');
			Session::flash('error', '*This account has been deactivated, please contact the admin.');
			return view('/employer/login');
		}else if($employer_login == 'login'){
			Session::delete('facebook_auth');
			Session::flash('success', 'You have loggedin successfully!');
			return view('/');
		}else{
			Session::delete('old_url');
			Session::delete('facebook_auth');
			Session::flash('success', 'You have loggedin successfully!');
			return view($employer_login);
		}
	}




	// LOGIN USER IN ECOMMERCE
	if(Session::has('fb_shop_login'))
	{
		Session::delete('fb_shop_login');
		$shop_login = Input::facebook_shop_login($user_data['email']);
		if($shop_login == 'deactivated')
		{
			Session::delete('facebook_auth');
			Session::flash('error', '*This account has been deactivated, please contact the admin.');
			return view('/shop/login');
		}else if($shop_login == 'login'){
			Session::delete('facebook_auth');
			Session::flash('success', 'You have loggedin successfully!');
			return view('/shop');
		}else{
			Session::delete('facebook_auth');
			Session::delete('old_url');
			Session::flash('success', 'You have loggedin successfully!');
			return view($shop_login);
		}
	}
	
	
	
	
}







// ==================================
// GOOGLE LOGIN AUTH
// ==================================
if(Session::has('google_auth') && Input::exists('get') && Input::get('code'))
{
	$google = new Google();
	
	$token = $google->auth_code(Input::get('code'));
	if(!isset($token['error']))
	{
		$google->token($token);

		$data = $google->data();

		// LOGIN TO ECOMMERCE SECTION
		if(Session::has('shop_login'))
		{
			$shop_login = Input::shop_google_login($data['email']);
			if($shop_login == 'deactivated')
			{
				Session::delete('google_auth');
				Session::flash('error', '*This account has been deactivated, please contact the admin.');
                return view('/shop/login');
			}else if($shop_login == 'login'){
				Session::delete('google_auth');
				Session::flash('success', 'You have loggedin successfully!');
				return view('/shop');
			}else{
				Session::delete('google_auth');
				Session::delete('old_url');
				Session::flash('success', 'You have loggedin successfully!');
				return view($shop_login);
			}
		}


		// LOGIN AS EMPLOYER
		if(Session::has('employer_login'))
		{
			$employer_login = Input::employer_google_login($data['email']);
			if($employer_login == 'deactivated')
			{
				Session::delete('google_auth');
				Session::flash('error', '*This account has been deactivated, please contact the admin.');
                return view('/employer/login');
			}else if($employer_login == 'login'){
				Session::delete('google_auth');
				Session::flash('success', 'You have loggedin successfully!');
				return view('/');
			}else{
				Session::delete('old_url');
				Session::delete('google_auth');
				Session::flash('success', 'You have loggedin successfully!');
				return view($employer_login);
			}
		}


		// LOGIN AS EMPLOYEE
		if(Session::has('employee_login'))
		{
			$employee_login = Input::employee_google_login($data['email']);
			if($employee_login == 'deactivated')
			{
				Session::delete('google_auth');
				Session::flash('error', '*This account has been deactivated, please contact the admin.');
                return view('/employee/login');
			}else if($employee_login == 'login'){
				Session::delete('google_auth');
				Session::flash('success', 'You have loggedin successfully!');
				return view('/');
			}else{
				Session::delete('old_url');
				Session::delete('google_auth');
				Session::flash('success', 'You have loggedin successfully!');
				return view($employee_login);
			}
		}
	}
}




// ===========================================
// GET FREQUESNTLY ASK QUESTIONS
// ===========================================
$faqs = $connection->select('faqs')->where('is_feature', 1)->get();




// ==================================
// GET FEATURED EMPLOYERS
// ==================================
$employers = $connection->select('employers')->where('e_feature', 1)->where('e_deactivate', 0)->get();


// ==================================
// GET FEATURED EMPLOYEES
// ==================================
$employees = $connection->select('employee')->leftJoin('workers', 'employee.e_id', '=', 'workers.employee_id')->where('is_flagged', 0)->where('e_is_deactivate', 0)->where('is_feature', 1)->where('e_approved', 1)->limit(9)->get();



// ==================================
// GET EMPLOYEES
// ==================================
$workers = $connection->select('workers')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('is_job_feature', 1)->where('is_top', 1)->where('employee.is_feature', 1)->where('employee.e_approved', 1)->where('is_flagged', 0)->where('employee.e_is_deactivate', 0)->random()->limit(9)->get();




// ==================================
// GET TESTIMONIAL
// ==================================
$testimonials = $connection->select('testimonial')->where('is_featured', 1)->get();

?>


<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<!-- images/home/4.jpg -->
	

<!-- mobile navigation-->
<?php include('includes/mobile-navigation.php');  ?>

<!-- job search start-->
	<?php include('includes/search.php');  ?>
<!-- job search end-->





<!-- page content start-->
<div class="content-container">

	<!-- jobs start-->
	<div class="top-jobs-container">
		<div class="job-head">
			<h3 style="color: #333;">Top trending workers</h3>
		</div>
		<div class="jobs-body">
			<div class="alert-container">
			    <?php if(Session::has('success')): ?>
                    <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div>
                <?php endif; ?>
			</div>
			<div class="row">
				<?php if(count($workers)): 
					foreach($workers as $worker):
						$w_image = $worker->w_image ?  $worker->w_image : '/images/employee/demo.png';
						$amount = !$worker->amount_to ? money($worker->amount_form) : money($worker->amount_form).' - '.money($worker->amount_to);
					?>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="job-con">
							<a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>"><img src="<?= asset($w_image)?>" alt=""></a>
							<ul>
								<li><h4><a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>"><?= ucfirst($worker->job_title) ?></a></h4></li>
								<li><?= ucfirst($worker->first_name.' '.$worker->last_name) ?></li>
								<li><?= $worker->job_type != 'live in' ? 'Live out' : 'Live in';?>| <span class="text-warning money-amount"><?= $amount ?></span></li>
								<li>
									<a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>" class="text-primary">view details</a>
									<span class="float-right"><i class="fa fa-clock-o text-success "></i> <span  style="font-size: 12px;" class="text-success"><?= date('d M Y', strtotime($worker->date_added)) ?></span></span>
								</li>
							</ul>
						</div>
					</div>
					<?php endforeach; ?>
				<?php else: ?>

				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- jobs end-->



	<!-- standout start-->
	<div class="standout-conatiner" style="background-color: #fff;">
		<div class="standout-banner">
			<div class="std-head">
				<h3 class="content-head">Stand out!</h3>
				<p class="p">How Nigeria nanny helps you</p>
			</div>
             <br><br>
			<div class="standout-bodys">
				<div class="row">
					<div class="col-lg-4 standout-animate">
						<div class="std-content">
                            <ul>
								<li><i class="fa fa-search"></i></li>
								<li><h5>Find the right job</h5></li>
								<li><p>search for jobs where ever you are all at your finger tip.</p></li>
							</ul>
						</div>
					</div>
					<div class="col-lg-4 standout-animate">
						<div class="std-content">
                            <ul>
								<li><i class="fa fa-file"></i></li>
								<li><h5>Boost your chances</h5></li>
								<li>
									<p>Create a profile get it to 100% and immediately get noticed by the right recruiter.</p>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-lg-4 standout-animate">
						<div class="std-content">
                            <ul>
								<li><i class="fa fa-star"></i></li>
								<li><h5>Be the best candidate</h5></li>
								<li><p>Get access to working guidiance and advice via the work center and stand out from the rest.</p></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="base">
					<div class="direction text-center" id="direction_x">
					<!-- <i class="fa fa-circle"></i>  this is being appended using jquery at the bottom of this page-->
					</div>
					<div class="std-content-a" style="border: none;">
						<a href="<?= url('/form') ?>">Create your account</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- standout end-->


<?php if(count($employers)): ?>
   	<!-- emmployer start-->
    <div class="employer-container">
	    <div class="std-head feature-x">
			<br>
			<h3 class="content-head">Featured employers</h3>
			<p class="p">Your next job could be with one of these employers</p>
		</div>
		<div class="employer-body">
		    <div class="row">
				<div class="col-lg-12">
					<div class="testimonial_slider_home2">
						<?php if(count($employers)): 
							foreach($employers as $employer):
							   $e_image = $employer->e_image ? $employer->e_image : '/images/employer/demo.png';
							?>
							<div class="item">
								<div class="testimonial_item home2">
									<div class="thumb">
										<img class="img-fluid rounded-circle" src="<?= asset($e_image) ?>" alt="<?= $employer->first_name ?>">
										<div class="title"><?= ucfirst($employer->first_name.' '.$employer->last_name) ?></div>
										<div class="subtitle"><?= $employer->city.', '.$employer->state ?></div>
									</div>
								</div>
							</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="std-content-a">
				<a href="<?= url('/employer/register') ?>">Become an employer</a>
			</div>
		</div>
	</div>
<?php endif; ?>
	<!-- emmployer end-->



	<!-- jobs start-->
	<?php if(count($employees)):  ?>
	<div class="top-jobs-container">
		<div class="job-head">
			<h3 style="color: #333;">Featured employees</h3>
		</div>
		<div class="jobs-body">
			<div class="row">
				<?php if(count($employees)): 
					foreach($employees as $employee):
						$w_image = $employee->w_image ?  $employee->w_image : '/images/employee/demo.png';
						$amount = !$employee->amount_to ? money($employee->amount_form) : money($employee->amount_form).' - '.money($employee->amount_to);
					?>
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="job-con">
							<a href="<?= url('/job-detail.php?wid='.$employee->worker_id) ?>"><img src="<?= asset($w_image)?>" alt=""></a>
							<ul>
								<li><h4><a href="<?= url('/job-detail.php?wid='.$employee->worker_id) ?>"><?= ucfirst($employee->job_title) ?></a></h4></li>
								<li><?= ucfirst($employee->first_name.' '.$employee->last_name) ?></li>
								<li><?= $employee->job_type != 'live in' ? 'Live out' : 'Live in';?>| <span class="text-warning money-amount"><?= $amount ?></span></li>
								<li>
									<a href="<?= url('/job-detail.php?wid='.$employee->worker_id) ?>" class="text-primary">view details</a>
									<span class="float-right"><i class="fa fa-clock-o text-success "></i> <span  style="font-size: 12px;" class="text-success"><?= date('d M Y', strtotime($employee->date_added)) ?></span></span>
								</li>
							</ul>
						</div>
					</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<!-- jobs end-->

	
	<?php if(count($testimonials)):?>
	<!-- Our Testimonials -->
	<section id="our-testimonials" class="our-testimonial">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-6 offset-lg-3">
					<div class="main-title text-center">
						<h3 class="mt0">What People Say</h3>
						<p>This is what our top employers had to say about us!</p>
					</div>
				</div>
			</div>

			<!-- testimonial start -->
			<?php if(count($testimonials)):?>
			<div class="row">
				<div class="col-lg-12">
					<div class="testimonial_slider_home2">
						<?php foreach($testimonials as $testimonial): 
							$t_image = $testimonial->image ?  $testimonial->image : '/images/employee/demo.png';
							$function = $testimonial->function ? implode(',', json_decode($testimonial->function, true)) : '';	
						?>
						<div class="item">
							<div class="testimonial_item home2">
								<div class="thumb">
									<img class="img-fluid rounded-circle" src="<?= asset($t_image) ?>" alt="1.jpg">
									<div class="title"><?= ucfirst($testimonial->first_name.' '.$testimonial->last_name) ?></div>
									<div class="subtitle"><?= $function ?></div>
								</div>
								<div class="details">
									<div class="icon"><span class="fa fa-quote-left"></span></div>
									<p><?= ucfirst($testimonial->comment) ?></p>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<!-- testimonial end -->
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

  

  <?php if(count($faqs)):?>
		<!-- FAQS start-->
		<div class="top-jobs-container">
		<div class="fags-header">
			<h3>Frequestly Asked Questions</h3>
			<p>You can also browse the topic bellow to find what you are loooking for.</p>
		</div>
		<div class="faqs-body-x">
			<ul>
				<?php foreach($faqs as $faq): ?>
				<li>
					<a href="#" class="faq-single-item-x"><?= $faq->faq?> <i class="fa fa-angle-right float-right angle"></a></i>
                     <div class="inner-faq">
						<h4><?= $faq->faq?></h4>
						<?= $faq->content; ?>
					</div>
			    </li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<!-- FAQS end-->
	<?php endif; ?>
	

</div>
<!-- page content end-->


	
<!-- Our Footer -->
<?php include('includes/footer.php');  ?>






<script>
$(document).ready(function(){

if($(window).width() < 993){
	// ========================================
	// ADD CUAROSELS TO STAND OUT 
	// ========================================
	var frames = $(".standout-animate");
	var circles = $(frames).parent().parent().children('.base').find('.direction');
	for(var i = 0; i < frames.length; i++){
		if(i == 0){
			$(circles).append('<i class="fa fa-circle active"></i>');
		}else{
			$(circles).append('<i class="fa fa-circle"></i>');
		}
	}


	// ========================================
	// ANIMATE AND SLIDE STANDOUT
	// ========================================
	var n = 1;
	var directions = $("#direction_x");
	$(directions).on('click', '.fa-circle', function(){
		var counter = $(this).index() + 1;
		$(directions).children().removeClass('active');
		$(this).addClass('active');
		rotate(counter);
	});


	function rotate(n){
		for(var i = 0; i < frames.length; i++){
			$(frames[i]).hide();
		}
		$(frames[n - 1]).show();
	}
	rotate(n);
// end
}







// ==============================================
// OPEN FAQS CONTENT DETAILS
// ==============================================
$(".faq-single-item-x").click(function(e){
    e.preventDefault();
	var content = $(this).parent().children('.inner-faq');
	$(content).toggle();
});






// end ready function
});
</script>