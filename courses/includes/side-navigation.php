<?php 
   $categories = $connection->select('course_categories')->where('is_categoryFeature', 1)->get();
?>


<!-- SIDE NAVIGATION-->
    <div class="side-navigation">
        <ul class="parent-ul">
            <li>
                <a href="<?= url('/') ?>"><i class="fa fa-home"></i>Home</a>
            </li>
            <li>
                <a href="<?= url('/courses') ?>"><i class="fa fa-video-camera"></i>All courses</a>
            </li>
            <li>
                <a href="#" class="nav-drop-down"><i class="fa fa-cubes"></i>Categories <i class="fa fa-angle-right float-right angle"></i></a>
                <?php if(count($categories)):?>
                <ul class="child-drop-down">
                    <?php foreach($categories as $category):?>
                        <li><a href="#"><?= $category->category_name?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <li>
                <a href="#" class="nav-drop-down"><i class="fa fa-heart"></i>Saved courses <i class="fa fa-angle-right float-right angle"></i></a>
                <ul class="child-drop-down">
                    <li><a href="#">course 1</a></li>
                    <li><a href="#">course 2</a></li>
                    <li><a href="#">course 3</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-user"></i>Account</a>
            </li>
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
                    <a href="<?= url('/courses/logout') ?>"><i class="fa fa-power-off"></i>Logout</a>
                <?php else: ?>
                    <a href="<?= url('/courses/login') ?>"><i class="fa fa-sign-in"></i>Login</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
