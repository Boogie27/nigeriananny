<?php
// app banner settings
$banner =  $connection->select('settings')->where('id', 1)->first();

?>

	<div id="page" class="stylehome1 h0">
		<div class="mobile-menu">
	        <ul class="header_user_notif dashbord_pages_mobile_version pull-right">
                <li class="user_notif">
					<div class="dropdown">
					    <a class="notification_icon" href="#" data-toggle="dropdown"><span class="flaticon-email"></span></a>
					    <div class="dropdown-menu notification_dropdown_content">
							<div class="so_heading">
								<p>Notifications</p>
							</div>
							<!-- <div class="so_content" data-simplebar="init">
								<ul>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
								</ul>
							</div> -->
							<a class="view_all_noti text-thm" href="#">View all alerts</a>
					    </div>
					</div>
                </li>
                <li class="user_notif">
					<div class="dropdown">
					    <a class="notification_icon" href="#" data-toggle="dropdown"><span class="flaticon-alarm"></span></a>
					    <div class="dropdown-menu notification_dropdown_content">
							<div class="so_heading">
								<p>Notifications</p>
							</div>
							<!-- <div class="so_content" data-simplebar="init">
								<ul>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
									<li>
										<h5>Status Update</h5>
										<p>This is an automated server response message. All systems are online.</p>
									</li>
								</ul>
							</div> -->
							<a class="view_all_noti text-thm" href="#">View all alerts</a>
					    </div>
					</div>
                </li>
                <li class="user_setting">
					<div class="dropdown">
                		<a class="btn dropdown-toggle" href="#" data-toggle="dropdown"><img class="rounded-circle" src="<?= asset(Admin_auth::admin('image')) ?>" alt="<?= Admin_auth::admin('first_name') ?>"></a>
					    <div class="dropdown-menu">
					    	<div class="user_set_header">
					    		<img class="float-left" src="<?= asset(Admin_auth::admin('image')) ?>" alt="<?= Admin_auth::admin('first_name') ?>">
						    	<p><?= Admin_auth::admin('first_name') ?><br><span class="address"></span></p>
					    	</div>
					    	<div class="user_setting_content">
								<a class="dropdown-item active" href="<?= url('/admin/profile') ?>">My Profile</a>
								<a class="dropdown-item" href="<?= url('/admin/settings') ?>">Settings</a>
								<a class="dropdown-item" href="<?= url('/admin/logout') ?>">Log out</a>
					    	</div>
					    </div>
					</div>
		        </li>
            </ul>
			<div class="header stylehome1 dashbord_mobile_logo dashbord_pages">
				<div class="main_logo_home2">
		            <img class="nav_logo_img img-fluid float-left mt20 navi-top-img" src="<?=asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <span><?= $banner->app_name ?></span>
				</div>
				<ul class="menu_bar_home2">
					<li class="list-inline-item"></li>
					<li class="list-inline-item"><a href="#menu"><span></span></a></li>
				</ul>
			</div>
		</div><!-- /.mobile-menu -->
		<nav id="menu" class="stylehome1">
			<ul>
			    <li>
	              <a href="<?= url('/shop') ?>"><span>Home</span></a>
				</li>
				<li><span>Categories</span>
					<ul>
					<?php $categories = $connection->select('job_categories')->where('is_category_featured', 1)->limit(10)->get(); 
						if(count($categories)):
							foreach($categories as $category):
						?>
						<li><a href="<?= url('/jobs.php?category='.$category->category_slug) ?>"><span><?= $category->category_name ?></span></a></li>
						<?php 
						endforeach;
					endif; ?>
					</ul>
				</li>
				<li><span>Newsletters</span>
					<ul>
						<li><a href="<?= url('/admin-nanny/news-letters') ?>">News letter</a></li>
						<li><a href="<?= url('/admin-nanny/employer-newsletter') ?>">Employer news letter</a></li>
						<li><a href="<?= url('/admin-nanny/employee-newsletter') ?>">Employee news letter</a></li>
					</ul>
				</li>
				<li><span>Others</span>
					<ul>
					    <li><a href="<?= url('/admin-nanny/faq') ?>">FAQ</a></li>
						<li><a href="<?= url('/admin-nanny/about') ?>">About us</a></li>
						<li><a href="<?= url('/admin-nanny/privacy') ?>">Privacy</a></li>
						<li><a href="<?= url('/admin-nanny/terms') ?>">Terms & condition</a></li>
					</ul>
				</li>
				<li><span>Settings</span>
					<ul>
						<li><a href="<?= url('/admin-nanny/general-settings') ?>">General settings</a></li>
						<li><a href="<?= url('/admin-nanny/email-settings') ?>">Email settings</a></li>
						<li><a href="<?= url('/admin-nanny/banner-settings') ?>">Banner settings</a></li>
					</ul>
				</li>
				<li><span>Others</span>
					<ul>
						<li><a href="<?= url('/admin-nanny/faq') ?>">FAQ</a></li>
					</ul>
				</li>
				<!-- <li><span>Courses</span>
					<ul>
						<li><span>Courses List</span>
							<ul>
	                            <li><a href="page-course-v1.html">Courses v1</a></li>
	                            <li><a href="page-course-v2.html">Courses v2</a></li>
	                            <li><a href="page-course-v3.html">Courses v3</a></li>
							</ul>
						</li>
						<li><span>Courses Single</span>
							<ul>
	                            <li><a href="page-course-single-v1.html">Single V1</a></li>
	                            <li><a href="page-course-single-v2.html">Single V2</a></li>
	                            <li><a href="page-course-single-v3.html">Single V3</a></li>
							</ul>
						</li>
                        <li><a href="page-instructors.html">Instructors</a></li>
		                <li><a href="page-instructors-single.html">Instructor Single</a></li>
					</ul>
				</li> -->
				<li><a href="<?= url('/admin/logout.php') ?>"><span class="fa fa-sign-out"></span> Logout</a></li>
			</ul>
		</nav>
	</div>