



    <div class="dashboard_navigationbar dn db-1199">
        <div class="dropdown">
            <button onclick="myFunction()" class="dropbtn"><i class="fa fa-bars pr10"></i> Dashboard Navigation</button>
            <ul id="myDropdown" class="dropdown-content">
                <li><a href="<?= url('/shop/account') ?>"><span class="flaticon-puzzle-1"></span> Account</a></li>
                <li><a href="<?= url('/shop/user-detail') ?>"><span class="flaticon-online-learning"></span>My details</a></li>
                <li class="active"><a href="<?= url('/shop/order') ?>"><span class="flaticon-shopping-bag-1"></span> Order</a></li>
                <li><a href="<?= url('/shop/order-cancle') ?>"><span class="flaticon-speech-bubble"></span> Cancled order</a></li>
                <li><a href="<?= url('/shop/reviewed') ?>"><span class="flaticon-rating"></span> Reviews</a></li>
                <li><a href="#" data-toggle="modal" data-target="#change_password"><span class="flaticon-add-contact"></span> Change password</a></li>
            </ul>
        </div>
    </div>