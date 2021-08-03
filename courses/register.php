<?php include('../Connection.php');  ?>


<?php 
 if(Auth_course::is_loggedin())
 {
    return view('/courses');
 }



// ******** REGISTER COURSE USER ***********//
if(Input::post('register'))
{
    if(Token::check())
    {
        $validate = new DB();
        
        $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|match:password',
            'email' => 'required|email|unique:course_users'
        ]);

        if($validation->passed())
        {
            $connection->create('course_users', [
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name'),
                'email' => Input::get('email'),
                'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
            ]);

            $remember_me = Input::get('remember_me') ? true : false;

            if(Auth_course::login(Input::get('email'), $remember_me))
            {
                Session::flash('success', 'Account created successfully');
                return view('/courses');
            }
        }
    }
}


?>




<?php include('includes/header.php');  ?>


<?php include('includes/navigation.php');  ?>


<?php
// ************* MORE COURSES *****************//
$others = $connection->select('courses')->where('is_feature', 1)->random()->limit(8)->get();



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
						<div class="heading">
							<h3 class="text-center">Register to start learning</h3>
							<p class="text-center">Have an account? <a class="text-thm" href="<?= url('/courses/login') ?>">Login</a></p>
						</div>
						<div class="details">
							<form action="<?= current_url()?>" method="POST">
                                <div class="row">
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['first_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" name="first_name" class="form-control" placeholder="First name">
                                        </div>
                                     </div>
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['last_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" name="last_name" class="form-control" placeholder="Last name">
                                        </div>
                                     </div>
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
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['password'])) : ?>
                                                    <div class="text-danger"><?= $errors['password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="password" name="password" class="form-control" placeholder="Password">
                                        </div>
                                     </div>
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['confirm_password'])) : ?>
                                                    <div class="text-danger"><?= $errors['confirm_password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm passowrd">
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
                                            <button type="submit" name="register" class="btn btn-log btn-block button">Register</button>
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


