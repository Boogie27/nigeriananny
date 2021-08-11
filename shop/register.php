<?php include('../Connection.php');  ?>


<?php
    if(Auth::is_loggedin())
    {
       return view('/shop');
    }

   if(Input::post('register'))
   {
        if(Token::check())
        {
            $validate = new DB();
        
            $validation = $validate->validate([
                'first_name' => 'required|min:3|max:50',
                'last_name' => 'required|min:3|max:50',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|max:12',
                'confirm_password' => 'required|match:password',
            ]);
            
            if($validation->passed())
            {
                $create = new DB();
                $create->create('users', [
                        'first_name' => Input::get('first_name'),
                        'last_name' => Input::get('last_name'),
                        'email' => Input::get('email'),
                        'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT)
                    ]);

                if($create->passed())
                {
                    if(Auth::login(Input::get('email')))
                    {
                    return view('/shop');
                    }
                }
            
            }
        }
   }



//    banner 
   $banner =  $connection->select('settings')->where('id', 1)->first();
?>


    <?php include('includes/header.php') ?>

	<!-- Main Header Nav -->
    <?php include('includes/navigation.php') ?>
    <!-- main header nav end -->
    
    <!-- serach bar -->
    <?php include('includes/search-bar.php') ?>


<section class="inner_page_breadcrumb" style="background-image: url('<?= asset($banner->form_banner); ?>');">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 offset-xl-3 text-center breadcrumb_content_x">
                <div class="breadcrumb_content">
                    <h4 class="page_title">Register</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/shop') ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Register</li>
                    </ol>
                </div>
                <div class="banner-icon-x">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="cart_total_quantity"><?= Session::has('cart') ? Session::get('cart')->_totalQty : 0 ?></span>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- register start-->
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <div class="login_form ">
        <div class="shop_form_container">
            <div class="heading">
                <h3 class="text-center">Create New Account</h3>
                <p class="text-center">Have an account? <a class="text-thm" href="<?= url('/shop/login.php') ?>">Login</a></p>
            </div>
            <form action="<?= url('/shop/register.php') ?>" method="post">
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['first_name'])) : ?>
                                <div class="text-danger"><?= $errors['first_name']; ?></div>
                            <?php endif; ?>
                            <input type="text" class="form-control" name="first_name" value="<?= old('first_name') ?>" placeholder="first name">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['last_name'])) : ?>
                                <div class="text-danger"><?= $errors['last_name']; ?></div>
                            <?php endif; ?>
                            <input type="text" class="form-control" name="last_name" value="<?= old('last_name') ?>" placeholder="Last name">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <?php  if(isset($errors['email'])) : ?>
                                <div class="text-danger"><?= $errors['email']; ?></div>
                            <?php endif; ?>
                            <input type="email" class="form-control" name="email" value="<?= old('email') ?>" placeholder="Email Address">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <?php  if(isset($errors['password'])) : ?>
                                <div class="text-danger"><?= $errors['password']; ?></div>
                            <?php endif; ?>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <?php  if(isset($errors['confirm_password'])) : ?>
                                <div class="text-danger"><?= $errors['confirm_password']; ?></div>
                            <?php endif; ?>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                        </div>
                    </div>
                </div>
                <button type="submit" name="register" class="btn btn-log btn-block btn-thm2">Register</button>
                <hr>
                <div class="row mt40">
                    <div class="col-lg col-sm-6">
                        <button type="submit"  class="btn btn-block color-white bgc-fb"><i class="fa fa-facebook float-left mt5"></i> Facebook</button>
                    </div>
                    <div class="col-lg col-sm-6">
                        <button type="submit"  class="btn btn-block color-white bgc-gogle"><i class="fa fa-google float-left mt5"></i> Google</button>
                    </div>
                </div>
                <?= csrf_token() ?>
            </form>
        </div>
    </div>
</div>
<!-- register end-->


<!-- footer -->
<?php include('includes/footer.php') ?>