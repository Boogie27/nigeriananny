
<?php
$settings = $connection->select('settings')->where('id', 1)->first();

$categories = $connection->select('job_categories')->where('is_category_featured', 1)->get();
?>
<!-- Main Header Nav For Mobile -->
<div id="page" class="stylehome1 home2 h0">
		<div class="mobile-menu">
			<div class="header stylehome1">
				<div class="main_logo_home2">
		            <img class="nav_logo_img img-fluid float-left mt20" src="<?= asset($settings->logo) ?>" alt="<?= $settings->app_name ?>">
		            <span><?= $settings->app_name ? $settings->app_name : ''; ?></span>
				</div>
				<ul class="menu_bar_home2">
					<li class="list-inline-item">
	                	<div class="search_overlay">
						  <a id="search-button-listener" class="mk-search-trigger mk-fullscreen-trigger" href="#">
						    <div id="search-button"><i class="flaticon-magnifying-glass"></i></div>
						  </a>
							<div class="mk-fullscreen-search-overlay" id="mk-search-overlay">
							    <a href="#" class="mk-fullscreen-close" id="mk-fullscreen-close-button"><i class="fa fa-times"></i></a>
							    <div id="mk-fullscreen-search-wrapper">
							      <form method="get" id="mk-fullscreen-searchform">
							        <input type="text" value="" placeholder="Search courses..." id="mk-fullscreen-search-input">
							        <i class="flaticon-magnifying-glass fullscreen-search-icon"><input value="" type="submit"></i>
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
			    <li>
	              <a href="<?= url('/') ?>"><span>Home</span></a>
				</li>
				<li><span>Categories</span>
				    <?php if(count($categories)):?>
						<ul>
							<?php foreach($categories as $category): ?>
								<li><a href="<?= url('/jobs.php?category='.$category->category_slug) ?>"><?= $category->category_name ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
				<li><a href="<?= url('/jobs') ?>">Find employee</a></li>
				<li><a href="<?= url('/') ?>">Post a job</a></li>
				<?php if(Auth_employer::is_loggedin()): ?>
					<li><a href="<?= url('/employer/account') ?>"><span class="fa fa-user"></span> Account</a></li>
				<?php endif; ?>
				<?php if(Auth_employee::is_loggedin()): ?>
					<li><a href="<?= url('/employee/account') ?>"><span class="fa fa-user"></span> Account</a></li>
				<?php endif; ?>
				<li><a href="<?= url('/subscription') ?>"><span class="flaticon-user"></span> Subscription </a></li>
				<?php if(Auth_employee::is_loggedin()): ?>
					<li><a href="<?= url('/employee/logout') ?>"><span class="flaticon-edit"></span> Logout</a></li>
				<?php else: ?>
					<li><a href="<?= url('/employee/login') ?>"><span class="fa fa-users"></span> Job seeker login</a></li>
				<?php endif; ?>
				<?php if(Auth_employer::is_loggedin()):?>
					<li><a href="<?= url('/employer/logout') ?>"><span class="flaticon-edit"></span> Logout</a></li>
				<?php else: ?>
					<li><a href="<?= url('/employer/login') ?>"><span class="fa fa-briefcase"></span> Employer login</a></li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>