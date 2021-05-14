
<?php
//    banner 
$banner =  $connection->select('settings')->where('id', 1)->first();





// ********* GET NOTIFICATION *****************//
$nav_nots = $connection->select('notifications')->where('to_id', 1)->where('to_user', 'admin')->where('is_seen', 0)->orderBy('date', 'DESC')->limit(6)->get();




?>

	<header class="header-nav menu_style_home_one dashbord_pages navbar-scrolltofixed stricky main-menu">
		<div class="container-fluid">
		    <!-- Ace Responsive Menu -->
		    <nav>
		        <!-- Menu Toggle btn-->
		        <div class="menu-toggle">
		            <img class="nav_logo_img img-fluid navi-top-img" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <button type="button" id="menu-btn">
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		            </button>
		        </div>
		        <a href="#" class="navbar_brand float-left dn-smd">
		            <img class="logo1 img-fluid navi-top-img" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <img class="logo2 img-fluid navi-top-img" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <span><?= $banner->app_name ?></span>
		        </a>
		        <!-- Responsive Menu Structure-->
		        <!--Note: declare the Menu style in the data-menu-style="horizontal" (options: horizontal, vertical, accordion) -->
		        <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">
		            <li>
		                <a href="<?= url('/admin') ?>"><span class="title">Dashborad</span></a>
		            </li>
					<li>
		                <a href="#"><span class="title">Categories</span></a>
						<!-- Level Two-->
						<?php $categories = $connection->select('shop_categories')->where('is_category_feature', 1)->limit(6)->get();
						if(count($categories)):
						?>
						<ul>
							<?php foreach($categories as $category):?>
		                    <li>
								<a href="<?= url('/shop/category.php?category='.$category->category_slug.'&cid='.$category->category_id); ?>"><?= $category->category_name ?></a>
								<?php $subCategories = $connection->select('shop_subcategories')->where('shop_categories_id', $category->category_id)->where('shop_subCategory_isFeature', 1)->limit(6)->get(); 
								if(count($subCategories)):
								?>
		                        <ul>
								    <?php foreach($subCategories as $subCategory):?>
									<li><a href="<?= url('/shop/category.php?category='.$category->category_slug.'&cid='.$category->category_id.'&scid='.$subCategory->shop_subCategory_id); ?>"><?= $subCategory->shop_subCategory_name ?></a></li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>
		                    </li>
                            <?php endforeach; ?>
						</ul>
						<?php endif; ?>
		            </li>
		            </li>
					<li>
		                <a href="#"><span class="title">Others</span></a>
		                <ul>
							<li><a href="<?= url('/admin-nanny/faq') ?>">FAQ</a></li>
							<li><a href="<?= url('/admin-nanny/about') ?>">About us</a></li>
							<li><a href="<?= url('/admin-nanny/privacy') ?>">Privacy</a></li>
							<li><a href="<?= url('/admin-nanny/terms') ?>">Terms & condition</a></li>
							
		                </ul>
					</li>
					<li>
		                <a href="#"><span class="title">Settings</span></a>
		                <ul>
		                    <li><a href="<?= url('/admin/general-settings') ?>">General settings</a></li>
							<li><a href="<?= url('/admin/email-settings') ?>">Email settings</a></li>
							<li><a href="<?= url('/admin/banner-settings') ?>">Banner settings</a></li>
		                </ul>
					</li>
					<li class="last">
		                <a href="<?= url('/admin/logout') ?>"><span class="title">Logout</span></a>
					</li>
		        </ul>
		        <ul class="header_user_notif pull-right dn-smd">
				    <li class="user_notif">
						<div class="dropdown">
						    <a class="notification_icon <?= count($nav_nots) ? 'flaticon-not-count' : '' ?>" href="#" data-toggle="dropdown">
								<span class="flaticon-alarm"></span>
							</a>
						    <div class="dropdown-menu notification_dropdown_content">
								<div class="so_heading">
									<p>Notifications</p>
								</div>
								<div class="so_content" data-simplebar="init">
									<ul>
									<?php if(count($nav_nots)): 
									foreach($nav_nots as $notification):
										?>
											<li class="nav-not-link">
												<a href="<?= url($notification->link) ?>">
													<h5><?= $notification->name ?></h5>
													<p><?= $notification->body ?></p>
												</a>
											</li>
										<?php endforeach; ?>
									<?php else: ?>
                                         <li>No notifications yet!</li>
									<?php endif; ?>
									</ul>
								</div>
								<?php if(count($nav_nots)):  ?>
								<a class="view_all_noti text-thm" href="<?= url('/admin/notification') ?>">View all alerts</a>
								<?php endif; ?>
						    </div>
						</div>
	                </li>
	                <li class="user_setting">
						<div class="dropdown">
	                		<a class="btn dropdown-toggle" href="#" data-toggle="dropdown"><img class="rounded-circle" src="<?= asset(Admin_auth::admin('image')) ?> " alt="<?= Admin_auth::admin('first_name') ?>"></a>
						    <div class="dropdown-menu">
						    	<div class="user_set_header">
						    		<img class="float-left" src="<?= asset(Admin_auth::admin('image')) ?>" alt="<?= Admin_auth::admin('first_name') ?>">
							    	<p><?= Admin_auth::admin('first_name') ?></p>
						    	</div>
						    	<div class="user_setting_content">
									<a class="dropdown-item active" href="<?= url('/admin/profile') ?>">My Profile</a>
									<a class="dropdown-item" href="<?= url('/admin-nanny/change-password') ?>">Change password</a>
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