
<?php
// =====================================
//    GET APP BANNER
// =====================================
$banner =  $connection->select('settings')->where('id', 1)->first();


// =====================================
//    GET MESSAGE
// =====================================
$new_messages = $connection->select('contact_us')->where('is_seen', 0)->get();
?>

	<header class="header-nav menu_style_home_one dashbord_pages navbar-scrolltofixed stricky main-menu">
		<div class="container-fluid">
		    <!-- Ace Responsive Menu -->
		    <nav>
		        <!-- Menu Toggle btn-->
		        <div class="menu-toggle">
		            <img class="nav_logo_img img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <button type="button" id="menu-btn">
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		            </button>
		        </div>
		        <a href="<?= url('/') ?>" class="navbar_brand float-left dn-smd">
		            <img class="logo1 img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <img class="logo2 img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <span><?= $banner->app_name ?></span>
		        </a>
		        <!-- Responsive Menu Structure-->
		        <!--Note: declare the Menu style in the data-menu-style="horizontal" (options: horizontal, vertical, accordion) -->
		        <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">
					<li>
		                <a href="#"><span class="title">Categories</span></a>
						<!-- Level Two-->
						<?php $categories = $connection->select('job_categories')->where('is_category_featured', 1)->limit(8)->get(); 
						if(count($categories)):
						?>
						<ul>
							<?php foreach($categories as $category):?>
		                    <li>
								<a href="<?= url('/jobs.php?category='.$category->category_slug) ?>"><?= $category->category_name ?></a>
		                    </li>
                            <?php endforeach; ?>
						</ul>
						<?php endif; ?>
		            </li>
					</li>
					<li>
		                <a href="#"><span class="title">News letter</span></a>
		                <ul>
							<li><a href="<?= url('/admin-nanny/news-letters') ?>">News letter</a></li>
							<li><a href="<?= url('/admin-nanny/employer-newsletter') ?>">Employer news letter</a></li>
							<li><a href="<?= url('/admin-nanny/employee-newsletter') ?>">Employee news letter</a></li>
		                </ul>
					</li>
					<li>
		                <a href="#"><span class="title">Others</span></a>
		                <ul>
							<li><a href="<?= url('/admin-nanny/faq') ?>">FAQ</a></li>
		                </ul>
					</li>
					<li>
		                <a href="#"><span class="title">Settings</span></a>
		                <ul>
		                    <li><a href="<?= url('/admin-nanny/general-settings') ?>">General settings</a></li>
							<li><a href="<?= url('/admin-nanny/email-settings') ?>">Email settings</a></li>
							<li><a href="<?= url('/admin-nanny/banner-settings') ?>">Banner settings</a></li>
		                </ul>
					</li>
					<li class="last">
		                <a href="<?= url('/admin/logout') ?>"><span class="title">Logout</span></a>
					</li>
		        </ul>
		        <ul class="header_user_notif pull-right dn-smd">
	                <li class="user_setting">
						<div class="dropdown">
	                		<a class="btn dropdown-toggle" href="#" data-toggle="dropdown"><img class="rounded-circle" src="<?= asset(Admin_auth::admin('image')) ?>" alt="<?= Admin_auth::admin('first_name') ?>"></a>
						    <div class="dropdown-menu">
						    	<div class="user_set_header">
						    		<img class="float-left" src="<?= asset(Admin_auth::admin('image')) ?>" alt="<?= Admin_auth::admin('first_name') ?>">
							    	<p><?= Admin_auth::admin('first_name') ?></p>
						    	</div>
						    	<div class="user_setting_content">
									<a class="dropdown-item active" href="<?= url('/admin/profile') ?>">My Profile</a>
									<a class="dropdown-item" href="<?= url('/admin-nanny/message') ?>">Messages <span class="text-danger" style="font-size: 13px;"><?= $new_messages ? '('.count($new_messages).')' : ''?> </span></a>
									<a class="dropdown-item" href="<?= url('/admin/transactions') ?>">Transactions</a>
									<a class="dropdown-item" href="<?= url('/admin/general-settings') ?>">Settings</a>
									<a class="dropdown-item" href="<?= url('/admin/logout') ?>">Log out</a>
						    	</div>
						    </div>
						</div>
			        </li>
	            </ul>
		    </nav>
		</div>
	</header>