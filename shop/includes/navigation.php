
<?php
$settings = $connection->select('settings')->where('id', 1)->first();
?>


	<header class="header-nav menu_style_home_one navbar-scrolltofixed stricky main-menu">
		<div class="container-fluid">
		    <!-- Ace Responsive Menu -->
		    <nav>
		        <!-- Menu Toggle btn-->
		        <div class="menu-toggle">
		            <img class="nav_logo_img img-fluid" src="<?= asset($settings->logo)?>" alt="<?=$settings->app_name ?>">
		            <button type="button" id="menu-btn">
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		            </button>
		        </div>
		        <a href="<?= url('/shop'); ?>" class="navbar_brand float-left dn-smd">
		            <img class="logo1 img-fluid" src="<?= asset($settings->logo)?>" alt="<?=$settings->app_name ?>">
		            <img class="logo2 img-fluid" src="<?= asset($settings->logo)?>" alt="<?=$settings->app_name ?>">
		            <span><?= $settings->app_name ? $settings->app_name : '' ?></span>
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
		          
					<?php if(Auth::is_loggedin()):?>
		            <li>
		                <a href="<?= url('/shop/account') ?>"><span class="title">My account</span></a>
		            </li>
					<?php endif;?>
		            <li>
		                <a href="<?= url('/shop/order') ?>"><span class="title">My order</span></a>
		            </li>
					<li>
		                <a href="<?= url('/shop/cart') ?>"><span class="title">Cart</span></a>
		            </li>
					<li>
		                <a href="<?= url('/') ?>"><span class="title">Find a worker</span></a>
		            </li>
					
		        </ul>
		        <ul class="sign_up_btn pull-right dn-smd mt20">
	                <li class="list-inline-item list_s">
					    <?php if(!Auth::is_loggedin()): ?>
					        <a href="<?= url('/shop/login') ?>" class="btn flaticon-user"> <span class="td-lg">Login/Register</span></a>
						<?php else: ?>
						    <a href="<?= url('/shop/logout') ?>" class="btn flaticon-user"><span class="td-lg">Logout</span></a>
						<?php endif; ?>
					</li>
	                <li class="list-inline-item list_s">
	                	<div class="cart_btn">
							<ul class="cart">
								<li>
									<a href="<?= url('/shop/cart') ?>" class="btn cart_btn flaticon-shopping-bag">
									    <span class="cart_total_quantity"><?= Session::has('cart') ? Session::get('cart')->_totalQty : 0 ?></span>
									</a>
								</li>
							</ul>
						</div>
	                </li>
	                <li class="list-inline-item list_s">
	                	<div class="search_overlay">
						 	<a id="search-button-listener" class="mk-search-trigger mk-fullscreen-trigger" href="#">
						    	<span id="search-button"><i class="flaticon-magnifying-glass"></i></span>
						 	</a>
						</div>
	                </li>
	            </ul><!-- Button trigger modal -->
		    </nav>
		</div>
	</header>


    <?php include('mobile-navigation.php') ?>