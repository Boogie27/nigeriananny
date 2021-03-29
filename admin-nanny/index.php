<?php include('../Connection.php');  ?>
<?php

// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$employees = $connection->select('employee')->get();



// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$employers = $connection->select('employers')->get();


// ===========================================
// GET ALL SUBSCRITPION AMOUNT MADE
// ===========================================
$total_amount = 0;
$employer_subscriptions = $connection->select('employer_subscriptions')->get();
if(count($employer_subscriptions))
{
	foreach($employer_subscriptions as $subscriptions)
	{
		$total_amount += $subscriptions->s_amount;
	}
}





// ===========================================
// GET ALL ONLINE USERS
// ===========================================
$active_employers = $connection->select('employers')->where('e_active', 1)->get();
$active_employee = $connection->select('employee')->where('is_active', 1)->get();
$total_active = count($active_employee) + count($active_employers);
?>






<?php include('includes/header.php'); ?>

<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>

<!-- Main Header Nav For Mobile -->
<?php include('includes/mobile-navigation.php') ?>


<!-- Our Dashbord Sidebar -->
<?php include('includes/side-navigation.php') ?>


	<!-- Our Dashbord -->
	<div class="our-dashbord dashbord">
		<div class="dashboard_main_content">
			<div class="container-fluid">
				<div class="main_content_container">
					<div class="row">
                        <div class="col-lg-12">
							<?php include('includes/mobile-drop-nav.php') ?><!-- mobile-navigation -->
						</div>
						<div class="col-lg-12">
							<nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
								<h4 class="title float-left">Dashboard</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="#">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
								</ol>
							</nav>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one">
								<div class="icon"><span class="fa fa-users"></span></div>
								<div class="detais">
									<p>Employees</p>
									<div class="timer"><?= count($employees)?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style2">
								<div class="icon"><span class="fa fa-briefcase"></span></div>
								<div class="detais">
									<p>Employers</p>
									<div class="timer"><?= count($employers)?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style3">
								<div class="icon"><span class="flaticon-online-learning"></span></div>
								<div class="detais">
									<p>Active users</p>
									<div class="timer"><?= $total_active ?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style4">
								<div class="icon"><span class="fa fa-money"></span></div>
								<div class="detais">
									<p>Income</p>
									<div class="dash-amount"><h3><?= money($total_amount) ?></h3></div>
								</div>
							</div>
						</div>
						<div class="col-xl-8">
							<div class="application_statics">
								<h4>Your Profile Views</h4>
								<div class="c_container"></div>
							</div>
						</div>
						<div class="col-xl-4">
							<div class="recent_job_activity">
								<h4 class="title">Notifications</h4>
								<div class="grid">
									<ul>
										<li><div class="title">Status Update</div></li>
										<li><p>This is an automated server response message. All systems are online.</p></li>
									</ul>
								</div>
								<div class="grid">
									<ul>
										<li><div class="title">Status Update</div></li>
										<li><p>This is an automated server response message. All systems are online.</p></li>
									</ul>
								</div>
								<div class="grid">
									<ul>
										<li><div class="title">Status Update</div></li>
										<li><p>This is an automated server response message. All systems are online.</p></li>
									</ul>
								</div>
								<div class="grid">
									<ul>
										<li><div class="title">Status Update</div></li>
										<li><p>This is an automated server response message. All systems are online.</p></li>
									</ul>
								</div>
								<div class="grid mb0">
									<ul class="pb0 mb0 bb_none">
										<li><div class="title">Status Update</div></li>
										<li><p>This is an automated server response message. All systems are online.</p></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt50 mb50">
						<div class="col-lg-6 offset-lg-3">
							<div class="copyright-widget text-center">
								<p class="color-black2">Copyright Edumy © 2019. All Rights Reserved.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>



<!-- footer -->
<?php  include('includes/footer.php') ?>