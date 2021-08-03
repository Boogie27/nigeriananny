

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



	<!-- Main Header Nav For Mobile -->
	<div id="page" class="stylehome1 h0">
		<div class="mobile-menu">
	        <ul class="header_user_notif dashbord_pages_mobile_version pull-right">
                <li class="user_setting">
					<div class="dropdown">
                		<a class="btn dropdown-toggle" href="#" data-toggle="dropdown"><img class="rounded-circle" src="<?= asset($image); ?>" alt="<?= $first_name ?>"></a>
					    <div class="dropdown-menu">
					    	<div class="user_set_header">
					    		<img class="float-left" src="<?= asset($image); ?>" alt="<?= $first_name ?>">
						    	<p><?= $first_name ?> <br><span class="address"><a href="https://grandetest.com/cdn-cgi/l/email-protection" class="__cf_email__" ></a></span></p>
					    	</div>
					    	<div class="user_setting_content">
								<a class="dropdown-item active" href="<?= url('/shop/account'); ?>">My account</a>
								<a class="dropdown-item" href="<?= url('/shop/order'); ?>">My order</a>
								<a class="dropdown-item" href="<?= url('/shop/order.php') ?>">Purchase history</a>
								<?php if(Auth::is_loggedin()): ?>
									<a class="dropdown-item" href="<?= url('/shop/logout'); ?>">Log out</a>
								<?php else: ?>
									<a class="dropdown-item" href="<?= url('/shop/login'); ?>">Login</a>
									<a class="dropdown-item" href="<?= url('/shop/register'); ?>">Register</a>
								<?php endif; ?>
								
					    	</div>
					    </div>
					</div>
		        </li>
            </ul>
			<div class="header stylehome1 dashbord_mobile_logo dashbord_pages">
				<div class="main_logo_home2">
		            <img class="nav_logo_img img-fluid float-left mt20 navi-top-img" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
		           
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
					<?php $categories = $connection->select('shop_categories')->where('is_category_feature', 1)->limit(6)->get();
						if(count($categories)):
							foreach($categories as $category):
						?>
						<li><a href="<?= url('/shop/category.php?category='.$category->category_slug.'&cid='.$category->category_id); ?>"><span><?= $category->category_name ?></span></a>
							<ul>
							<?php $subCategories = $connection->select('shop_subcategories')->where('shop_categories_id', $category->category_id)->where('shop_subCategory_isFeature', 1)->limit(6)->get(); 
								if(count($subCategories)):
									foreach($subCategories as $subCategory):
								?>
	                           <li><a href="<?= url('/shop/category.php?category='.$category->category_slug.'&cid='.$category->category_id.'&scid='.$subCategory->shop_subCategory_id); ?>"><?= $subCategory->shop_subCategory_name ?></a></li>
							<?php 
								endforeach;
								endif; ?>
							</ul>
						</li>
						<?php 
					endforeach;
					endif; ?>
					</ul>
				</li>
				<li>
	              <a href="<?= url('/shop/order') ?>"><span>My order</span></a>
				</li>
				<li>
	              <a href="<?= url('/shop/account') ?>"><span>My account</span></a>
				</li>
				<li><a href="<?= url('/shop/cart'); ?>"><span class="fa fa-shopping-cart"></span> Cart</a></li>
				<li><a href="<?= url('/'); ?>"><span class="fa fa-briefcase"></span> Find a worker</a></li>
				<li><a href="<?= url('/courses'); ?>"><span class="fa fa-video-camera"></span> Courses</a></li>
					<?php if(Auth::is_loggedIn()): ?>
					<li><a href="<?= url('/shop/logout') ?>"><span class="fa fa-sign-out"></span> Logout</a></li>
				<?php else: ?>
					<li><a href="<?= url('/shop/login') ?>"><span class="flaticon-user"></span> Login</a></li>
					<li><a href="<?= url('/shop/register') ?>"><span class="fa fa-sign-in"></span> Register</a></li>
                <?php endif; ?>
			</ul>
			
		</nav>
	</div>
