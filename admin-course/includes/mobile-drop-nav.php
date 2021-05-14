<div class="dashboard_navigationbar dn db-1199">
    <div class="dropdown">
        <button onclick="myFunction()" class="dropbtn"><i class="fa fa-bars pr10"></i> Dashboard Navigation</button>
        <ul id="myDropdown" class="dropdown-content">
            <li class="active"><a href="<?= url('/admin-course') ?>"><span class="flaticon-puzzle-1"></span> Dashboard</a></li>
            <li><a href="<?= url('/admin-course/category') ?>"><span class="fa fa-cubes"></span>Categories</a></li>
            <li><a href="<?= url('/admin-course/courses') ?>"><span class="fa fa-video-camera"></span>Courses</a></li>
            <li><a href="<?= url('/admin-course/users') ?>"><span class="fa fa-users"></span>Manage users</a></li>
            <li><a href="<?= url('/admin-nanny/profile') ?>"><span class="fa fa-user-o"></span>Profile</a></li>
            <li><a href="<?= url('/admin-nanny/general-settings') ?>"><span class="flaticon-settings"></span> General settings</a></li>
            <li><a href="<?= url('/admin/logout') ?>"><span class="flaticon-logout"></span> Logout</a></li>
        </ul>
    </div>
</div>




