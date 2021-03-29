<?php include('../Connection.php');  ?>

<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin/general-settings');
  return view('/admin/login');
}



if(Input::post('update_general_settings'))
{
	$validate = new DB();
       
	$validation = $validate->validate([
		'app_name' => 'required|min:1|max:50',
		'business_hours' => 'required',
		'alrights' => 'required|min:3|max:50',
		'phone' => 'required|min:11|max:11|number',
		'address' => 'required|min:3|max:200',
		'city' => 'required|min:1|max:50',
		'state' => 'required|min:1|max:50',
		'country' => 'required|min:1|max:50',
		'paystack_secrete' => 'required|min:3|max:50',
		'paystack_public' => 'required|min:3|max:50',
	]);
	

	if($validation->passed())
	{
		$connection = new DB();
		$update_settings = $connection->update('settings', [
						'app_name' => Input::get('app_name'),
						'business_hours' => Input::get('business_hours'),
						'alrights' => Input::get('alrights'),
						'phone' => Input::get('phone'),
						'address' => Input::get('address'),
						'city' => Input::get('city'),
						'state' => Input::get('state'),
						'country' => Input::get('country'),
						'paystack_secrete' => Input::get('paystack_secrete'),
						'paystack_public' => Input::get('paystack_public'),
					])->where('id', 1)->save();
		if($update_settings)
		{
			Session::flash('success', 'Settings has been updated successfully!');
			return back();
		}   
	}
}


// app banner settings
$setting =  $connection->select('settings')->where('id', 1)->first();



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
								<h4 class="title float-left">General Settings</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">general settings</li>
								</ol>
							</nav>
						</div>
						<div class="col-lg-12">
							<form action="" method="post" class="">
								<div class="form-sm">
									<?php if(Session::has('success')): ?>
										<div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
									<?php endif;?>
									<div class="row">
										<div class="col-lg-12">
											<h3 class="h3">App settings</h3>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['app_name'])) : ?>
													<div class="form-alert text-danger"><?= $errors['app_name']; ?></div>
												<?php endif; ?>
												<input type="text" name="app_name" class="form-control h50" value="<?= $setting->app_name ?? old('app_name') ?>" placeholder="App name">
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['business_hours'])) : ?>
													<div class="form-alert text-danger"><?= $errors['business_hours']; ?></div>
												<?php endif; ?>
												<input type="text" name="business_hours" class="form-control h50" value="<?= $setting->business_hours ?? old('business_hours') ?>" placeholder="Enter business hours">
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['alrights'])) : ?>
													<div class="form-alert text-danger"><?= $errors['alrights']; ?></div>
												<?php endif; ?>
												<textarea name="alrights" class="form-control h50" placeholder="Copyrights"><?= $setting->alrights ?? old('alrights') ?></textarea>
											</div>
										</div>
							    	</div>
								</div>
								<div class="form-sm">
								   <div class="row">
									    <div class="col-lg-12">
											<h3 class="h3">App Payment</h3>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['paystack_secrete'])) : ?>
													<div class="form-alert text-danger"><?= $errors['paystack_secrete']; ?></div>
												<?php endif; ?>
												<label for="">Secrete key</label>
												<input type="text" name="paystack_secrete" class="form-control h50" value="<?= $setting->paystack_secrete ?? old('paystack_secrete') ?>" placeholder="Paystack key">
											</div>
											<?php if($setting->paystack_secrete): ?>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="paystack_secrete_btn" <?= $setting->is_paystack_activate ? 'checked' : '' ?>>
													<label class="custom-control-label" for="paystack_secrete_btn">Activate</label>
												</div>
											<?php endif; ?>
										</div>
										<br>
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['paystack_public'])) : ?>
													<div class="form-alert text-danger"><?= $errors['paystack_public']; ?></div>
												<?php endif; ?>
												<label for="">Public key</label>
												<input type="text" name="paystack_public" class="form-control h50" value="<?= $setting->paystack_public ?? old('paystack_public') ?>" placeholder="Paystack key">
											</div>
											<?php if($setting->paystack_public): ?>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="paystack_public_btn" <?= !$setting->is_paystack_activate ? 'checked' : '' ?>>
													<label class="custom-control-label" for="paystack_public_btn">Activate</label>
												</div>
											<?php endif; ?>
										</div>
								   </div>
								</div>
								<div class="form-sm">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="setting-img" id="app-logo-container">
											    <div class="s-inner-img">
													<?php if($setting->logo): ?>
														<img src="<?= asset($setting->logo) ?>" alt="<?= $setting->app_name?>">
													<?php else: ?>
														<img src="<?= asset('/shop/images/header-logo.png') ?>" alt="<?= $setting->app_name?>">
													<?php endif; ?>
											    </div>
											</div>
											<div class="form-group">
												<label for="" class="app_img_uploads" id="app_img_upload_btn">Upload app image...</label>
												<input type="file" hidden id="app_img_uploads_input" class="form-control h50">
											     <div class="alert_0 text-danger"></div>
											</div>
									    </div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="setting-img" id="app-footer-logo-container">
												<div class="s-inner-img">
													<?php if($setting->footer_logo): ?>
														<img src="<?= asset($setting->footer_logo) ?>" alt="<?= $setting->app_name?>">
													<?php else: ?>
														<img src="<?= asset('/shop/images/header-logo.png') ?>" alt="<?= $setting->app_name?>">
													<?php endif; ?>
												</div>
											</div>
											<div class="form-group">
												<label for="" class="app_img_uploads" id="app_footer_logo_btn">Upload footer image...</label>
												<input type="file" hidden id="app_footer_logo_input" class="form-control h50">
												<div class="alert_1 text-danger"></div>
											</div>
										</div>
										<div class="col-lg-12">
											<h3 class="h3">App contact</h3>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['phone'])) : ?>
													<div class="form-alert text-danger"><?= $errors['phone']; ?></div>
												<?php endif; ?>
												<input type="text" name="phone" class="form-control h50" value="<?= $setting->phone ?? old('phone') ?>" placeholder="Phone number">
											</div>
										</div>
									
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['address'])) : ?>
													<div class="form-alert text-danger"><?= $errors['address']; ?></div>
												<?php endif; ?>
												<textarea name="address" class="form-control h50" placeholder="Address"><?= $setting->address ?? old('address') ?></textarea>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<?php  if(isset($errors['city'])) : ?>
													<div class="form-alert text-danger"><?= $errors['city']; ?></div>
												<?php endif; ?>
												<input type="text" name="city" class="form-control h50" value="<?= $setting->city ?? old('city') ?>" placeholder="City">
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<?php  if(isset($errors['state'])) : ?>
													<div class="form-alert text-danger"><?= $errors['state']; ?></div>
												<?php endif; ?>
												<input type="text" name="state" class="form-control h50" value="<?= $setting->state ?? old('state') ?>" placeholder="State">
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-12">
											<div class="form-group">
												<?php  if(isset($errors['country'])) : ?>
													<div class="form-alert text-danger"><?= $errors['country']; ?></div>
												<?php endif; ?>
												<input type="text" name="country" class="form-control h50" value="<?= $setting->country ?? old('country') ?>" placeholder="Country">
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-gorup">
												<button type="submit" name="update_general_settings" class="btn btn-info float-right">Update...</button>
											</div>
										</div>
									
									</div>
								</div>
							</form>
								
						
						</div>
					</div>
					<div class="row mt50 mb50">
						<div class="col-lg-6 offset-lg-3">
							<div class="copyright-widget text-center">
								<p class="color-black2"><?= $setting->alrights ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>









<a href="<?= url('/admin/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>




<?php  include('includes/footer.php') ?>




















<script>
$(document).ready(function(){

// ======================================
// OPEN APP LOGO FILE FIELD
// =====================================
$('#app_img_upload_btn').click(function(e){
	$(".alert_0").html('');
    $("#app_img_uploads_input").click();
});



// =================================================
//  UPLOAD APP LOGO IMAGE
// =================================================\
$("#app_img_uploads_input").on('change', function(e){
    var url = $(".ajax_url_tag").attr('href');
    var image = $("#app_img_uploads_input");
	$(".preloader-container").show() //show preloader

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('app_logo', image);
    data.append('upload_app_logo_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var info = JSON.parse(response);
           if(info.error){
               $('.alert_0').html(info.error.app_logo)
           }else if(info.data){
                get_app_log();
           }else{
            $('.alert_0').html('*Something went worng!')
           }
        }
    });
});






function get_app_log(){
	var url = $(".ajax_url_tag").attr('href');

	  $.ajax({
        url: url,
        method: "post",
        data: {
			get_app_logos: 'get_app_logos'
		},
        success: function (response){
			remove_preloader()
            $("#app-logo-container").html(response);
        }
    });
}







// ======================================
// OPEN APP FOOTER LOGO FILE FIELD
// =====================================
$('#app_footer_logo_btn').click(function(e){
    $("#app_footer_logo_input").click();
});





// =================================================
//  UPLOAD APP FOOTER LOGO IMAGE
// =================================================\
$("#app_footer_logo_input").on('change', function(e){
    var url = $(".ajax_url_tag").attr('href');
    var image = $("#app_footer_logo_input");
	$(".preloader-container").show() //show preloader

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('footer_logo', image);
    data.append('upload_footer_logo_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var info = JSON.parse(response);
           if(info.error){
				$('.alert_1').html(info.error.footer_logo)
           }else if(info.data){
                get_footer_log();
           }else{
				$('.alert_1').html('*Something went worng!')
           }
        }
    });
});






// get footer logo
function get_footer_log(){
	var url = $(".ajax_url_tag").attr('href');

	  $.ajax({
        url: url,
        method: "post",
        data: {
			get_footer_logos: 'get_footer_logos'
		},
        success: function (response){
			remove_preloader()
            $("#app-footer-logo-container").html(response);
        }
    });
}








// ==================================================
// ACTIVATE PAYSTACK SECRETE KEY
// ==================================================
$("#paystack_secrete_btn").click(function(){
	toggle_paystack_key();
});



// ==================================================
// ACTIVATE PAYSTACK PUBLIC KEY
// ==================================================
$("#paystack_public_btn").click(function(){
      toggle_paystack_key();
});




function toggle_paystack_key(){
	var url = $(".ajax_url_tag").attr('href');
	$(".preloader-container").show() //show preloader
	
	$.ajax({
	url: url,
	method: "post",
	data: {
		paystack_is_activate: 'paystack_is_activate'
	},
	success: function (response){
		var info = JSON.parse(response);
		if(info.data){
			location.reload();
		}
	}
	});
}





// ========================================
// REMOVE PRELOADER
// ========================================
function remove_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
        $(".alert-success").hide();
    }, 2000);
}







});
</script>