


<?php
$settings = $connection->select('settings')->where('id', 1)->first();



// ************ GET SAVED WORKERS ***************//
$savedWorkers = 0;
if(Cookie::has('saved_worker'))
{
	$saved_workers = json_decode(Cookie::get('saved_worker'), true);
	$savedWorkers = count($saved_workers);
}


// ********** NAVIGATION IMAGE ******************//
$profile_image = '/employee/images/demo.png';
if(Auth_employee::is_loggedin())
{
	$employee_profile = $connection->select('employee')->leftJoin('workers', 'employee.e_id', '=', 'workers.employee_id')->where('email', Auth_employee::employee('email'))->where('e_id', Auth_employee::employee('id'))->first();
    $profile_image = $employee_profile->w_image ? $employee_profile->w_image : '/employee/images/demo.png';
}else if(Auth_employer::is_loggedin())
{
	$employee_profile = $connection->select('employers')->where('email', Auth_employer::employer('email'))->where('id', Auth_employer::employer('id'))->first();
    $profile_image = $employee_profile->e_image ? $employee_profile->e_image : '/employer/images/demo.png';
}

?>

<!-- NAVIGATION-->
<div class="navigation">
   <div class="inner-navigation">
		<div class="nav-left"> <!-- nav left start-->
			<i class="fa fa-bars toggle-side-navigation"></i>
			<a href="<?= url('/') ?>">
			   <img src="<?= asset($settings->logo) ?>" alt="<?= $settings->app_name ?>" class="nav-img">
			   <span class="nav-app-name"><?= $settings->app_name ?></span>
			</a>
		</div><!-- nav left end-->
		<div class="navigation-search">
	        <form action="<?= url('/jobs')?>" method="GET" class="nav-form">
				<input type="text" name="search" class="nav-search" placeholder="search by title">
				<button class="search-btn"><i class="fa fa-search"></i></button>
		    </form>
		</div>
		<div class="nav-right"><!-- nav right start-->
			<div class="img-right">
				<i class="fa fa-bars toggle-side-navigation"></i>
				<!-- <i class="fa fa-search"></i> -->
				<?php if(Auth_employee::is_loggedin()):?>
					<a href="<?= url('/job-detail.php?wid='.$employee_profile->worker_id) ?>" class="nav-right-profile-link">My profile</a>
				<?php endif; ?>
				<a href="#" class="nav-alert-badge">
					<span class=""></span>
					<i class="fa fa-bell-o text-danger"></i>
				</a>
				<img src="<?= asset($profile_image) ?>" alt="name" class="nav-profile-img"></span>
			</div>
		</div><!-- nav right end-->
   </div>
</div>

