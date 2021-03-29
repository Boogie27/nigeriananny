




	<!-- Our Dashbord Sidebar -->
	<section class="dashboard_sidebar dn-1199">
		<div class="dashboard_sidebars">
			<div class="user_board">
				<div class="user_profile">
					<div class="media">
					  	<div class="media-body">
					    	<h4 class="mt-0">Start</h4>
						</div>
					</div>
				</div>
				<div class="dashbord_nav_list" >
					<ul>
						<li><a href="<?= url('/shop/account') ?>"><span class="flaticon-puzzle-1"></span> Account</a></li>
						<!-- <li><a href="page-my-courses.html"><span class="flaticon-online-learning"></span> My Courses</a></li> -->
						<li class=""><a href="<?= url('/shop/order') ?>"><span class="flaticon-shopping-bag-1"></span> Order</a></li>
						<li><a href="<?= url('/shop/user-detail') ?>"><span class="flaticon-speech-bubble"></span> Details</a></li>
						<li><a href="<?= url('/shop/reviewed') ?>"><span class="flaticon-rating"></span> Reviews</a></li>
						<li class=""><a href="<?= url('/shop/order-cancle') ?>"><span class="flaticon-shopping-bag-1"></span>Cancled Order</a></li>						
						<li><a href="<?= url('/shop/address-book') ?>"><span class="flaticon-like"></span> Address book</a></li>
						<?php if(Auth::is_loggedin()):?>
						<li><a href="#" data-toggle="modal" data-target="#change_password"><span class="flaticon-add-contact"></span>Change password</a></li>
						<?php endif;?>
					</ul>
					<h4>Account</h4>
					<ul>
						<?php if(!Auth::is_loggedin()):?>
						<li><a href="<?= url('/shop/login') ?>"><span class="flaticon-user"></span> Login</a></li>
						<li><a href="<?= url('/shop/register') ?>"><span class="flaticon-online-learning"></span> Register</a></li>
						<?php else:?>
						<li><a href="<?= url('/shop/logout') ?>"><span class="flaticon-logout"></span> Logout</a></li>
						<?php endif;?>
					</ul>
				</div>
			</div>
		</div>
	</section>


	<?php include('common/change-password-modal.php') ?>