<?php
// ******** APP SETTINGS ************//
$settings = $connection->select('settings')->where('id', 1)->first();



// ********* COURSE USERS PROFILE *********** //
$course_user = $connection->select('course_users')->where('id', Auth_course::user('id'))->where('email', Auth_course::user('email'))->first();
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
	        <form action="<?= url('/courses/search.php')?>" method="" class="nav-form">
				<input type="text" name="search" class="nav-search" placeholder="search for...">
				<button class="search-btn"><i class="fa fa-search"></i></button>
		    </form>
		</div>
		<div class="nav-right"><!-- nav right start-->
			<div class="img-right">
				<i class="fa fa-bars toggle-side-navigation"></i>
				<?php if(Auth_course::is_loggedin()): ?>
					<a href="<?= url('/courses/logout')?>"><i class="fa fa-power-off text-danger"></i></a>
				<?php else: ?>
					<a href="<?= url('/courses/login')?>"><i class="fa fa-sign-in text-danger"></i></a>
				<?php endif; ?>
				<?php $profile_image = $course_user && $course_user->image ? $course_user->image : '/employee/images/demo.png' ?>
				<img src="<?= asset($profile_image) ?>" alt="name" class="nav-profile-img"></span>
			</div>
		</div><!-- nav right end-->
   </div>
</div>