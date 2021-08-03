


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
$link = current_url();
$profile_image = '/employee/images/demo.png';
if(Auth_employee::is_loggedin())
{
	$link = url('/employee/account');
	$employee_profile = $connection->select('employee')->leftJoin('workers', 'employee.e_id', '=', 'workers.employee_id')->where('email', Auth_employee::employee('email'))->where('e_id', Auth_employee::employee('id'))->first();
    $profile_image = $employee_profile->w_image ? $employee_profile->w_image : '/employee/images/demo.png';
}else if(Auth_employer::is_loggedin())
{
	$link = url('/employer/account');
	$employee_profile = $connection->select('employers')->where('email', Auth_employer::employer('email'))->where('id', Auth_employer::employer('id'))->first();
    $profile_image = $employee_profile->e_image ? $employee_profile->e_image : '/employer/images/demo.png';
}





// ************ GET NOTIFICATION ****************//
$notifications = array();
if(Auth_employee::is_loggedin())
{
	$notifications = $connection->select('notifications')->where('from_user', 'employer')
	                    ->where('to_user', 'employee')->where('to_id', Auth_employee::employee('id'))->where('is_seen', 0)->orderBy('date', 'DESC')->limit(5)->get();
}else if(Auth_employer::is_loggedin())
{
    $notifications = $connection->select('notifications')->where('from_user', 'employee')
	                    ->where('to_user', 'employer')->where('to_id', Auth_employer::employer('id'))->where('is_seen', 0)->orderBy('date', 'DESC')->limit(5)->get();
}
?>

<!-- NAVIGATION-->
<div class="navigation">
    <div class="inner-navigation">
		<div class="nav-left"> <!-- nav left start-->
			<i class="fa fa-bars toggle-side-navigation"></i>
			<a href="<?= url('/') ?>">
			   <img src="<?= asset($settings->logo) ?>" alt="<?= $settings->app_name ?>" class="nav-img-img">
			</a>
		</div><!-- nav left end-->
		<div class="navigation-search">
	        <form action="<?= url('/jobs')?>" method="GET" class="nav-form">
				<input type="text" name="search" class="nav-search" placeholder="Search by title">
				<button class="search-btn"><i class="fa fa-search"></i></button>
		    </form>
		</div>
		<div class="nav-right"><!-- nav right start-->
			<div class="img-right">	
				<?php if(Auth_employee::is_loggedin()):?>
					<a href="<?= url('/job-detail.php?wid='.$employee_profile->worker_id) ?>" class="nav-right-profile-link">My profile</a>
				<?php endif; ?>
				<a href="#" id="nav_top_search_toggle"><i class="fa fa-search"></i></a>
				<?php if(Auth_employee::is_loggedin() && !$employee_profile->e_approved): ?>
				<span class="nav-alert-badge-body">
					<a href="#" class="nav-alert-badge">
						<i class="fa fa-bell-o text-danger"></i>
						<span class=""></span>
					</a>
				</span>
				<?php else: ?>
			    	<span class="nav-alert-badge-body">
						<a href="#" class="nav-alert-badge" id="notification_open_btn">
							<i class="fa fa-bell-o text-danger"></i>
							<span class="badge bg-danger"><?= count($notifications) ? count($notifications) : '' ?></span>
						</a>
					</span>
				<?php endif ?>
				<i class="fa fa-bars toggle-side-navigation"></i>
				<a href="<?= $link ?>">
					<img src="<?= asset($profile_image) ?>" alt="name" class="nav-profile-img">
				</a>
			</div>
		</div><!-- nav right end-->
   </div>
</div>


<!-- mobile search start-->
<div class="mobile-nav-search" id="mobile_nav_search_container">
	<div class="search-dark-theme" id="search_dark_theme">
	    <div class="mobile-search-form">
			<form action="<?= url('/jobs')?>" method="GET">
				<div class="inner-search-form">
					<input type="text" name="search" value="" placeholder="Search by title">
					<button type="submit"><i class="fa fa-search"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- mobile search end-->



<?php if(Auth_employee::is_loggedin()): ?>
<!-- notification start-->
<div class="notification-container">
	<div class="notification-body">
		<div class="not-cancle">
			<a href="#" id="notification_cancle_btn"><i class="fa fa-times"></i></a>
		</div>
		<div class="not-header"><h4>Notification</h4></div>
	   	<div id="notification_content_container">
		   <ul class="ul-notification client_ul_notification">
				<?php if(count($notifications)): ?>
				<?php foreach($notifications as $notification): ?>
					<li class="not-link">
						<a href="<?= url($notification->link)?>">
							<h5><?= $notification->name ?></h5>
							<p><?= $notification->body?></p>
						</a>
					</li>
				<?php endforeach; ?>
				<?php else: ?>
					No notification
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
<?php endif;?>
<!-- notification end-->

<!-- notification start-->
<?php if(Auth_employer::is_loggedin()): ?>
<div class="notification-container">
	<div class="notification-body">
		<div class="not-cancle">
			<a href="#" id="notification_cancle_btn"><i class="fa fa-times"></i></a>
		</div>
		<div class="not-header"><h4>Notification</h4></div>
	   	<div id="notification_content_container">
		   <ul class="ul-notification client_ul_notification">
				<?php if(count($notifications)): ?>
				<?php foreach($notifications as $notification): ?>
					<li class="not-link">
						<a href="<?= url($notification->link)?>">
							<h5><?= $notification->name ?></h5>
							<p><?= $notification->body?></p>
						</a>
					</li>
				<?php endforeach; ?>
				<?php else: ?>
					No notification
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
<?php endif;?>
<!-- notification end-->





