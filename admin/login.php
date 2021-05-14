<?php include('../Connection.php');  ?>

<?php
    if(Admin_auth::is_loggedin())
    {
        return view('/admin-nanny');
    }

   if(Input::post('login'))
   {
        $validate = new DB();
       
        $validation = $validate->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:12',
        ]);

        if(!$validation->passed())
    {
        return back();
    }
        
        if($validation->passed())
        {
            $connection = new DB();
            $email_verify = $connection->select('admins')->where('email', Input::get('email'))->first();
            if(!$email_verify)
            {
                Session::errors('errors', ['email' => '*Wrong email, try again!']);
                return back();
            }

            if(!password_verify(Input::get('password'), $email_verify->password))
            {
                Session::errors('errors', ['password' => '*Wrong password, try again!']);
                return back();
            }

            
            if(Admin_auth::login(Input::get('email')))
            {
                if(Session::has('old_url'))
                {
                    $old_url = Session::get('old_url');
                    Session::delete('old_url');
                    Session::flash('success', 'You have loggedin successfully!');
                    return view($old_url);
                }

                Session::flash('success', 'You have loggedin successfully!');
                return view('/admin-nanny');
            }
        }
   }


   

// app banner settings
$banner =  $connection->select('settings')->where('id', 1)->first();
?>

<?php include('includes/header.php'); ?>


<!-- login start-->
<div class="" id="admin-login-form" role="tabpanel" aria-labelledby="home-tab">
    <div class="login_form" id="login_form">
        <div class="shop_form_container">
            <div class="admin-heading">
                <div class="app-img text-center">
                   <img src="<?= asset($banner->logo) ?>" alt="">
                </div>
                <h3 class="text-center">Admin Account</h3>
                <p class="text-center">Login as an admin</p>

                 <?php if(Session::has('error')): ?>
                    <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                <?php endif;?>
            </div>
            <form action="<?= current_url() ?>" method="post">
                    <div class="form-group">
                    <?php  if(isset($errors['email'])) : ?>
                        <div class="text-danger"><?= $errors['email']; ?></div>
                    <?php endif; ?>
                    <input type="email" class="form-control" name="email" value="<?= old('email') ?>" placeholder="Email Address">
                </div>
                <div class="form-group">
                    <?php  if(isset($errors['password'])) : ?>
                        <div class="text-danger"><?= $errors['password']; ?></div>
                    <?php endif; ?>
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <button type="submit" name="login" class="btn btn-log btn-block btn-thm2">Login</button>
            </form>
        </div>
    </div>
</div>
<!-- login end-->


<!-- footer -->
<?php  include('includes/footer.php') ?>

