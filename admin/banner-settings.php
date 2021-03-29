<?php include('../Connection.php');  ?>

<?php

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



// app banner settings
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
							<nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
								<h4 class="title float-left">App Banner Settings</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="#">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Settings</li>
								</ol>
							</nav>
						</div>
						<div class="col-lg-12">
							<form action="<?= current_url() ?>" method="post" class="">
								<div class="form-sm">
									<?php if(Session::has('success')): ?>
										<div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
									<?php endif;?>
									<div class="row">
					                      <div class="col-lg-12">
                                              Banners input here
                                          </div>
							    	</div>
								</div>
							</form>
						</div>
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


<?php  include('includes/footer.php') ?>