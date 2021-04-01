<!-- navbar-scrolltofixed -->

<!-- Main Header Nav -->
	<header class="header-nav home2 style_one  main-menu">
		<div class="container">
		    <!-- Ace Responsive Menu -->
		    <nav>
		        <!-- Menu Toggle btn-->
		        <div class="menu-toggle">
		            <button type="button" id="menu-btn">
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		            </button>
		        </div>
		        <!-- Responsive Menu Structure-->
		        <!--Note: declare the Menu style in the data-menu-style="horizontal" (options: horizontal, vertical, accordion) -->
		        <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">
		            <li><a href="<?= url('/') ?>"><span class="title">Home</span></a></li>
					<li><a href="<?= url('/jobs') ?>"><span class="title">Find employee</span></a></li>
					<li>
		                <a href="#"><span class="title">My account</span></a>
		                <ul>
							<?php if(Auth_employer::is_loggedin()):?>
								<li><a href="<?= url('/employer/account') ?>">Account</a></li>
							<?php endif; ?>
							<?php if(Auth_employee::is_loggedin()):?>
								<li><a href="<?= url('/employee/account') ?>">Account</a></li>
							<?php endif; ?>
							<?php if(!Auth_employee::is_loggedin()): ?>
								<li><a href="<?= url('/subscription') ?>">Subscription plan</a></li>
								<li><a href="<?= url('/employee/login') ?>">Job seeker login</a></li>
							<?php endif; ?>
							<?php if(!Auth_employer::is_loggedin()):?>
								<li><a href="<?= url('/employer/login') ?>">Employer login</a></li>
								<li><a href="<?= url('/form') ?>">Create account</a></li>
							<?php else: ?>
								<li><a href="<?= url('/employer/logout') ?>">Logout</a></li>
							<?php endif; ?>
							<?php if(Auth_employee::is_loggedin()): ?>
								<li><a href="<?= url('/employee/logout') ?>">Logout</a></li>
							<?php endif; ?>
		                </ul>
					</li>
					<li><a href="<?= url('/flagged') ?>"><span class="title">Flagged</span></a></li>
					<li class="last">
		                <a href="<?= url('/contact') ?>"><span class="title">Contact us</span></a>
					</li>
					<li class="last">
		                <a href="#" class="news_letter_open_btn"><span class="title">News letter</span></a>
		            </li>
		        </ul>
		        <ul class="sign_up_btn pull-right dn-smd mt20">
					<?php if(!Auth_employer::is_loggedin()):?>
						<li class="list-inline-item"><a href="<?= url('/form') ?>" class="btn btn-md"><i class="flaticon-user"></i> <span class="dn-md">Create account</span></a></li>
					<?php else: ?>
						<li class="list-inline-item"><a href="<?= url('/employer/logout.php') ?>" class="btn btn-md"><i class=""></i> <span class="dn-md">Logout</span></a></li>
					<?php endif; ?>
					<?php if(Auth_employee::is_loggedin()): ?>
					<li class="list-inline-item"><a href="<?= url('/employee/logout.php') ?>" class="btn btn-md"><i class=""></i> <span class="dn-md">Logout</span></a></li>
					<?php endif; ?>
					<li class="list-inline-item"><a href="<?= url('/shop') ?>" class="btn btn-md"><i class=""></i> <span class="dn-md">Market place</span></a></li>
				</ul><!-- Button trigger modal -->
		    </nav>
		    <!-- End of Responsive Menu -->
		</div>
	</header>



