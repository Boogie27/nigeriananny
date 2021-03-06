<?php include('../Connection.php');  ?>
<?php
if(!Auth_employee::is_loggedin())
{
    return view('/employee/login');
}




if(Input::post('update_password'))
{
    if(Token::check())
    {
        $validate = new DB();
        $validation = $validate->validate([
            'old_password' => 'required|min:6|max:12',
            'new_password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|max:12|match:new_password',
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        if($validation->passed())
        {
            $old_password = $connection->select('employee')->where('e_id', Auth_employee::employee('id'))->first();
            if(!password_verify(Input::get('old_password'), $old_password->password))
            {
                Session::errors('errors', ['old_password' => '*Wrong old password, try again!']);
                return back();
            }

            $update = $connection->update('employee', [
                        'password' =>  password_hash(Input::get('new_password'), PASSWORD_DEFAULT),
                    ])->where('e_id', Auth_employee::employee('id'))->save();

            if($update->passed())
            {
                Session::flash('success', 'Password updated successfully!');
                Session::flash('success-m', 'Password updated successfully!');
                return back();
            }
        }
    }
    Session::flash('error', 'Network error, try again later!');
    return back();
}




// ======================================
// GET EMPLOYEE DETAILS
// ======================================
$employee = $connection->select('employee')->where('e_id', Auth_employee::employee('id'))->where('email', Auth_employee::employee('email'))->where('e_is_deactivate', 0)->first();
if(!$employee)
{
    Session::put('old_url', '/employee/account');
    Session::delete('employee');
    return view('/employee/login');
}



?>



<?php include('../includes/header.php');  ?>


<!-- top navigation-->
<?php include('../includes/navigation.php');  ?>

<?php include('../includes/side-navigation.php');  ?>

    

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
                                            <?php $profile_image = $employee->w_image ? $employee->w_image : '/images/employee/demo.png' ?>
                                            <img src="<?= asset($profile_image) ?>" alt="<?= $employee->first_name ?>" class="acc-img" id="profile_image_img">
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
                                       <div class="dob text-center text-success" style="font-size: 12px;"><span>Joined: </span><?= date('d M Y', strtotime($employee->date_joined)) ?></div>
                                         <ul class="anchor-acc">
                                            <li><a href="<?= url('/employee/account') ?>">Account</a></li>
                                            <li><a href="<?= url('/employee/job-offer') ?>">Job offers</a></li>
                                            <li><a href="<?= url('/employee/accepted')?>">Accepted offers</a></li>
                                            <li><a href="<?= url('/employee/job-history')?>">Offer history</a></li>
                                            <li><a href="<?= url('/employee/change-password')?>">Change password</a></li>
                                            <li><a href="<?= url('/employee/logout')?>">Logout</a></li>
                                        </ul>
                                   </div>
                                </div>
                                <div class="col-xl-9 col-lg-9">
                                    <div class="mobile-alert">
                                        <?php if(Session::has('error-m')): ?>
                                            <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error-m') ?></div>
                                        <?php endif; ?>
                                        <?php if(Session::has('success-m')): ?>
                                            <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success-m') ?></div>
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
                                        <?= csrf_token() ?>
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
<?php include('../includes/footer.php');  ?>



















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
                $(".nav-profile-img").attr('src', data.data)
                $("#profile_image_img").attr('src', data.data)
                img_preloader()
            }
        }
    });
});







// ========================================
//     GET ERROR PRELOADER
// ========================================
function img_preloader(string){
    setTimeout(function(){
        $(".e-loader-kamo").hide();
    }, 1000);
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
