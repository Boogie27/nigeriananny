

<?php 
$user = $connection->select('users')->where('id', Auth::user('id'))->first();
$image = '/shop/images/users/demo.png';
$first_name = null;
if(Auth::is_loggedin() && $user)
{
    $image = $user->user_image ? $user->user_image : '/shop/images/users/demo.png';
    $first_name = $user->first_name;
}


//    banner 
$banner =  $connection->select('settings')->where('id', 1)->first();
?>



<!-- Main Header Nav -->
<header class="header-nav menu_style_home_one dashbord_pages navbar-scrolltofixed stricky main-menu">
		<div class="container-fluid">
		    <!-- Ace Responsive Menu -->
		    <nav>
		        <!-- Menu Toggle btn-->
		        <div class="menu-toggle">
		            <a href="<?= url('/shop'); ?>"><img class="nav_logo_img img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>"></a>
					<button type="button" id="menu-btn">
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		            </button>
		        </div>
		        <a href="<?= url('/shop'); ?>" class="navbar_brand float-left dn-smd">
		            <img class="logo1 img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <img class="logo2 img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		            <span></span>
		        </a>
		        <!-- Responsive Menu Structure-->
		        <!--Note: declare the Menu style in the data-menu-style="horizontal" (options: horizontal, vertical, accordion) -->
		        <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">
		            <li>
                    <a href="<?= url('/shop') ?>"><span class="title">Home</span></a>
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
                    <li>
		                <a href="<?= url('/shop/account') ?>"><span class="title">My account</span></a>
		            </li>
		            <li>
		                <a href="<?= url('/shop/order') ?>"><span class="title">My order</span></a>
		            </li>
					<li>
		                <a href="<?= url('/shop/cart') ?>"><span class="title">Cart</span></a>
		            </li>
		        </ul>
		        <ul class="header_user_notif pull-right dn-smd">
	                <li class="user_notif">
						<div class="dropdown">
						    <a class="notification_icon" href="<?= url('/shop/cart') ?>">
							     <span class="flaticon-shopping-bag"></span>
								 <span class="nav-cart-qty" style="font-size: 15px;"><?= Session::has('cart') ? Session::get('cart')->_totalQty : 0 ?></span>
						    </a>
						</div>
	                </li>
	                <li class="user_setting">
						<div class="dropdown">
	                		<a class="btn dropdown-toggle" href="#" data-toggle="dropdown"><img class="rounded-circle" src="<?= asset($image) ?>" alt="<?= $first_name ?>"></a>
						    <div class="dropdown-menu">
						    	<div class="user_set_header">
						    		<img class="float-left" src="<?= asset($image) ?>" alt="<?= $first_name?>">
							    	<p><?= $first_name?> <br><span class="address"><a href="https://grandetest.com/cdn-cgi/l/email-protection" class="__cf_email__"></a></span></p>
						    	</div>
						    	<div class="user_setting_content">
									<a class="dropdown-item active" href="<?= url('/shop/user-detail'); ?>">My Profile</a>
									<a class="dropdown-item" href="<?= url('/shop/reviewed'); ?>">Reviews</a>
									<a class="dropdown-item" href="<?= url('/shop/order'); ?>">Purchase history</a>
									<a class="dropdown-item" href="<?= url('/shop/cart'); ?>">Cart</a>
									<?php if(!Auth::is_loggedin()): ?>
										<a href="<?= url('/shop/login') ?>" class="dropdown-item">Login/Register</a>
									<?php else: ?>
										<a href="<?= url('/shop/logout') ?>" class="dropdown-item">Logout</a>
									<?php endif; ?>
						    	</div>
						    </div>
						</div>
			        </li>
	            </ul>
		    </nav>
		</div>
	</header>

