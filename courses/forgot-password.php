<?php include('../Connection.php');  ?>


<?php 
 if(Auth_course::is_loggedin())
 {
    return view('/courses');
 }


// *********** RESET PASSWORD *************//
if(Input::post('receive_password'))
{
    $validate = new DB(); 
    $validation = $validate->validate([
        'email' => 'required|email',
    ]);

    if(!$validation->passed())
    {
        return back();
    }

    $emailExists = $connection->select('course_users')->where('email', Input::get('email'))->first();
    if(!$emailExists)
    {
        Session::flash('error', '*Email does not exist');
        return back();
    }
    
    $settings = $connection->select('settings')->where('id', 1)->first();

    $oldReset = $connection->select('course_user_reset_password')->where('reset_email', Input::get('email'))->first();
    if($oldReset)
    {
        $connection->delete('course_user_reset_password')->where('reset_id', $oldReset->reset_id)->save();
    }


    $token = password_hash(uniqid(), PASSWORD_DEFAULT);
    $createReset = $connection->create('course_user_reset_password', [
                        'reset_email' => Input::get('email'),
                        'reset_token' => $token,
                ]);

    if(!$createReset)
    {
        Session::flash('success', 'Password reset error, please try again later.');
        return view('/courses/forgot-password');
    }


    $body = '';
    $url = url('/courses/new-password.php?tid='.$token);
    
    $body .= '<div class="password_reset-forms">
                <div class="col-lg-12">
                    <div style="text-align: center;"><img src="'.asset($settings->logo).'" style="width: 50px; height: 50px; border-radius: 50%;" alt="'.$settings->app_name.'"></div>
                </div>
                <h4 style="text-align: center;">'.$settings->app_name.'</h4>
                <h3 style="text-align: center;">Reset password</h3>
                <div style="text-align: center;">
                    <p>We received a password reset request. The link to reset your password is here below.<br>
                        If you did not make this request please ignore this mail. This token expires after 30 minutes, Thank you.
                    </p>
                    <p>Here is the password reset link <a href="'.$url.'">Reset password</a></p>
                </div>
            </div>';

    $mail = new Mail();
    $send = $mail->mail([
				'to' => Input::get('email'),
				'subject' => 'forgot password',
				'body' => $body,
			]);
	
	if(!$send->passed())
	{
		return Redirect::to('forgot-password', ['error' => $send->error()]);
    }
    
    if($send->send_email())
    {
        Session::flash('success', 'Password reset token has been sent to your email.');
        return back();
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
							<h3 class="text-center">Forgot password</h3>
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
                                            <button type="submit" name="receive_password" class="btn btn-log btn-block button">Recieve passowrd</button>                                              
                                          </div>
                                     </div>
                                </div>
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


