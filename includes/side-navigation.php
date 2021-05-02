<?php 
// ===========================================
// GET ALL JOB CATEGORIES
// ===========================================
$categories = $connection->select('job_categories')->where('is_category_featured', 1)->get(); 


// ************ CHECK IF WORKERS IS SAVED ***************//
$savedSides = false;
if(Cookie::has('saved_worker'))
{
    $savedSides = json_decode(Cookie::get('saved_worker'), true);
}
?>


<!-- SIDE NAVIGATION-->
    <div class="side-bar-dark-theme"></div> <!-- dark theme-->
    <div class="side-navigation">
        <div class="text-right side-nav-cancle">
            <a href="#" class="side-navigation-close"><i class="fa fa-times"></i></a>
        </div>
        <ul class="parent-ul">
            <li>
                <a href="<?= url('/')?>"><i class="fa fa-home"></i>Home</a>
            </li>
            <li>
                <a href="#" class="nav-drop-down"><i class="fa fa-cubes"></i>Categories <i class="fa fa-angle-right float-right angle"></i></a>
                <?php if(count($categories)):?>
                <ul class="child-drop-down">
                    <li><a href="<?= url('/jobs')?>">All categories</a></li>
                    <?php foreach($categories as $category): ?>
                        <li><a href="<?= url('/jobs.php?category='.$category->category_slug) ?>"><?= $category->category_name ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <li>
                <a href="#" class="nav-drop-down"><i class="fa fa-heart"></i>Saved jobs <span class="text-danger side-saved-workers"><?= $savedSides ? '('.count($savedSides).')' : ''?></span><i class="fa fa-angle-right float-right angle"></i></a>
                <?php if($savedSides):?>
                <ul class="child-drop-down">
                    <?php foreach($savedSides as $savedSide): ?>
                    <li><a href="<?= url('/job-detail.php?wid='.$savedSide['worker_id']) ?>"><?= $savedSide['title']?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <li>
                <?php if(Auth_employer::is_loggedin()):?>
                    <a href="<?= url('/employer/account') ?>"><i class="fa fa-user"></i> Account</a>
                    <a href="<?= url('/subscription') ?>"><i class="fa fa-money"></i> Subscription plan</a>
                <?php endif; ?>
                <?php if(Auth_employee::is_loggedin()):?>
                    <a href="<?= url('/employee/account') ?>"><i class="fa fa-user"></i> Account</a>
                <?php endif; ?>
            </li>
            <li>
                <a href="<?= url('/jobs') ?>"><i class="fa fa-briefcase"></i>Find a worker</a>
            </li>
            <li>
                <a href="<?= url('/shop') ?>"><i class="fa fa-shopping-cart"></i>Market place</a>
            </li>
            <li>
                <a href="<?= url('/courses') ?>"><i class="fa fa-video-camera"></i>All courses</a>
            </li>
            <li>
                <a href="<?= url('/contact') ?>"><i class="fa fa-phone"></i>Contact us</a>
            </li>
            <li>
                <?php if(!Auth_employer::is_loggedin()):?>
                    <a href="<?= url('/employer/login') ?>"><i class="fa fa-sign-in"></i>Employer Login</a>
                <?php endif; ?>
            </li>
            <li>
                <?php if(!Auth_employee::is_loggedin()):?>
                    <a href="<?= url('/employee/login') ?>"><i class="fa fa-sign-in"></i>Employee Login</a>
                <?php endif; ?>
            </li>
            <li>
                <?php if(Auth_employer::is_loggedin()):?>
                    <a href="<?= url('/employer/logout') ?>" id="employer_logout_btn"><i class="fa fa-power-off"></i> Logout</a>
                <?php endif; ?>
            </li>
            <li>
                <?php if(Auth_employee::is_loggedin()):?>
                    <a href="<?= url('/employee/logout') ?>" id="employee_logout_btn"><i class="fa fa-power-off"></i> Logout</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
