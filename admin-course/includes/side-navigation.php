
<?php
$new_messages = $connection->select('contact_us')->where('is_seen', 0)->get();
?>

<section class="dashboard_sidebar dn-1199">
    <div class="dashboard_sidebars">
        <div class="user_board">
            <div class="user_profile">
                <div class="media">
                    <div class="media-body media-body-anchor text-center">
                        <a href="<?= url('/admin-nanny') ?>" title="Work place"><i class="fa fa-briefcase"></i></a> |
                        <a href="<?= url('/admin') ?>" title="Market place"><i class="fa fa-shopping-cart"></i></a> |
                        <a href="<?= url('/admin-course') ?>" title="Course"><i class="fa fa-video-camera"></i></a>
                    </div>
                </div>
            </div>
            <div class="dashbord_nav_list">
               
                <ul>
                    <li class="<?= path('/') ? 'active' : ''?>"><a href="<?= url('/admin-course') ?>"><span class="flaticon-puzzle-1"></span> Dashboard</a></li>
                    <li class="<?= path('categories') ? 'active' : ''?>"><a href="<?= url('/admin-course/category') ?>"><span class="fa fa-cubes"></span>Categories</a></li>
                    <li class="<?= path('courses') ? 'active' : ''?>"><a href="<?= url('/admin-course/courses') ?>"><span class="fa fa-video-camera"></span> Courses</a></li>
                    <li class="<?= path('all-users') ? 'active' : ''?>"><a href="<?= url('/admin-course/all-users') ?>"><span class="fa fa-users text-success"></span>All users</a></li>
                    <li class="<?= path('users') ? 'active' : ''?>"><a href="<?= url('/admin-course/users') ?>"><span class="fa fa-users"></span>Manage users</a></li>
                    <li class="<?= path('deactivated') ? 'active' : ''?>"><a href="<?= url('/admin-course/deactivated-users') ?>"><span class="fa fa-users text-danger"></span>Deactivated users</a></li>
                    <li class="<?= path('profile') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/profile') ?>"><span class="fa fa-user-o"></span>Profile</a></li>
                </ul>
                
                <h4>Account</h4>
                <ul>
                <li class="<?= path('general-settings') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/general-settings') ?>"><span class="flaticon-settings"></span> General settings</a></li>
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