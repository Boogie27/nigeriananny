<div class="dashboard_navigationbar dn db-1199">
    <div class="dropdown">
        <button onclick="myFunction()" class="dropbtn"><i class="fa fa-bars pr10"></i> Dashboard Navigation</button>
        <ul id="myDropdown" class="dropdown-content">
            <li class="active"><a href="<?= url('/admin-nanny') ?>"><span class="flaticon-puzzle-1"></span> Dashboard</a></li>
            <li class=""><a href="<?= url('/admin') ?>"><span class="flaticon-puzzle-1"></span> Shop dashboard</a></li>
            <li><a href="<?= url('/admin-nanny/categories') ?>"><span class="fa fa-cubes"></span>Categories</a></li>
            <li><a href="<?= url('/admin-nanny/employees') ?>"><span class="fa fa-briefcase"></span>Employees</a></li>
            <li><a href="<?= url('/admin-nanny/unapproved-employees') ?>"><span class="fa fa-briefcase text-warning"></span>Unapproved Employees</a></li>
            <li><a href="<?= url('/admin-nanny/deactivated-employees') ?>"><span class="fa fa-briefcase text-danger"></span>Deactivated Employees</a></li>
            <li><a href="<?= url('/admin-nanny/employers') ?>"><span class="fa fa-users"></span>Employers</a></li>
            <li><a href="<?= url('/admin-nanny/unapproved-employers') ?>"><span class="fa fa-briefcase text-warning"></span>Unapproved Employers</a></li>
            <li><a href="<?= url('/admin-nanny/deactivated-employers') ?>"><span class="fa fa-briefcase text-danger"></span>Deactivated Employers</a></li>
            <li><a href="<?= url('/admin-nanny/subscriptions') ?>"><span class="flaticon-speech-bubble"></span>Subscriptions</a></li>
            <li><a href="<?= url('/admin-nanny/subscription') ?>"><span class="flaticon-speech-bubble"></span>Employer subscription</a></li>
            <li><a href="<?= url('/admin/sub-category') ?>"><span class="flaticon-rating"></span> Manage subcategory</a></li>
            <li><a href="<?= url('/admin-nanny/report-employee') ?>"><span class="flaticon-add-contact"></span> Reported employee</a></li>
            <li><a href="<?= url('/admin/transactions') ?>"><span class="fa fa-cubes"></span> Manage transactions</a></li>
            <li><a href="<?= url('/admin-nanny/profile') ?>"><span class="fa fa-user-o"></span>Profile</a></li>
            <li><a href="<?= url('/admin-nanny/general-settings') ?>"><span class="flaticon-settings"></span> General settings</a></li>
            <li><a href="<?= url('/admin/logout') ?>"><span class="flaticon-logout"></span> Logout</a></li>
        </ul>
    </div>
</div>




