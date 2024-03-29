<?php include('../Connection_Admin.php');  ?>

<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin/general-settings');
  return view('/admin/login');
}





// =============================================
// UPDATE APP SETTINGS
// =============================================
if(Input::post('update_app_settings'))
{
	if(Token::check())
    {
		$validate = new DB();
		
		$validation = $validate->validate([
			'app_name' => 'required|min:1|max:50',
			'info_email' =>  'required|email',
			'business_hours' => 'required',
			'my_email' => 'email',
			'alrights' => 'required|min:3|max:50',
		]);

		if(!$validation->passed())
		{
			return back();
		}

		if($validation->passed())
		{
			$app_active = Input::get('app_active') == 'true' ? 1 : 0;
			$connection = new DB();
			$update_settings = $connection->update('settings', [
							'app_name' => Input::get('app_name'),
							'info_email' => Input::get('info_email'),
							'business_hours' => Input::get('business_hours'),
							'alrights' => Input::get('alrights'),
							'my_email' => Input::get('my_email'),
							'is_active' => $app_active,
						])->where('id', 1)->save();
			if($update_settings)
			{
				Session::flash('success', 'App settings updated successfully!');
				return back();
			}   
		}
	}
}



// ===============================================
// UPDATE GENERAL SETTINGS
// ===============================================
if(Input::post('update_general_settings'))
{
	if(Token::check())
    {
		$validate = new DB();
		
		$validation = $validate->validate([
			'phone' => 'required|min:11|max:11|number',
			'address' => 'required|min:3|max:200',
			'city' => 'required|min:1|max:50',
			'state' => 'required|min:1|max:50',
			'country' => 'required|min:1|max:50',
			'paystack_secrete' => 'required|min:3|max:50',
			'paystack_public' => 'required|min:3|max:50',
		]);

		if(!$validation->passed())
		{
			return back();
		}
		

		if($validation->passed())
		{
			$connection = new DB();
			$update_settings = $connection->update('settings', [
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
}







// ===============================================
// UPDATE SOCIAL MEDIA LINKS
// ===============================================
if(Input::post('social_media'))
{
	if(Token::check())
    {
		$update_settings = $connection->update('settings', [
			'facebook' => Input::get('facebook'),
			'twitter' => Input::get('twitter'),
			'linkedin' => Input::get('linkedin'),
			'instagram' => Input::get('instagram'),
		])->where('id', 1)->save();
		if($update_settings)
		{
			Session::flash('success', 'Social links updated successfully!');
			return back();
		}
	}   
}






// =================================================
// app banner settings
// =================================================
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
										<div class="col-lg-6">
											<div class="form-group">
												<?php  if(isset($errors['info_email'])) : ?>
													<div class="form-alert text-danger"><?= $errors['info_email']; ?></div>
												<?php endif; ?>
												<input type="email" name="info_email" class="form-control h50" value="<?= $setting->info_email ?? old('info_email') ?>" placeholder="App name">
											</div>
										</div>
										
										<div class="col-lg-6">
											<div class="form-group">
												<?php  if(isset($errors['business_hours'])) : ?>
													<div class="form-alert text-danger"><?= $errors['business_hours']; ?></div>
												<?php endif; ?>
												<input type="text" name="business_hours" class="form-control h50" value="<?= $setting->business_hours ?? old('business_hours') ?>" placeholder="Enter business hours">
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['my_email'])) : ?>
													<div class="form-alert text-danger"><?= $errors['my_email']; ?></div>
												<?php endif; ?>
												<input type="email" name="my_email" class="form-control h50" value="<?= $setting->my_email ?? old('my_email') ?>" placeholder="Enter personal email">
											    <label for="" class="text-warning" style="font-size: 10px;">Email for recieving notifications from buyers</label>
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
										<div class="col-lg-12">
											<div class="form-group">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input activate_site_btn" id="activate_site" value="true" <?= $setting->is_active ? 'checked' : '' ?>>
													<label class="custom-control-label" for="activate_site">Activate site</label>
												</div>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input activate_site_btn" id="deactivate_site" value="false" <?= !$setting->is_active ? 'checked' : '' ?>>
													<label class="custom-control-label" for="deactivate_site">Deactivate site</label>
												</div>
												<input type="hidden" name="app_active" id="activate_site_input" value="<?= $setting->is_active ? 'true' : 'false' ?>">
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-gorup">
												<button type="submit" name="update_app_settings" class="btn btn-info float-right">Update...</button>
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
									
										<div class="col-lg-12"><br><br>
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
									    <div class="col-lg-12">
											<h3 class="h3">Social media links</h3>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
												<?php  if(isset($errors['facebook'])) : ?>
													<div class="form-alert text-danger"><?= $errors['facebook']; ?></div>
												<?php endif; ?>
												<label for="">Facebook</label>
												<input type="text" name="facebook" class="form-control h50" value="<?= $setting->facebook ?? old('facebook') ?>" placeholder="Facebook link">
											</div>
										</div>
										<div class="col-lg-12">
										    <div class="form-group">
												<?php  if(isset($errors['twitter'])) : ?>
													<div class="form-alert text-danger"><?= $errors['twitter']; ?></div>
												<?php endif; ?>
												<label for="">Twitter</label>
												<input type="text" name="twitter" class="form-control h50" value="<?= $setting->twitter ?? old('twitter') ?>" placeholder="Twitter link">
											</div>
										</div>
										<div class="col-lg-12">
										    <div class="form-group">
												<?php  if(isset($errors['instagram'])) : ?>
													<div class="form-alert text-danger"><?= $errors['instagram']; ?></div>
												<?php endif; ?>
												<label for="">Instagram</label>
												<input type="text" name="instagram" class="form-control h50" value="<?= $setting->instagram ?? old('instagram') ?>" placeholder="Instagram link">
											</div>
										</div>
										<div class="col-lg-12">
										    <div class="form-group">
												<?php  if(isset($errors['linkedin'])) : ?>
													<div class="form-alert text-danger"><?= $errors['linkedin']; ?></div>
												<?php endif; ?>
												<label for="">Linkedin</label>
												<input type="text" name="linkedin" class="form-control h50" value="<?= $setting->linkedin ?? old('linkedin') ?>" placeholder="Linkedin link">
											</div>
										</div>
										<div class="col-lg-12">
										    <div class="form-group text-right">
											<button type="submit" name="social_media" class="btn btn-primary">Update...</button>
											</div>
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
										<?= csrf_token() ?>
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
// ========================================
// ASSIGN GENDER FIELD
// ========================================
var active_site = $(".activate_site_btn");
$.each($(".activate_site_btn"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < active_site.length; i++){
            if(index != i)
            {
               $($(active_site)[i]).prop('checked', false);
            }else{
                $($(active_site)[i]).prop('checked', true);
            }
        }
    });
});




// ====================================================
// ACTIVATE AND DEACTIVATE SITE BUTTON
// ====================================================
$(".activate_site_btn").click(function(){
    var input = $(this).val();
    $("#activate_site_input").val(input);
});






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
		   console.log(response)
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