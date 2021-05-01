<?php
$settings = $connection->select('settings')->where('id', 1)->first();
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
	        <form action="<?= current_url()?>" method="" class="nav-form">
				<input type="text" name="search" class="nav-search" placeholder="search for...">
				<button class="search-btn"><i class="fa fa-search"></i></button>
		    </form>
		</div>
		<div class="nav-right"><!-- nav right start-->
			<div class="img-right">
				<i class="fa fa-bars toggle-side-navigation"></i>
				<i class="fa fa-heart text-danger"></i>
				<img src="<?= asset('/employee/images/demo.png') ?>" alt="name" class="nav-img"></span>
			</div>
		</div><!-- nav right end-->
   </div>
</div>