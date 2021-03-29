


<section class="dashboard_sidebar dn-1199">
    <div class="dashboard_sidebars">
        <div class="user_board">
            <div class="user_profile">
                <div class="media">
                    <div class="media-body text-center">
                        <a href="<?= url('/admin-nanny') ?>">Work place</a> | <a href="<?= url('/admin') ?>">Shop</a>
                    </div>
                </div>
            </div>
            <div class="dashbord_nav_list">
               
                <ul>
                    <li class="active"><a href="<?= url('/admin-nanny') ?>"><span class="flaticon-puzzle-1"></span> Dashboard</a></li>
                    <li><a href="<?= url('/admin-nanny/categories') ?>"><span class="fa fa-cubes"></span>Categories</a></li>
                    <li><a href="<?= url('/admin-nanny/employees') ?>"><span class="fa fa-briefcase"></span>Employees</a></li>
                    <li><a href="<?= url('/admin-nanny/employers') ?>"><span class="fa fa-users"></span>Employers</a></li>
                    <li><a href="<?= url('/admin-nanny/subscriptions') ?>"><span class="flaticon-speech-bubble"></span>Subscriptions</a></li>
                    <li><a href="<?= url('/admin-nanny/subscription') ?>"><span class="flaticon-speech-bubble"></span>Employer subscription</a></li>
                    <li><a href="<?= url('/admin-nanny/report-employee') ?>"><span class="flaticon-add-contact"></span> Reported employee</a></li>
                    <li><a href="<?= url('/admin-nanny/testimonial') ?>"><span class="fa fa-folder"></span> Testimonial</a></li>
                    <li><a href="<?= url('/admin-nanny/profile') ?>"><span class="fa fa-user"></span>Profile</a></li>
                </ul>
                
                <h4>Account</h4>
                <ul>
                <li><a href="<?= url('/admin-nanny/general-settings') ?>"><span class="flaticon-settings"></span> General settings</a></li>
                    <?php if(!Admin_auth::is_loggedin()): ?>
                        <li><a href="<?= url('/admin/login') ?>"><span class="flaticon-settings"></span> Login</a></li>
                    <?php else: ?>
                        <li><a href="<?= url('/admin/logout') ?>"><span class="flaticon-logout"></span> Logout</a></li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    </div>
</section>