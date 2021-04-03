<?php
$settings = $connection->select('settings')->where('id', 1)->first();
?>



	<!-- Main Header Nav For Mobile -->
	<div id="page" class="stylehome1 h0">
		<div class="mobile-menu">
			<div class="header stylehome1">
				<div class="main_logo_home2">
		            <img class="nav_logo_img img-fluid float-left mt20" src="<?= asset($settings->logo) ?>" alt="<?= $settings->app_name ?>">
		            <span><?= $settings->app_name ?></span>
				</div>
			
				<ul class="menu_bar_home2">

					<li class="list-inline-item">
	                	<div class="search_overlay">
						  <a id="search-button-listener2" class="mk-search-trigger mk-fullscreen-trigger" href="#">
						    <div id="search-button2"><i class="flaticon-magnifying-glass"></i></div>
						  </a>
							<div class="mk-fullscreen-search-overlay" id="mk-search-overlay2">
							    <a href="#" class="mk-fullscreen-close" id="mk-fullscreen-close-button2"><i class="fa fa-times"></i></a>
							    <div id="mk-fullscreen-search-wrapper2">
							      <form action=""  method="GET" id="mk-fullscreen-searchform2">
							        <input type="text" value="" placeholder="Search courses..." id="mk-fullscreen-search-input2">
							        <i class="flaticon-magnifying-glass fullscreen-search-icon"></i>
							      </form>
							    </div>
							</div>
						</div>
					</li>
					
					<li class="list-inline-item"><a href="#menu"><span></span></a></li>
				</ul>
			</div>
		</div><!-- /.mobile-menu -->
		<nav id="menu" class="stylehome1">
			<ul>
				<li><a href="<?= url('/shop') ?>"><span>Home</span></a></li>
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
				<li><a href="<?= url('/shop/cart.php'); ?>"><span class="fa fa-shopping-cart"></span> Cart</a></li>
				<li><a href="<?= url('/') ?>"><span>Work place</span></a></li>
					<?php if(Auth::is_loggedIn()): ?>
					<li><a href="<?= url('/shop/logout') ?>"><span class="fa fa-sign-out"></span> Logout</a></li>
				<?php else: ?>
					<li><a href="<?= url('/shop/login') ?>"><span class="flaticon-user"></span> Login</a></li>
					<li><a href="<?= url('/shop/register') ?>"><span class="fa fa-sign-in"></span> Register</a></li>
                <?php endif; ?>
			</ul>
		</nav>
	</div>