<?php include('../Connection.php');  ?>
<?php
//    banner 
$banner =  $connection->select('settings')->where('id', 1)->first();
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
		        <a href="#" class="navbar_brand float-left dn-smd">
		            <img class="logo1 img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <img class="logo2 img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <span><?= $banner->app_name ?></span>
		        </a>
		        <!-- Responsive Menu Structure-->
		        <!--Note: declare the Menu style in the data-menu-style="horizontal" (options: horizontal, vertical, accordion) -->
		        <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">
		            <li>
		                <a href="<?= url('/admin/index.php') ?>"><span class="title">Dashborad</span></a>
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
		            <!-- <li>
		                <a href="#"><span class="title">Events</span></a>
		                <ul>
		                    <li><a href="page-event.html">Event List</a></li>
		                    <li><a href="page-event-single.html">Event Single</a></li>
		                </ul>
		            </li> -->
		            
		            <li class="last">
		                <a href="#"><span class="title">Dispatcher</span></a>
		            </li>
					<li class="last">
		                <a href="<?= url('/admin/logout.php') ?>"><span class="title">Logout</span></a>
		            </li>
		        </ul>
		        <ul class="header_user_notif pull-right dn-smd">
	                <li class="user_notif">
						<div class="dropdown">
						    <a class="notification_icon" href="#" data-toggle="dropdown"><span class="flaticon-email"></span></a>
						    <div class="dropdown-menu notification_dropdown_content">
								<div class="so_heading">
									<p>Notifications</p>
								</div>
								<div class="so_content" data-simplebar="init">
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
								</div>
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
								<div class="so_content" data-simplebar="init">
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
								</div>
								<a class="view_all_noti text-thm" href="#">View all alerts</a>
						    </div>
						</div>
	                </li>
	                <li class="user_setting">
						<div class="dropdown">
	                		<a class="btn dropdown-toggle" href="#" data-toggle="dropdown"><img class="rounded-circle" src="<?= asset('/admin/images/admin-img/1.png') ?>" alt="e1.png"></a>
						    <div class="dropdown-menu">
						    	<div class="user_set_header">
						    		<img class="float-left" src="<?= asset('/admin/images/admin-img/1.png') ?>" alt="e1.png">
							    	<p>Kim Hunter <br><span class="address"><a href="https://grandetest.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="89e2e0e4e1fce7fdecfbc9eee4e8e0e5a7eae6e4">[email&#160;protected]</a></span></p>
						    	</div>
						    	<div class="user_setting_content">
									<a class="dropdown-item active" href="#">My Profile</a>
									<a class="dropdown-item" href="#">Messages</a>
									<a class="dropdown-item" href="#">Purchase history</a>
									<a class="dropdown-item" href="#">Help</a>
									<a class="dropdown-item" href="#">Log out</a>
						    	</div>
						    </div>
						</div>
			        </li>
	            </ul>
		    </nav>
		</div>
	</header>