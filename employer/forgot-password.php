<?php include('../Connection.php');  ?>

<?php
  if(Auth_employer::is_loggedin())
  {
      return view('/');
  }
  
 if(Input::post('forgot_password'))
 {
    $validate = new DB();
    
    $validation = $validate->validate([
        'email' => 'required|email',
    ]);

    if(!$validation->passed())
    {
        return back();
    }
    
    $settings = $connection->select('settings')->where('id', 1)->first();
    
    $emailExists = $connection->select('employers')->where('email', Input::get('email'))->first();
    if(!$emailExists)
    {
        Session::errors('errors', [ 'email' => '*email does not exist']);
        return back();
    }

    $oldReset = $connection->select('employer_reset_password')->where('reset_email', Input::get('email'))->first();
    if($oldReset)
    {
        $connection->delete('employer_reset_password')->where('reset_id ', $oldReset->reset_id )->save();
    }

      
    $token = password_hash(uniqid(), PASSWORD_DEFAULT);
    $createReset = $connection->create('employer_reset_password', [
                        'reset_email' => Input::get('email'),
                        'reset_token' => $token,
                ]);

    if(!$createReset)
    {
        Session::flash('success', 'Password reset error, please try again later.');
        return back();
    }

    $body = '';
    $url = url('/employer/new-password.php?tid='.$token);
    
    $body .=    '<div class="password_reset-forms">
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
        Session::put('get_passsword', true);
        Session::flash('success', 'Password reset token has been sent to your email.');
        return back();
    }
 }

?>
<?php include('../includes/header.php');  ?>


<!--  navigation-->
<?php include('../includes/navigation.php');  ?>

<?php include('../includes/side-navigation.php');  ?>



<div class="page-content">
    <div class="job-seeker-conatiner">
        <div class="sr-head"><h4>Forgot password</h4></div>
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
                   <div class="col-lg-12">
                        <div class="form-group">
                            <button type="submit" name="forgot_password" class="btn-fill">Receive password</button>
                            <p class="apply-p">Don't have an account? <br><a href="<?= url('/employer/register') ?>" class="text-primary">Register</a></p>
                        </div>
                    </div>
            
                </div>
            </div>
        </form>
    </div>
</div>




    
<!-- Our Footer -->
<?php include('../includes/footer.php');  ?>










