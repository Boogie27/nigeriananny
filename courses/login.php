<?php include('../Connection.php');  ?>


<?php 
 if(Auth_course::is_loggedin())
 {
    return view('/courses');
 }



// ******** REGISTER COURSE USER ***********//
if(Input::post('login_user'))
{
    if(Token::check())
    {
        $validate = new DB();
        
        $validate->validate([
            'password' => 'required|min:6|max:12',
            'email' => 'required|email'
        ]);

        if(!$validate->passed())
        {
            return back();
        }

        if($validate->passed())
        {
            $verification = $connection->select('course_users')->where('email', Input::get('email'))->first();
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
            $logged_in = Auth_course::login(Input::get('email'), $remember_me);
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
                return view('/courses');
            }
        }
    } 
}






// ***************GOOGLE LOGIN AUTH ****************//
if(Input::post('google_login'))
{
    $google = new Google();
    Session::delete('employer_login');
    Session::delete('shop_login');
    Session::delete('employee_login');

    Session::put('google_auth', true);
    Session::put('course_google_login', true);
    return Redirect::to($google->auth_url());
}





// *************FACEBOOK LOGIN AUTH ***************//
if(Input::post('facebook_login'))
{
    $facebook = new Facebook();
    Session::delete('fb_employer_login');
    Session::delete('fb_shop_login');
    Session::delete('fb_employee_login');
    Session::put('facebook_auth', true);
    Session::put('course_facebook_login', true);
    return Redirect::to($facebook->login_url());
}



// ************* MORE COURSES *****************//
$others = $connection->select('courses')->where('is_feature', 1)->random()->limit(8)->get();

?>




<?php include('includes/header.php');  ?>


<?php include('includes/navigation.php');  ?>


<?php
//************ GET ALL VIDEOS ****************** 
$courses = $connection->select('courses')->where('is_feature', 1)->paginate(24);

?>

<div class="page-content-x">
    <div class="row" id="page-expand">
        <div class="col-lg-3" id="side-navigation-container">
            <?php include('includes/side-navigation.php');  ?>
        </div>
        <div class="col-lg-9 body-expand">
            <div class="body-content home-body-content">
                <div class="parent-container">
                    <div class="sign_up_form auth-form-course">
                        <?php if(Session::has('error')): ?>
                            <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                        <?php endif; ?>
                        <?php if(Session::has('success')): ?>
                            <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div>
                        <?php endif; ?>
						<div class="heading">
							<h3 class="text-center">Login to account</h3>
							<p class="text-center">Dont have an account? <a class="text-thm" href="<?= url('/courses/register') ?>">Register</a></p>
						</div>
						<div class="details">
							<form action="<?= current_url()?>" method="POST">
                                <div class="row">
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['email'])) : ?>
                                                    <div class="text-danger"><?= $errors['email']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="email" name="email" class="form-control" placeholder="Email">
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['password'])) : ?>
                                                    <div class="text-danger"><?= $errors['password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="password" name="password" class="form-control" placeholder="Password">
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="form-group custom-control custom-checkbox">
                                                <input type="checkbox" name="remember_me" class="custom-control-input" id="exampleCheck3">
                                                <label class="custom-control-label" for="exampleCheck3">Remember me</label>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <button type="submit" name="login_user" class="btn btn-log btn-block button">Login</button>
                                            <div class="text-right"><a href="<?= url('/courses/forgot-password')?>" class="text-danger">Forgot password</a></div>
                                            <div class="divide">
                                                <span class="lf_divider">Or</span>
                                                <hr>
                                            </div>
                                            <div class="row mt40">
                                                <div class="col-lg mb-3">
                                                    <button type="submit" name="facebook_login" class="btn btn-block color-white bgc-fb mb0"><i class="fa fa-facebook float-left mt5"></i> Facebook</button>
                                                </div>
                                                <div class="col-lg mb-3">
                                                    <button type="submit" name="google_login" class="btn btn2 btn-block color-white bgc-gogle mb0"><i class="fa fa-google float-left mt5"></i> Google</button>
                                                </div>
                                            </div>
                                        </div>
                                     </div>
                                </div>
                                <?= csrf_token() ?>
							</form>
						</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12"><!-- more courses container end -->
                           <br><br>
                           <div class="more-course">
                                <div class="title"><h4>More courses</h4></div>
                                <div class="more-course-body">
                                    <div class="row">
                                        <?php if(count($others)): 
                                        foreach($others as $other):    
                                        ?>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="<?= url('/courses/detail.php?cid='.$other->course_id) ?>"><img src="<?= asset($other->course_poster) ?>" alt="<?= $other->title ?>"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4><?= substr(ucfirst($other->title), 0, 30)?></h4></a>
                                                    </li>
                                                    <li>
                                                        <p><?= substr($other->description, 0, 50)?></p>
                                                    </li>
                                                    <li><?= stars($other->ratings, $other->rating_count)?></li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                           </div>
                        </div><!-- morder course container end -->
                    </div>
                </div>
            </div>
            <!-- footer -->
            <?php include('includes/footer.php') ?>
        </div>
    </div>
</div>


