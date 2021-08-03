<div class="dashboard_navigationbar dn db-1199">
    <div class="dropdown">
        <button onclick="myFunction()" class="dropbtn"><i class="fa fa-bars pr10"></i> Dashboard Navigation</button>
        <ul id="myDropdown" class="dropdown-content">
            <li class="<?= path('/') ? 'active' : ''?>"><a href="<?= url('/admin') ?>"><span class="flaticon-puzzle-1"></span> Dashboard</a></li>
            <li class="<?= path('admin-nanny') ? 'active' : ''?>"><a href="<?= url('/admin-nanny') ?>"><span class="flaticon-puzzle-1"></span> Work dashboard</a></li>
            <li class="<?= path('products') ? 'active' : ''?>"><a href="<?= url('/admin/products') ?>"><span class="flaticon-online-learning"></span>Manage products</a></li>
            <li class="<?= path('add-product') ? 'active' : ''?>"><a href="<?= url('/admin/add-product') ?>"><span class="flaticon-shopping-bag-1"></span> Add product</a></li>
            <li class="<?= path('category') ? 'active' : ''?>"><a href="<?= url('/admin/category') ?>"><span class="flaticon-speech-bubble"></span> Manage category</a></li>
            <li class="<?= path('sub-category') ? 'active' : ''?>"><a href="<?= url('/admin/sub-category') ?>"><span class="flaticon-rating"></span> Manage subcategory</a></li>
            <li class="<?= path('all-customers') ? 'active' : ''?>"><a href="<?= url('/admin/all-customers') ?>"><span class="flaticon-add-contact"></span> All customers</a></li>
            <li class="<?= path('customers') ? 'active' : ''?>"><a href="<?= url('/admin/customers') ?>"><span class="flaticon-add-contact"></span> Manage customers</a></li>
            <li class="<?= path('deactivated-customers') ? 'active' : ''?>"><a href="<?= url('/admin/deactivated-customers') ?>"><span class="flaticon-add-contact"></span> Deactivated customers</a></li>
            <li class="<?= path('transactions') ? 'active' : ''?>"><a href="<?= url('/admin/transactions') ?>"><span class="fa fa-cubes"></span> Manage transactions</a></li>
            <li class="<?= path('cancle-order') ? 'active' : ''?>"><a href="<?= url('/admin/cancle-order') ?>"><span class="fa fa-cube"></span> Manage cancled orders</a></li>
            <li class="<?= path('profile') ? 'active' : ''?>"><a href="<?= url('/admin/profile') ?>"><span class="fa fa-user"></span>Profile</a></li>
            <li class="<?= path('general-settings') ? 'active' : ''?>"><a href="<?= url('/admin-nanny/general-settings') ?>"><span class="flaticon-settings"></span> General settings</a></li>
            <li><a href="<?= url('/admin/logout') ?>"><span class="flaticon-logout"></span> Logout</a></li>
        </ul>
    </div>
</div>




