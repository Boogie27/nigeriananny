


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
                    <li class="active"><a href="<?= url('/admin') ?>"><span class="flaticon-puzzle-1"></span> Dashboard</a></li>
                    <li><a href="<?= url('/admin/products') ?>"><span class="flaticon-online-learning"></span>Manage products</a></li>
                    <li><a href="<?= url('/admin/add-product') ?>"><span class="flaticon-shopping-bag-1"></span> Add product</a></li>
                    <li><a href="<?= url('/admin/category') ?>"><span class="flaticon-speech-bubble"></span> Manage category</a></li>
                    <li><a href="<?= url('/admin/sub-category') ?>"><span class="flaticon-rating"></span> Manage subcategory</a></li>
                    <li><a href="<?= url('/admin/customers') ?>"><span class="flaticon-add-contact"></span> Manage customers</a></li>
                    <li><a href="<?= url('/admin/transactions') ?>"><span class="fa fa-cubes"></span> Manage transactions</a></li>
                    <li><a href="<?= url('/admin/cancle-order') ?>"><span class="fa fa-cube"></span>Cancled orders</a></li>
                    <li><a href="<?= url('/admin/profile') ?>"><span class="fa fa-user"></span>Profile</a></li>
                </ul>
                
                <h4>Account</h4>
                <ul>
                    <li><a href="<?= url('/admin/general-settings') ?>"><span class="flaticon-settings"></span> General settings</a></li>
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