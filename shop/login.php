<?php include('../Connection.php');  ?>



<?php
    if(Auth::is_loggedin())
    {
        return view('shop');
    }
    
   if(Input::post('login'))
   {
        $validate = new DB();
       
        $validate->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:12',
        ]);


        if(!$validate->passed())
        {
            return back();
        }
        
        if($validate->passed())
        {
            $verify = new DB();
            $verification = $verify->select('users')->where('email', Input::get('email'))->first();
            if(!$verification)
            {
                Session::errors('errors', ['email' => '*Wrong email provided, try again!']);
               return back();
            }

            if(!password_verify(Input::get('password'), $verification->password))
            {
                Session::errors('errors', ['password' => '*Wrong password, try again!']);
               return back();
            }

            if($verification->is_deactivate)
            {
                Session::flash('error', '*This account has been deactivated, please contact the admin.');
               return back();
            }

            $remember_me = Input::get('remember_me')? true : false;

            $logged_in = Auth::login(Input::get('email'), $remember_me);

            if($logged_in && Session::has('old_url'))
            {
                $old_url = Session::get('old_url');
                Session::delete('old_url');
                return Redirect::to($old_url);
            }

            if($logged_in)
            {
                return view('/shop');
            }
        }
       
   }


// ==========================================
// SETTINGS
// ==========================================
$banner =  $connection->select('settings')->where('id', 1)->first();




//  =============================================
// GOOGLE LOGIN AUTH
// =============================================
$google = new Google();
if(Input::post('google_login'))
{
    Session::delete('employer_login');
    Session::delete('employee_login');
    Session::put('google_auth', true);
    Session::put('shop_login', true);
    return Redirect::to($google->auth_url());
}




// =============================================
// FACEBOOK LOGIN
// =============================================
if(Input::post('facebook_login'))
{
    $facebook = new Facebook();
    Session::delete('fb_employer_login');
    Session::put('fb_shop_login', true);
    Session::put('facebook_auth', true);
    Session::delete('fb_employee_login');
    return Redirect::to($facebook->login_url());
}

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
                    <h4 class="page_title">Login</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/shop/index.php') ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Login</li>
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


<!-- login start-->
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <div class="login_form shop_form_container">
        <form action="<?= current_url() ?>" method="post">
            <div class="heading">
                <h3 class="text-center">Login to your account</h3>
                <p class="text-center">Don't have an account? <a class="text-thm" href="<?= url('/shop/register.php') ?>">Sign Up!</a></p>
            </div>
            <?php if(Session::has('success')): ?>
                <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
            <?php endif; ?>
            <?php if(Session::has('error')): ?>
                <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
            <?php endif; ?>
            <div class="form-group">
                <?php  if(isset($errors['email'])) : ?>
                    <div class="text-danger"><?= $errors['email']; ?></div>
                <?php endif; ?>
                <input type="email" class="form-control" name="email" placeholder="Email Address">
            </div>
            <div class="form-group">
                <?php  if(isset($errors['password'])) : ?>
                    <div class="text-danger"><?= $errors['password']; ?></div>
                <?php endif; ?>
                <input type="password" class="form-control"  name="password" placeholder="Password">
            </div>
            <div class="form-group custom-control custom-checkbox">
                <input type="checkbox" name="remember_me" id="exampleCheck1" class="custom-control-input">
                <label class="custom-control-label" for="exampleCheck1">Remember me</label>
                <a class="tdu btn-fpswd float-right" href="<?= url('/shop/forgot-password') ?>">Forgot Password?</a>
            </div>
            <button type="submit" name="login" class="btn btn-log btn-block btn-thm2">Login</button>
            <hr>
            <div class="row mt40">
                <div class="col-lg col-sm-6">
                    <button type="submit" name="facebook_login" class="btn btn-block color-white bgc-fb"><i class="fa fa-facebook float-left mt5"></i> Facebook</button>
                </div>
                <div class="col-lg col-sm-6">
                    <button type="submit" name="google_login" class="btn btn-block color-white bgc-gogle"><i class="fa fa-google float-left mt5"></i> Google</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- login end-->


<!-- footer -->
<?php include('includes/footer.php') ?>





