<?php 
   $categories = $connection->select('course_categories')->where('is_categoryFeature', 1)->get();
?>


<!-- SIDE NAVIGATION-->
    <div class="side-navigation">
        <ul class="parent-ul">
            <li>
                <a href="<?= url('/courses') ?>"><i class="fa fa-home"></i>Home</a>
            </li>
            <li>
                <a href="<?= url('/courses') ?>"><i class="fa fa-camera"></i>All courses</a>
            </li>
            <li>
                <a href="#" class="nav-drop-down"><i class="fa fa-cubes"></i>Categories <i class="fa fa-angle-right float-right angle"></i></a>
                <?php if(count($categories)):?>
                <ul class="child-drop-down">
                    <?php foreach($categories as $category):?>
                        <li><a href="<?= url('/courses/category.php?category='.$category->category_slug) ?>"><?= $category->category_name?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <li>
                <?php $savedCount = Cookie::has('saved_course') ?  json_decode(Cookie::get('saved_course'), true) : ''?>
                <a href="#" class="nav-drop-down"><i class="fa fa-heart"></i>Saved courses <span class="text-danger" id="saved_course_count"><?= $savedCount ? '('.count($savedCount).')' : '' ?></span><i class="fa fa-angle-right float-right angle"></i></a>
                <ul class="child-drop-down" id="save_course_ul_dropdown">
                <?php if(Cookie::has('saved_course')):
                    $savedCourses = json_decode(Cookie::get('saved_course'), true);
                    foreach($savedCourses as $savedCourse):?>
                        <li><a href="<?= url('/courses/detail.php?cid='.$savedCourse['course_id']) ?>"><?= ucfirst(substr($savedCourse['title'], 0, 20)).'...'?></a></li>
                    <?php endforeach; ?>
                <?php else: ?>
                     <li>Empty</li>
                <?php endif; ?>
                </ul>
            </li>
            <?php if(Auth_course::is_loggedin()): ?>
            <li>
                <a href="<?= url('/courses/account') ?>"><i class="fa fa-user"></i>Account</a>
            </li>
            <?php endif; ?>
            <?php if(Auth_course::is_loggedin()): ?>
            <li>
                <a href="<?= url('/courses/change-password') ?>"><i class="fa fa-key"></i>Change password</a>
            </li>
            <?php endif; ?>
            <li>
                <a href="<?= url('/jobs') ?>"><i class="fa fa-briefcase"></i>Find a worker</a>
            </li>
            <li>
                <a href="<?= url('/shop') ?>"><i class="fa fa-shopping-cart"></i>Market place</a>
            </li>
            <li>
                <a href="<?= url('/contact') ?>"><i class="fa fa-phone"></i>Contact us</a>
            </li>
            <li>
                <?php if(Auth_course::is_loggedin()): ?>
                    <a href="<?= url('/courses/logout') ?>" id="course_user_logout_btn"><i class="fa fa-power-off"></i>Logout</a>
                <?php else: ?>
                    <a href="<?= url('/courses/login') ?>"><i class="fa fa-sign-in"></i>Login</a>
                    <a href="<?= url('/courses/register') ?>"><i class="fa fa-users"></i>Register</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
