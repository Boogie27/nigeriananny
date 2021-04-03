<?php include('../Connection.php');  ?>

<?php
  if(Auth_employer::is_loggedin())
  {
      return view('/');
  }




//   =============================================
// EMPLOYER EMAIL LOGIN
// ==============================================
 if(Input::post('login_employer'))
 {
    $validate = new DB();
    
    $validation = $validate->validate([
        'email' => 'required|email',
        'password' => 'required|min:6|max:12',
    ]);


    
    if($validation->passed())
    {
        $verify = new DB();
        $verification = $verify->select('employers')->where('email', Input::get('email'))->first();
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

        if($verification->e_deactivate)
        {
            Session::flash('error', '*This account has been deactivated, please contact the admin.');
            return back();
        }

        $remember_me = Input::get('remember_me')? true : false;

        $logged_in = Auth_employer::login(Input::get('email'), $remember_me);

        if($logged_in && Session::has('old_url'))
        {
            $old_url = Session::get('old_url');
            Session::delete('old_url');
            Session::flash('success', 'You have loggedin successfully!');
            return view($old_url);
        }

        if($logged_in)
        {
            Session::flash('success', 'You have loggedin successfully!');
            return view('/');
        }
    }
    
}





 //  =============================================
// GOOGLE LOGIN AUTH
// =============================================
$google = new Google();
if(Input::post('google_login'))
{
    Session::delete('shop_login');
    Session::delete('employee_login');
    Session::put('employer_login', true);
    return Redirect::to($google->auth_url());
}

?>




<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<!-- images/home/4.jpg -->
	

<!-- mobile navigation-->
<?php include('includes/mobile-navigation.php');  ?>
    



<div class="page-content">
    <div class="job-seeker-conatiner">
        <div class="sr-head"><h4>Employer login</h4></div>
        <form action="<?= current_url() ?>" method="POST">
            <?php if(Session::has('error')): ?>
                <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
            <?php endif; ?>
            <?php if(Session::has('success')): ?>
                <div class="alert-success text-center p-3"><?= Session::flash('success') ?></div>
            <?php endif; ?>
            <div class="form-seeker form-employer-container">
                <div class="row">
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['email'])) : ?>
                                <div class="text-danger"><?= $errors['email']; ?></div>
                            <?php endif; ?>
                            <label for="">Email:</label>
                            <input type="email" name="email" class="form-control h50" value="<?= old('email')?>" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['password'])) : ?>
                                <div class="text-danger"><?= $errors['password']; ?></div>
                            <?php endif; ?>
                            <label for="">Password:</label>
                            <input type="password" name="password" class="form-control h50" required>
                        </div>
                    </div>
                   
                   <div class="col-lg-12">
                        <div class="form-group custom-control custom-checkbox">
                            <input type="checkbox" name="remember_me" id="exampleCheck1" class="custom-control-input">
                            <label class="custom-control-label" for="exampleCheck1">Remember me</label>
                            <a class="tdu btn-fpswd text-danger float-right" href="<?= url('/employer/forgot-password') ?>">Forgot Password?</a>
                        </div>
                   </div>
                   <div class="col-lg-12">
                        <div class="form-group">
                            <button type="submit" name="login_employer" class="uppercase btn-fill">Employer login</button>
                            <p class="apply-p">Don't have an account? <br><a href="<?= url('/employer/register') ?>" class="text-primary">Register</a></p>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center">
                        <span class="lf_divider ">Or</span>
                        <hr>
                    </div>
                    <div class="col-lg">
                        <button type="submit" class="btn btn-block color-white bgc-fb mb0"><i class="fa fa-facebook float-left mt5"></i> Facebook</button>
                    </div>
                    <div class="col-lg">
                        <button type="submit" name="google_login" class="btn btn2 btn-block color-white bgc-gogle mb0"><i class="fa fa-google float-left mt5"></i> Google</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>




    
<!-- Our Footer -->
<?php include('../includes/footer.php');  ?>