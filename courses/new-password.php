<?php include('../Connection.php');  ?>


<?php 
 if(Auth_course::is_loggedin())
 {
    return view('/courses');
 }


 if(!Input::exists('get') && Input::get('tid'))
 {
    return view('/courses/login');
 }
 
 


//  ********** REST PASSWORD ************//
 if(Input::post('reset_password'))
 {
    if(Token::check())
    {
        $validate = new DB(); 
        $validation = $validate->validate([
                'new_password' => 'required|min:6|max:12',
                'confirm_password' => 'required|min:6|max:12|match:new_password'
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        $reset = $connection->select('course_user_reset_password')->where('reset_token', Input::get('reset_token'))->first();
        if($reset)
        {
            $update_password = $connection->update('course_users', [
                'password' => password_hash(Input::get('new_password'), PASSWORD_DEFAULT)
            ])->where('email', $reset->reset_email)->save();

            if($update_password)
            {
                $connection->delete('course_user_reset_password')->where('reset_token', Input::get('reset_token'))->save();
                Session::flash('success', 'Password reset successfully, you can login!');
                return view('/courses/login');
            }
        }
    }
 }
?>




<?php include('includes/header.php');  ?>


<?php include('includes/navigation.php');  ?>










<div class="page-content-x">
    <div class="row" id="page-expand">
        <div class="col-lg-3" id="side-navigation-container">
            <?php include('includes/side-navigation.php');  ?>
        </div>
        <div class="col-lg-9 body-expand">
            <div class="body-content home-body-content">
                <div class="parent-container forgot-password">
                    <div class="sign_up_form auth-form-course">
                        <?php if(Session::has('error')): ?>
                            <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                        <?php endif; ?>
                        <?php if(Session::has('success')): ?>
                            <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div>
                        <?php endif; ?>
						<div class="heading">
							<h3 class="text-center">Reset password</h3>
							<p class="text-center">Dont have an account? <a class="text-thm" href="<?= url('/courses/register') ?>">Register</a></p>
						</div>
						<div class="details">
							<form action="<?= current_url()?>" method="POST">
                                <div class="row">
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['new_password'])) : ?>
                                                    <div class="text-danger"><?= $errors['new_password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="password" name="new_password" class="form-control" placeholder="Password">
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['confirm_password'])) : ?>
                                                    <div class="text-danger"><?= $errors['confirm_password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password">
                                            <input type="hidden" name="reset_token" value="<?= Input::get('tid') ?>">
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                          <div class="form-group">
                                            <button type="submit" name="reset_password" class="btn btn-log btn-block button">Reset password</button>                                              
                                          </div>
                                     </div>
                                </div>
                                <?= csrf_token() ?>
							</form>
						</div>
                    </div>
                </div>
            </div>
            <!-- footer -->
            <?php include('includes/footer.php') ?>
        </div>
    </div>
</div>


<!-- $2y$10$w9sWWqlDloXbT3kA.rbhgewK8dHfKRoOOsChF22uLQ8G3ZUciENZy -->