


<?php
$settings = $connection->select('settings')->where('id', 1)->first();




// ********** NAVIGATION IMAGE ******************//
$profile_image = '/employee/images/demo.png';
if(Auth_employee::is_loggedin())
{
	$profile_image = Auth_employee::employee('w_image') ? Auth_employee::employee('w_image') : '/employee/images/demo.png';
}else if(Auth_employer::is_loggedin())
{
	$profile_image = Auth_employer::employer('image') ? Auth_employer::employer('image') : '/employer/images/demo.png';
}



// ************ GET SAVED WORKERS ***************//
$savedWorkers = 0;
if(Cookie::has('saved_worker'))
{
	$saved_workers = json_decode(Cookie::get('saved_worker'), true);
	$savedWorkers = count($saved_workers);
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
	        <form action="<?= current_url()?>" method="GET" class="nav-form">
				<input type="text" name="search" class="nav-search" placeholder="search for...">
				<button class="search-btn"><i class="fa fa-search"></i></button>
		    </form>
		</div>
		<div class="nav-right"><!-- nav right start-->
			<div class="img-right">
				<i class="fa fa-bars toggle-side-navigation"></i>
				<span class="saved-workers"><?= $savedWorkers ? '('.$savedWorkers.')' : '' ?></span>
				<i class="fa fa-heart text-danger"></i>
				<img src="<?= asset($profile_image) ?>" alt="name" class="nav-img"></span>
			</div>
		</div><!-- nav right end-->
   </div>
</div>