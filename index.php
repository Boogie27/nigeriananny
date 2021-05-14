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
	
	





	// LOGIN USER IN COURSE
	if(Session::has('course_facebook_login'))
	{
		$course_login = Input::facebook_course_login($user_data['email']);
		if($course_login == 'deactivated')
		{
			Session::delete('facebook_auth');
			Session::flash('error', '*This account has been deactivated, please contact the admin.');
			return view('/courses/login');
		}else if($course_login == 'login'){
			Session::delete('facebook_auth');
			Session::flash('success', 'You have loggedin successfully!');
			return view('/courses');
		}else{
			Session::delete('facebook_auth');
			Session::delete('old_url');
			Session::flash('success', 'You have loggedin successfully!');
			return view($course_login);
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




		// LOGIN AS COURSE USER
		if(Session::has('course_google_login'))
		{
			$course_login = Input::course_google_login($data['email']);
			if($course_login == 'deactivated')
			{
				Session::delete('google_auth');
				Session::flash('error', '*This account has been deactivated, please contact the admin.');
                return view('/courses/login');
			}else if($course_login == 'login'){
				Session::delete('google_auth');
				Session::flash('success', 'You have loggedin successfully!');
				return view('/courses');
			}else{
				Session::delete('old_url');
				Session::delete('google_auth');
				Session::flash('success', 'You have loggedin successfully!');
				return view($course_login);
			}
		}
	}
}



// ==================================
// GET FEATURED EMPLOYEES
// ==================================
$employees = $connection->select('employee')->leftJoin('workers', 'employee.e_id', '=', 'workers.employee_id')->where('is_flagged', 0)->where('e_is_deactivate', 0)->where('is_feature', 1)->where('e_approved', 1)->limit(9)->get();

// ==================================
// GET TOP EMPLOYEES
// ==================================
$workers = $connection->select('workers')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('is_job_feature', 1)->where('is_top', 1)->where('employee.is_feature', 1)->where('employee.e_approved', 1)->where('is_flagged', 0)->where('employee.e_is_deactivate', 0)->random()->limit(9)->get();


$testimoials = $connection->select('testimonial')->where('is_featured', 1)->get();
?>

<?php include('includes/header.php');  ?>


<!--  navigation-->
<?php include('includes/navigation.php');  ?>

<?php include('includes/side-navigation.php');  ?>

<?php include('includes/slider.php');  ?>





<div class="body-content">
    
    <?php if(count($workers)): ?>
	<div class="content-one"> <!-- top content start -->
	    <div class="page-alert-x">
		    <?php if(Session::has('success')): ?>
                <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div>
            <?php endif; ?>
		</div>
        <div class="text-center title home-title"><h3>Top Employee in Nigeria <span>Nanny</span></h3></div>
        <div class="content-body"> 
           <div class="row">
               <?php foreach($workers as $worker): 
                $w_image = $worker->w_image ?  $worker->w_image : '/employee/images/demo.png';
                $amount = !$worker->amount_to ? money($worker->amount_form) : money($worker->amount_form).' - '.money($worker->amount_to);
                $location = $worker->job_type != 'live in' ? json_decode($worker->job_type, true) : null;
                ?>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                   <div class="inner-content flex">
                       <a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>">
                            <img src="<?= asset($w_image)?>" alt="<?= $worker->first_name?>" class="inner-img">
                       </a>
                       <ul class="ul-content">
                            <li><h4><a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>"><?= ucfirst($worker->job_title) ?></a></h4></li>
                            <li><?= ucfirst($worker->first_name.' '.$worker->last_name) ?></li>
                            <li><?= $worker->job_type != 'live in' ? 'Live out | '.$location['state'] : 'Live in';?></li>
                            <li><span class="text-warning"><?= $amount ?></span> <span class="float-right"><?= date('d M Y', strtotime($worker->date_added)) ?></span></li>
                        </ul>
                   </div>
                </div>
                <?php endforeach; ?>
           </div>
        </div>
     </div> <!-- top content end -->
    <?php endif; ?>

    
    <!-- content two start -->
    <div class="content-two">
        <div class="content-two-body">
            <div class="one-img">
                <img src="<?= asset('/images/banner/8.png')?>" alt="" class="one-image-left">
            </div>
            <ul class="ul-content-two">
                <li><h3>Find the right domestic staff <br>in Nigeria</h3></li>
                <li>NNC offers what you need</li>
                <li class="text-center create-btn">
                    <a href="<?= url('/employer/register')?>" class="btn-fill">Create account</a>
                </li>
            </ul>
            <div class="two-img fade-right-container">
                <img src="<?= asset('/images/banner/8.png')?>" alt="" class="two-image-right">
            </div>
        </div>
    </div>
    <!-- content two start -->

     <!-- content two start -->
     <div class="content-two two">
        <div class="content-two-body">
            <div class="three-img">
                <img src="<?= asset('/images/banner/7.png')?>" alt="">
            </div>
            <ul class="ul-content-two">
                <li><h3>Get Hired By The Right Employer <br>in Nigeria</h3></li>
                <li>
                    <p>as quality of CVs seems to get us what we need, at the right time - so there’s
                        and most times you don’t get the best from that. I will definitely use nigeri nanny</p>
                </li>
                <li class="text-center create-btn">
                    <a href="<?= url('/employee/register')?>" class="btn-fill">Create account</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- content two start -->

   
    <?php if(count($employees)): ?>
     <div class="content-one"> <!-- top content start -->
        <div class="text-center title"><h3>Featured employees</h3></div>
        <div class="content-body"> 
           <div class="row">
               <?php foreach($employees as $employee): 
                $w_image = $employee->w_image ?  $employee->w_image : '/employee/images/demo.png';
                $amount = !$employee->amount_to ? money($employee->amount_form) : money($employee->amount_form).' - '.money($employee->amount_to);
                $location = $employee->job_type != 'live in' ? json_decode($employee->job_type, true) : null;
                ?>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                    <div class="inner-content flex">
                        <a href="<?= url('/job-detail.php?wid='.$employee->worker_id) ?>">
                            <img src="<?= asset($w_image)?>" alt="<?= $employee->first_name?>" class="inner-img">
                        </a>
                        <ul class="ul-content">
                            <li><h4><a href="<?= url('/job-detail.php?wid='.$employee->worker_id) ?>"><?= ucfirst($employee->job_title) ?></a></h4></li>
                            <li><?= ucfirst($employee->first_name.' '.$employee->last_name) ?></li>
                            <li><?= $employee->job_type != 'live in' ? 'Live out | '.$location['state'] : 'Live in';?></li>
                            <li><span class="text-warning"><?= $amount ?></span> <span class="float-right"><?= date('d M Y', strtotime($employee->date_added)) ?></span></li>
                        </ul>
                    </div>
                </div>
                <?php endforeach; ?>
           </div>
        </div>
     </div> <!-- top content end -->
    <?php endif; ?>


	<div class="services-offer">
		<div class="content-two">
			<div class="content-two-body">
				<div class="one-img">
					<img src="<?= asset('/images/banner/6.png')?>" class="services-img" alt="">
				</div>
				<ul class="ul-content-two">
					<li><h3>What Services We Offer <br>in Nigeria Nanny</h3></li>
					<li>
						<p style="color: #fff;">as quality of CVs seems to get us what we need, at the right time - so there’s
							and most times <br>you don’t get the best from that. I will definitely use nigeria nanny</p>
					</li>
					<li class="create-btn">
						<a href="<?= url('/how-it-works')?>" class="btn-fill">How it works</a>
					</li>
				</ul>
				<div class="two-img fade-right-container">
					<img src="<?= asset('/images/banner/6.png')?>" class="services-img" alt="">
				</div>
			</div>
		</div>
	</div>


	<!-- testimial start -->
	<?php if(count($testimoials)):?>
    <div class="testimonial-container">
		<div class="testimoial-header">
			<h4>What people said about <br>Nigeria nanny</h4>
		</div>
		<div class="row">
			<?php foreach($testimoials as $testimoial): 
			$title = implode(',', json_decode($testimoial->function, true));
			$t_image = $testimoial->image ? $testimoial->image : '/images/testimonial/demo.png';	
			?>
			<div class="col-xl-4 col-lg-6 col-md-12">
                 <div class="testimonial-body">
					  <div class="testimoial-img">
							<img src="<?= asset($t_image) ?>" alt="<?=$testimoial->first_name ?>">
					   </div>
					   <ul>
						   <li><h4><?= ucfirst($testimoial->last_name.' '.$testimoial->first_name)?></h4></li>
						   <li class="title"><?= $title ?></li>
						   <li>
							  <p><?= $testimoial->comment ?></p>
							</li>
					   </ul>
				 </div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
	<!-- testimonial end -->
	

	

    <!-- news letter -->
    <?php include('includes/news-letter.php') ?>

</div>



<!-- Our Footer -->
<?php include('includes/footer.php');  ?>