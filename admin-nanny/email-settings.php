<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/general-settings');
  return view('/admin/login');
}


// smtp = smtp.gmail.com
// smtp port = 465
// smtp_username: anonyecharles@gmail.com
// smtp_password = boogie190

// ===========================================
// EMAIL SETTINGS
// ===========================================
if(Input::post('update_email_settings'))
{
	$validate = new DB();
       
	$validation = $validate->validate([
		'from_name' => 'required|min:1|max:50',
		'from_email' => 'required',
		'smtp_host' => 'required|min:3|max:50',
		'smtp_port' => 'required|number',
		'smtp_username' => 'required|min:3|max:200',
		'smtp_password' => 'required|min:6|max:12',
	]);
	
	if($validation->passed())
	{
		$connection = new DB();
		$update_settings = $connection->update('settings', [
						'from_name' => Input::get('from_name'),
						'from_email' => Input::get('from_email'),
						'smtp_host' => Input::get('smtp_host'),
						'smtp_port' => Input::get('smtp_port'),
						'smtp_username' => Input::get('smtp_username'),
						'smtp_password' => Input::get('smtp_password'),
					])->where('id', 1)->save();
		if($update_settings)
		{
			Session::flash('success', 'Email settings has been updated successfully!');
			return back();
		}   
	}
}



// =======================================
// app banner settings
// =======================================
$settings =  $connection->select('settings')->where('id', 1)->first();
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
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Email settings</h4>
							<ol class="breadcrumb float-right">
								<li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">email settings</li>
							</ol>
                        </nav>
                    </div>
                    <div class="col-lg-12"> <!--content start -->
                        <form action="<?= current_url() ?>" method="post" class="">
                            <div class="form-sm">
                                <?php if(Session::has('success')): ?>
                                    <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                <?php endif;?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p class="info">This email will be used to send all mails from this website.</p>
                                        <br>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <?php  if(isset($errors['from_name'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['from_name']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="from_name" class="form-control h50" value="<?= $settings->from_name ?? old('from_name') ?>" placeholder="From name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <?php  if(isset($errors['from_email'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['from_email']; ?></div>
                                            <?php endif; ?>
                                            <input type="email" name="from_email" class="form-control h50" value="<?= $settings->from_email ?? old('from_email') ?>" placeholder="From email">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <?php  if(isset($errors['smtp_host'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['smtp_host']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="smtp_host" class="form-control h50" value="<?= $settings->smtp_host ?? old('smtp_host') ?>" placeholder="SMTP Host">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <?php  if(isset($errors['smtp_port'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['smtp_port']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="smtp_port" class="form-control h50" value="<?= $settings->smtp_port ?? old('smtp_port') ?>" placeholder="SMTP Port">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <?php  if(isset($errors['smtp_username'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['smtp_username']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="smtp_username" class="form-control h50" value="<?= $settings->smtp_username ?? old('smtp_username') ?>" placeholder="SMTP User name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <?php  if(isset($errors['smtp_password'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['smtp_password']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="smtp_password" class="form-control h50" value="<?= $settings->smtp_password ?? old('smtp_password') ?>" placeholder="SMTP Password">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-gorup">
                                            <button type="submit" name="update_email_settings" class="btn btn-info float-right">Update...</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> <!-- content end-->
                </div>
                <div class="row mt50 mb50">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="copyright-widget text-center">
							<p class="color-black2"><?= $settings->alrights ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>














<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



