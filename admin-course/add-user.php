<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-course/add-user');
  return view('/admin/login');
}




// ============================================
//  UPDATE EMPLOYER PROFILE
// ============================================
if(Input::post('create_account'))
{
        $validate = new DB();
        $validation = $validate->validate([
            'email' => 'required|email',
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'email' => 'required|unique:course_users',
            'phone' => 'required|min:11|max:11|number',
            'city' => 'required|min:3|max:50',
            'state' => 'required|min:3|max:50',
            'country' => 'required|min:3|max:50',
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        if($validation->passed())
        {
            $create = new DB();
            $create->create('course_users', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'email' => Input::get('email'),
                    'phone' => Input::get('phone'),
                    'city' => Input::get('city'),
                    'state' => Input::get('state'),
                    'country' => Input::get('country'),
                ]);
    
            if($create->passed())
            {
                Session::flash('success', 'Account created successfully!');
                return view('/admin-course/users');
            }
        }

}




// ===============================================
// app banner settings
// ===========================================
$banner =  $connection->select('settings')->where('id', 1)->first();

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
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Add user</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-course') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-course/users') ?>">Users</a></li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="col-lg-12"><!-- content start-->
                            <div class="mobile-alert">
                                <?php if(Session::has('error')): ?>
                                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                                <?php endif; ?>
                                <?php if(Session::has('success')): ?>
                                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body"><br>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['first_name'])) : ?>
                                                            <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">First name:</label>
                                                    <input type="text" name="first_name" class="form-control h50" value="<?=  old('first_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['last_name'])) : ?>
                                                            <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">Last name:</label>
                                                    <input type="text" name="last_name" class="form-control h50" value="<?=  old('last_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['email'])) : ?>
                                                            <div class="text-danger"><?= $errors['email']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">Email:</label>
                                                    <input type="text" name="email" class="form-control h50" value="<?=  old('email') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['phone'])) : ?>
                                                            <div class="text-danger"><?= $errors['phone']; ?></div>
                                                        <?php endif; ?>
                                                    </div> 
                                                    <label for="">Phone:</label>
                                                    <input type="text" name="phone" class="form-control h50" value="<?=  old('phone') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['city'])) : ?>
                                                            <div class="text-danger"><?= $errors['city']; ?></div>
                                                        <?php endif; ?>
                                                    </div> 
                                                    <label for="">City:</label>
                                                    <input type="text" name="city" class="form-control h50" value="<?=  old('city') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['state'])) : ?>
                                                            <div class="text-danger"><?= $errors['state']; ?></div>
                                                        <?php endif; ?>
                                                    </div> 
                                                    <label for="">State:</label>
                                                    <input type="text" name="state" class="form-control h50" value="<?=  old('state') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['country'])) : ?>
                                                            <div class="text-danger"><?= $errors['country']; ?></div>
                                                        <?php endif; ?>
                                                    </div> 
                                                    <label for="">Country:</label>
                                                    <input type="text" name="country" class="form-control h50" value="<?=  old('country') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button type="submit" name="create_account" class="btn view-btn-fill float-right">Create account</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- content end-->
                    </div>
                </div>
                <div class="row mt50 mb50">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="copyright-widget text-center">
                            <p class="color-black2"><?= $banner->alrights ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>
                       
                        


<?php  include('includes/footer.php') ?>               