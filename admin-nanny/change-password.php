<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/change-password');
  return view('/admin/login');
}

  

// ********* CHANGE ADMIN PASSWORD **************//
if(Input::post('update_password'))
{
    $state = false;
     $validate = new DB();
     $validation = $validate->validate([
            'email' => 'required',
            'old_password' => 'required|min:6|max:12',
            'new_password' => 'required|min:6|max:12',
            'confirm_new_password' => 'required|min:6|max:12|match:new_password',
     ]);

    if(!$validation->passed())
    {
        return back();
    }
     
    if($validation->passed())
    {
        $verify = $connection->select('admins')->where('id', 1)->first();
        if($verify->email != Input::get('email'))
        {
            $state = true;
            Session::delete('admin');
            Session::flash('error', '*Wrong email, login and try again');
        }

        if(!password_verify(Input::get('old_password'), $verify->password))
        {
            $state = true;
            Session::delete('admin');
            Session::flash('error', '*Wrong password, login and try again!');
        }
      
        if($state){
            Session::put('old_url', '/admin-nanny/change-password');
            return view('/admin/login');
        }
        
        $update = $connection->update('admins', [
                        'password' => password_hash(Input::get('new_password'), PASSWORD_DEFAULT),
                    ])->where('id', 1)->save();
        if($update)
        {
            Admin_auth::login(Input::get('email'));
            Session::flash('success', 'Password updated successfully!');
            return back();
        }
    }
    
    
}



// *************APP ADMIN***************//
$admin =  $connection->select('admins')->where('id', 1)->first();

// *************APP SETTINGS***************//
$app_settings =  $connection->select('settings')->where('id', 1)->first();


?>


<?php include('includes/header.php'); ?>


<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>

<!-- Main Header Nav For Mobile -->
<?php include('includes/mobile-navigation.php') ?>


<!-- Our Dashbord Sidebar -->
<?php include('includes/side-navigation.php') ?>


<!-- Our Dashbord -->
<div class="our-dashbord dashbord">
    <div class="dashboard_main_content">
        <div class="container-fluid">
            <div class="main_content_container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('includes/mobile-drop-nav.php') ?><!-- mobile-navigation -->
                    </div>
                    <div class="col-lg-12">
                    <?php if(Session::has('success')): ?>
                        <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage password</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/change-password') ?>">Change password</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <form action="<?= current_url() ?>" method="POST" id="subscription_form">
                            <div class="sr-head text-center"><h4>Edit password</h4></div><br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['email'])) : ?>
                                                <div class="text-danger"><?= $errors['email']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">Email:</label>
                                        <input type="email" name="email" class="form-control h50" value="<?= $admin->email ?? old('email')?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['old_password'])) : ?>
                                                <div class="text-danger"><?= $errors['old_password']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">Old password:</label>
                                        <input type="password" name="old_password" class="form-control h50" value="<?= $admin->password ?? old('old_password')?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['new_password'])) : ?>
                                                <div class="text-danger"><?= $errors['new_password']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">New password:</label>
                                        <input type="password" name="new_password" class="form-control h50" value="<?= old('new_password')?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['confirm_new_password'])) : ?>
                                                <div class="text-danger"><?= $errors['confirm_new_password']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">Confirm new password:</label>
                                        <input type="password" name="confirm_new_password" class="form-control h50" value="<?= old('confirm_new_password')?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text-right">
                                       <button name="update_password" class="btn btn-primary">Update password</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt50 mb50">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="copyright-widget text-center">
                            <p class="color-black2"><?= $app_settings->alrights ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>


















<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>

