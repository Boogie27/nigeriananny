<div class="dashboard_navigationbar dn db-1199">
    <div class="dropdown">
        <button onclick="myFunction()" class="dropbtn"><i class="fa fa-bars pr10"></i> Dashboard Navigation</button>
        <ul id="myDropdown" class="dropdown-content">
            <li class="active"><a href="<?= url('/admin/index.php') ?>"><span class="flaticon-puzzle-1"></span> Dashboard</a></li>
            <li class="active"><a href="<?= url('/admin-nanny') ?>"><span class="flaticon-puzzle-1"></span> Work dashboard</a></li>
            <li><a href="<?= url('/admin/products.php') ?>"><span class="flaticon-online-learning"></span>Manage products</a></li>
            <li><a href="<?= url('/admin/add-product.php') ?>"><span class="flaticon-shopping-bag-1"></span> Add product</a></li>
            <li><a href="<?= url('/admin/category.php') ?>"><span class="flaticon-speech-bubble"></span> Manage category</a></li>
            <li><a href="<?= url('/admin/sub-category.php') ?>"><span class="flaticon-rating"></span> Manage subcategory</a></li>
            <li><a href="<?= url('/admin/customers.php') ?>"><span class="flaticon-add-contact"></span> Manage customers</a></li>
            <li><a href="<?= url('/admin/transactions.php') ?>"><span class="fa fa-cubes"></span> Manage transactions</a></li>
            <li><a href="<?= url('/admin/cancle-order.php') ?>"><span class="fa fa-cube"></span> Manage cancled orders</a></li>
            <li><a href="<?= url('/admin/profile.php') ?>"><span class="fa fa-user"></span>Profile</a></li>
            <li><a href="<?= url('/admin-nanny/general-settings') ?>"><span class="flaticon-settings"></span> General settings</a></li>
            <li><a href="<?= url('/admin/logout.php') ?>"><span class="flaticon-logout"></span> Logout</a></li>
        </ul>
    </div>
</div>




