<?php include('../Connection.php');  ?>

<?php


// ==================================
//   GET  APP SITE BANNERS 
// ==================================
$site =  $connection->select('settings')->where('id', 1)->first();

if(Input::post('receive_password'))
{
    $validate = new DB(); 
    $validation = $validate->validate([
        'email' => 'required|email',
    ]);

    $emailExists = $connection->select('users')->where('email', Input::get('email'))->first();
    if(!$emailExists)
    {
        Session::flash('error', '*email does not exist');
        return back();
    }
    
    $settings = $connection->select('settings')->where('id', 1)->first();

    $oldReset = $connection->select('reset_password')->where('reset_email', Input::get('email'))->first();
    if($oldReset)
    {
        $connection->delete('reset_password')->where('reset_password_id', $oldReset->reset_password_id)->save();
    }


    $token = password_hash(uniqid(), PASSWORD_DEFAULT);
    $createReset = $connection->create('reset_password', [
                        'reset_email' => Input::get('email'),
                        'reset_token' => $token,
                ]);

    if(!$createReset)
    {
        Session::flash('success', 'Password reset error, please try again later.');
        return Redirect::to('forgot-password');
    }


    $body = '';
    $url = url('/shop/new-password.php?tid='.$token);
    
    $body .= '<div class="password_reset-forms">
                <div class="col-lg-12">
                    <div style="text-align: center;"><img src="'.asset($settings->logo).'" alt="'.$settings->app_name.'"></div>
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
<?php include('includes/header.php') ?>

<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php include('includes/search-bar.php') ?>


<section class="inner_page_breadcrumb" style="background-image: url('<?= asset($site->home_banner); ?>');">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 offset-xl-3 text-center">
                <div class="breadcrumb_content">
                    <h4 class="page_title">Forgot password</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/shop') ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Forgot password</li>
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

<section>
    <!-- login start-->
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="login_form shop_form_container">
            <form action="<?= current_url() ?>" method="post">
                <div class="heading">
                    <h3 class="text-center">Forgot password</h3>
                    <p class="text-center">An email will be sent to you with the instruction on how to reset your password.</p>
                    <?php if(Session::has('error')): ?>
                        <div class="alert-danger text-center p-3"><?= Session::flash('error') ?></div>
                    <?php endif; ?>
                    <?php if(Session::has('success')): ?>
                        <div class="alert-success text-center p-3"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <?php  if(isset($errors['email'])) : ?>
                        <div class="text-danger"><?= $errors['email']; ?></div>
                    <?php endif; ?>
                    <input type="email" class="form-control" name="email" placeholder="Email Address">
                </div>
                <button type="submit" name="receive_password" class="btn btn-log btn-block btn-thm2">Receive password</button>
                <hr>
            </form>
        </div>
    </div>

   
    <!-- login end-->
</section>

<!-- footer -->
<?php include('includes/footer.php') ?>



