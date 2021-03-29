<?php include('../Connection.php');  ?>


<?php
$user = $connection->select('users')->where('id', Auth::user('id'))->where('email', Auth::user('email'))->first();
if(!$user)
{
    Session::put('old_url', current_url());
    Auth::logout();
    return Redirect::to('login.php');
}



// ==========================================
//          UPDATE USER DETAILS
// ==========================================
if(Input::post('update_detail'))
{
    $validate = new DB();
       
    $validation = $validate->validate([
        'email' => 'required|email',
        'first_name' => 'required|min:3|max:50',
        'last_name' => 'required|min:3|max:50',
        'phone' => 'required|min:11|max:15',
        'gender' => 'required',
    ]);

    $user_email = $connection->select('users')->where('email', Auth::user('email'))->where('id', Auth::user('id'))->first();
    if(!$user_email)
    {
        $other_email = $connection->select('users')->where('email', Input::get('email'))->first();
        if($other_email)
        {
            Session::flash('errors', ['email' => "*email already exist!"]);
            return back();
        }
    }

    $image_name = $user_email->user_image ? $user_email->user_image : null;
    if(Image::exists('image'))
    {

        if($user_email->user_image)
        {
            Image::delete('../'.$user_email->user_image);
        }

        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'users');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 100, 'height' => 100, 'size_allowed' => 1000000,'file_destination' => '../shop/images/users/']);
            
        $image_name = '/shop/images/users/'.$file_name;
        if(!$image->passed())
        {
            Session::flash('errors', ['profile_image' => $image->error()]);
            return back();
        }
    }



    
    if($validation->passed())
    {
        $update = $connection->update('users', [
            'first_name' => Input::get('first_name'),
            'last_name' => Input::get('last_name'),
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'gender' => Input::get('gender'),
            'birth_date' => Input::get('birth_date'),
            'user_image' => $image_name,
        ])->where('id', Auth::user('id'))->save();
        
        Auth::login(Input::get('email'));
        Session::put('success', 'Detail has been updated sucessfully!');
        return back();
    }
}


?>

<?php include('includes/header.php') ?>

<?php include('includes/dash-board-navigation.php'); ?>


<?php include('includes/account-mobile-navigation.php') ?>


<?php include('includes/side-bar.php'); ?>




<!-- Our Dashbord -->
<div class="our-dashbord dashbord">
		<div class="dashboard_main_content">
			<div class="container-fluid">
				<div class="main_content_container">
					<div class="row">
						<div class="col-lg-12">
								<!-- mobile side bar -->
									<?php include('includes/mobile-side-bar.php'); ?>
								<!-- mobile side bar end -->
						</div>
						<div class="col-lg-12">
                            <?php if(Session::has('success')): ?>
                            <div class="page_alert_success alert-success text-center p-3 mb-2"><?= Session::flash('success')?></div>
                            <?php endif; ?>
							<div class="page_alert_success alert-success text-center p-3 mb-2" style="display: none;"></div>
						</div>
						<div class="col-lg-12">
							<nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
								<h4 class="title float-left">Account</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="#">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Account</li>
								</ol>
							</nav>
						</div>
					
						<div class="col-xl-12">
							<div class="recent_job_activity">
                                <h4 class="title">Details</h4>
                            
							    <form action="<?= current_url() ?>" method="post" enctype="multipart/form-data" class="user_detail_form">
								    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="user_profile_img">
                                                <?php if(Auth::user('user_image')): ?>
                                                    <img src="<?= asset(Auth::user('user_image')); ?>" alt="<?= $user->first_name?>">
                                                <?php else: ?>
                                                    <img src="<?= asset('/shop/images/users/demo.png') ?>" alt="<?= $user->first_name?>">
                                                <?php endif; ?>
                                            </div>
										</div>
                                        <div class="col-lg-6">
											<div class="form-group">
                                                <?php  if(isset($errors['first_name'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['first_name'] ?></div>
                                                <?php endif; ?>
												<label for="">First name</label>
												<input type="text" name="first_name" class="form-control" value="<?= $user->first_name ?? old('first_name') ?>">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
											    <?php  if(isset($errors['last_name'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['last_name'] ?></div>
                                                <?php endif; ?>
												<label for="">Last name</label>
												<input type="text" name="last_name" class="form-control" value="<?= $user->last_name ?>">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
											    <?php  if(isset($errors['email'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['email'] ?></div>
                                                <?php endif; ?>
												<label for="">Email</label>
												<input type="email" name="email" class="form-control" value="<?= $user->email ?>">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
											    <?php  if(isset($errors['phone'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['phone'] ?></div>
                                                <?php endif; ?>
												<label for="">Phone number</label>
												<input type="text" name="phone" class="form-control" value="<?= $user->phone ?>">
											</div>
										</div>
										<div class="col-lg-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['gender'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['gender'] ?></div>
                                                <?php endif; ?>
                                                <label for="exampleFormControlInput9">Gender *</label><br>
                                                <select name="gender" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="male" <?= $user->gender == 'male' ? 'selected' : '';?>>Male</option>
                                                    <option value="female" <?= $user->gender == 'female' ? 'selected' : '';?>>Female</option>
                                                </select>
                                            </div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
											    <?php  if(isset($errors['birth_date'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['birth_date'] ?></div>
                                                <?php endif; ?>
												<label for="">Date of birth</label>
												<input type="date" name="birth_date" class="form-control" value="<?= $user->birth_date ? date('Y-m-d', strtotime($user->birth_date)) : '' ?>">
											</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['profile_image'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['profile_image'] ?></div>
                                                <?php endif; ?>
                                                <label for="">Profile Image</label><br>
                                                <input type="file" name="image" class="user_p_img">
                                                <label for="" class="upload_p_image">Upload Image...</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
											<div class="form-group">
												<button type="submit" name="update_detail" class="btn-fill  float-right">Submit</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="row mt50 mb50">
						<div class="col-lg-6 offset-lg-3">
							<div class="copyright-widget text-center">
								<p class="color-black2">Copyright Edumy © 2019. All Rights Reserved.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>







<!-- footer -->
<div style="position: relative; z-index: 1000;">
	<?php include('includes/footer.php') ?>
</div>





<script>
$(document).ready(function(){
    //   CLICK FILE BUTTON 
    $('.upload_p_image').click(function(e){
        $('.user_p_img').click();
    });

});

</script>