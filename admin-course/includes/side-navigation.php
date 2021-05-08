
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
                    <li class="<?= path('employees') ? 'active' : ''?>"><a href="<?= url('/admin-course/courses') ?>"><span class="fa fa-video-camera"></span> Courses</a></li>
                    <li class="<?= path('employers') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/employers') ?>"><span class="fa fa-users"></span>Employers</a></li>
                    <li class="<?= path('employers') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/employments') ?>"><span class="fa fa-money"></span>Employments</a></li>
                    <li class="<?= path('subscriptions') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/subscriptions') ?>"><span class="flaticon-speech-bubble"></span>Subscriptions</a></li>
                    <li class="<?= path('subscription') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/subscription') ?>"><span class="flaticon-speech-bubble"></span>Employer subscription</a></li>
                    <li class="<?= path('report-employee') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/report-employee') ?>"><span class="flaticon-add-contact"></span> Reported employee</a></li>
                    <li class="<?= path('testimonial') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/testimonial') ?>"><span class="fa fa-folder-o"></span> Testimonial</a></li>
                    <li class="<?= path('message') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/message') ?>"><span class="fa fa-envelope-o"></span> Messages <span class="text-danger" style="font-size: 13px;"><?= $new_messages ? '('.count($new_messages).')' : ''?> </span></a></li>
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