


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
                    <li class="<?= path('/') ? 'active' : ''?>"><a href="<?= url('/admin') ?>"><span class="flaticon-puzzle-1"></span> Dashboard</a></li>
                    <li class="<?= path('products') ? 'active' : ''?>"><a href="<?= url('/admin/products') ?>"><span class="flaticon-online-learning"></span>Manage products</a></li>
                    <li class="<?= path('add-product') ? 'active' : ''?>"><a href="<?= url('/admin/add-product') ?>"><span class="flaticon-shopping-bag-1"></span> Add product</a></li>
                    <li class="<?= path('category') ? 'active' : ''?>"><a href="<?= url('/admin/category') ?>"><span class="flaticon-speech-bubble"></span> Manage category</a></li>
                    <li class="<?= path('sub-category') ? 'active' : ''?>"><a href="<?= url('/admin/sub-category') ?>"><span class="flaticon-rating"></span> Manage subcategory</a></li>
                    <li class="<?= path('customers') ? 'active' : ''?>"><a href="<?= url('/admin/customers') ?>"><span class="flaticon-add-contact"></span> Manage customers</a></li>
                    <li class="<?= path('transactions') ? 'active' : ''?>"><a href="<?= url('/admin/transactions') ?>"><span class="fa fa-cubes"></span> Manage transactions</a></li>
                    <li class="<?= path('cancle-order') ? 'active' : ''?>"><a href="<?= url('/admin/cancle-order') ?>"><span class="fa fa-cube"></span>Cancled orders</a></li>
                    <li class="<?= path('profile') ? 'active' : ''?>"><a href="<?= url('/admin/profile') ?>"><span class="fa fa-user"></span>Profile</a></li>
                </ul>
                
                <h4>Account</h4>
                <ul>
                    <li class="<?= path('general-settings') ? 'active' : ''?>"><a href="<?= url('/admin/general-settings') ?>"><span class="flaticon-settings"></span> General settings</a></li>
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