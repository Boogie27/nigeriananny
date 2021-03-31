<?php include('../Connection.php');  ?>
<?php
if(!Auth_employer::is_loggedin())
{
    Session::put('old_url', '/employer/account');
    Session::put('error', '*Signup or Login to access that page!');
    return view('/');
}




if(Input::post('update_password'))
{
    $validate = new DB();
    $validation = $validate->validate([
        'old_password' => 'required|min:6|max:12',
        'new_password' => 'required|min:6|max:12',
        'confirm_password' => 'required|min:6|max:12|match:new_password',
    ]);

    if($validation->passed())
    {
        $old_password = $connection->select('employers')->where('id', Auth_employer::employer('id'))->first();
        if(!password_verify(Input::get('old_password'), $old_password->password))
        {
            Session::errors('errors', ['old_password' => '*Wrong old password, try again!']);
            return back();
        }

        $update = $connection->update('employers', [
                    'password' =>  password_hash(Input::get('new_password'), PASSWORD_DEFAULT),
                ])->where('id', Auth_employer::employer('id'))->save();

        if($update->passed())
        {
            Session::flash('success', 'Password updated successfully!');
            Session::flash('success-m', 'Password updated successfully!');
            return back();
        }
    }
}







// ======================================
// GET EMPLOYER DETAILS
// ======================================
$employer = $connection->select('employers')->where('id', Auth_employer::employer('id'))->where('email', Auth_employer::employer('email'))->where('e_deactivate', 0)->first();
if(!$employer)
{
    Session::put('old_url', '/employer/account');
    Session::delete('employer');
    return view('/employer/login');
}



?>



<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<!-- images/home/4.jpg -->
	

<!-- mobile navigation-->
<?php include('includes/mobile-navigation.php');  ?>


    

<!-- jobs  start-->
<div class="page-content">
    <div class="items-container">
        <div class="account-container">
            <div class="desktop-alert">
                <?php if(Session::has('success')): ?>
                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                <?php endif; ?>
                <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="account-x">
                        <div class="head-x flex-item"><i class="fa fa-key"></i><h4>Change password </h4> </div>
                        <div class="account-x-body">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3">
                                    <div class="img-conatiner-x">
                                        <div class="em-img">
                                            <?php $profile_image = $employer->e_image ? $employer->e_image : '/images/employer/demo.png' ?>
                                            <img src="<?= asset($profile_image) ?>" alt="<?= $employer->first_name ?>" class="acc-img" id="profile_image_img">
                                            <i class="fa fa-camera" id="profile_img_open"></i>
                                            <input type="file" class="profile_img_input" style="display: none;">
                                            <div class="text-danger alert_profile_img text-center"></div>
                                        </div>
                                        <!-- preloader -->
                                        <div class="e-loader-kamo">
                                            <div class="r">
                                                <div class="preload"></div>
                                            </div>
                                        </div>
                                        <div class="dob text-center text-success" style="font-size: 12px;"><span>Joined: </span><?= date('d M Y', strtotime($employer->e_date_joined)) ?></div>
                                        <ul class="anchor-acc">
                                            <li><a href="<?= url('/employer/account') ?>">Account</a></li>
                                            <li><a href="<?= url('/employer/job-offer') ?>">Job offeres</a></li>
                                            <li><a href="<?= url('/employer/accepted')?>">Accepted offers</a></li>
                                            <li><a href="<?= url('/employer/change-password')?>">Change password</a></li>
                                            <li><a href="<?= url('/employer/logout')?>">Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xl-9 col-lg-9">
                                    <div class="mobile-alert">
                                        <?php if(Session::has('error-m')): ?>
                                            <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error-m') ?></div>
                                        <?php endif; ?>
                                        <?php if(Session::has('success-m')): ?>
                                            <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success-m') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="rh-head">Change password</h3><br>
                                    <form action="<?= current_url() ?>" method="POST" id="change_password_x">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['old_password'])) : ?>
                                                    <div class="text-danger alert-pwd"><?= $errors['old_password']; ?></div>
                                                <?php endif; ?>
                                                <input type="password" name="old_password" class="last_name_input form-control" placeholder="Old password" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['new_password'])) : ?>
                                                    <div class="text-danger alert-pwd"><?= $errors['new_password']; ?></div>
                                                <?php endif; ?>
                                                <input type="password" name="new_password" class="last_name_input form-control" placeholder="New password" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['confirm_password'])) : ?>
                                                    <div class="text-danger alert-pwd"><?= $errors['confirm_password']; ?></div>
                                                <?php endif; ?>
                                                <input type="password" name="confirm_password" class="last_name_input form-control" placeholder="Confirm password" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group text-right">
                                                    <button type="submit" name="update_password" class="btn btn-primary view-btn-fill">Update...</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>



















    <script>
$(document).ready(function(){

// ===========================================
//      OPEN PROFILE IMAGE
// ===========================================
$('.img-conatiner-x').on('click', '#profile_img_open', function(){
     $(".profile_img_input").click();
     $(".alert_profile_img").html('');
});


// ============================================
//  ADD PROFILE IMAGE
// ============================================
$('.img-conatiner-x').on('change', '.profile_img_input', function(){
    var url = $(".ajax_url_page").attr('href');
    var image = $(".profile_img_input");
    $(".e-loader-kamo").show();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('upload_employee_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
            if(data.error){
                error_preloader(data.error.image);
            }else if(data.data){
                img_preloader();
            }
        }
    });
});







// ========================================
//     GET EMPLOYER IMAGE
// ========================================
function get_employer_img(){
    var url = $(".ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
            get_employee_img: 'get_employee_img'
        },
        success: function (response){
            $(".img-conatiner-x .em-img").html(response)
        }
    });
}





// ========================================
//     GET ERROR PRELOADER
// ========================================
function img_preloader(string){
    $(".e-loader-kamo").show();
    setTimeout(function(){
        get_employer_img()
        $(".e-loader-kamo").hide();
    }, 5000);
}





// ========================================
//     GET ERROR PRELOADER
// ========================================
function error_preloader(string){
    $(".e-loader-kamo").show();
    setTimeout(function(){
        $('.alert_profile_img').html(string);
        $(".e-loader-kamo").hide();
    }, 2000);
}


});
</script>
