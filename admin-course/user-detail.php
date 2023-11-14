<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-course/user-detail.php?uid='.Input::get('uid'));
  return view('/admin/login');
}




// =====================================
// CHECK IF EMPLOYEE WAS CLICK
// =====================================
if(!Input::exists('get') || !Input::get('uid'))
{
    return view('/admin-course/users');
}



// ============================================
//  UPDATE EMPLOYER PROFILE
// ============================================
if(Input::post('update_profile'))
{
        $validate = new DB();
        $validation = $validate->validate([
            'email' => 'required|email',
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'phone' => 'required|min:11|max:11|number',
            'city' => 'required|min:3|max:50',
            'state' => 'required|min:3|max:50',
            'country' => 'required|min:3|max:50',
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        $my_email = $connection->select('course_users')->where('email', Input::get('email'))->where('id', Input::get('uid'))->first();
        if(!$my_email)
        {
            $all_email = $connection->select('course_users')->where('email', Input::get('email'))->get();           
            if(count($all_email))
            {
                Session::errors('errors', ['email' => '*Email already exists']);
                return back();
            }
        }

        if($validation->passed())
        {
            $create = new DB();
            $create->update('course_users', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'email' => Input::get('email'),
                    'phone' => Input::get('phone'),
                    'city' => Input::get('city'),
                    'state' => Input::get('state'),
                    'country' => Input::get('country'),
                ])->where('id', Input::get('uid'))->save();
    
            if($create->passed())
            {
                Session::flash('success', 'Account updated successfully!');
                return back();
            }
        }

}








// ======================================
// GET EMPLOYER DETAILS
// ======================================
$user = $connection->select('course_users')->where('id', Input::get('uid'))->first();
if(!$user)
{
    return view('/admin-course/users');
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
                            <h4 class="title float-left">User infomation</h4>
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
                                <div class="options-x text-right">
                                    <div class="drop-down">
                                        <i class="fa fa-ellipsis-h dot-icon"></i>
                                        <ul class="drop-down-ul">
                                            <li><a href="#" data-toggle="modal"  data-target="#exampleModal_deactivate_user_delete"><?= $user->is_deactivate ? 'Deactivate' : 'Activate'?></a></li>                                        
                                            <li><a href="#"  data-toggle="modal"  data-target="#exampleModal_course_user_delete" id="<?= $user->id ?>" class="delete_course_user_btn">Delete</a></li>                                        
                                        </ul>
                                    </div>
                                </div>
                                    <div class="img-conatiner-x">
                                        <div class="em-img">
                                            <?php $profile_image = $user->image ? $user->image : '/courses/images/user/demo.png' ?>
                                            <img src="<?= asset($profile_image) ?>" alt="<?= $user->first_name ?>" class="acc-img" id="profile_image_img">
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
                                    </div>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['first_name'])) : ?>
                                                        <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">First name:</label>
                                                    <input type="text" name="first_name" class="form-control h50" value="<?= $user->first_name ?? old('first_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['last_name'])) : ?>
                                                        <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Last name:</label>
                                                    <input type="text" name="last_name" class="form-control h50" value="<?= $user->last_name ?? old('last_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['email'])) : ?>
                                                        <div class="text-danger"><?= $errors['email']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Email:</label>
                                                    <input type="text" name="email" class="form-control h50" value="<?= $user->email ?? old('email') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['phone'])) : ?>
                                                        <div class="text-danger"><?= $errors['phone']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Phone:</label>
                                                    <input type="text" name="phone" class="form-control h50" value="<?= $user->phone ?? old('phone') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['city'])) : ?>
                                                        <div class="text-danger"><?= $errors['city']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">City:</label>
                                                    <input type="text" name="city" class="form-control h50" value="<?= $user->city ?? old('city') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['state'])) : ?>
                                                        <div class="text-danger"><?= $errors['state']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">State:</label>
                                                    <input type="text" name="state" class="form-control h50" value="<?= $user->state ?? old('state') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['country'])) : ?>
                                                        <div class="text-danger"><?= $errors['country']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Country:</label>
                                                    <input type="text" name="country" class="form-control h50" value="<?= $user->country ?? old('country') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button type="submit" name="update_profile" class="btn view-btn-fill float-right">Update...</button>
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
                       
                        






<!-- Modal -->
<div class="sign_up_modal modal fade" id="exampleModal_course_user_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_delete_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete this user?</p>
                                <input type="hidden" id="course_user_delete_id" value="<?= Input::get('uid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-course/ajax.php') ?>" id="submit_course_user_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal deactivate-->
<div class="sign_up_modal modal fade" id="exampleModal_deactivate_user_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal_dropdown_close" id="modal_delete_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to updated this user's status?</p>
                                <input type="hidden" id="course_user_deactivate_id" value="<?= Input::get('uid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-course/ajax.php') ?>" id="submit_course_user_deactivate_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








<a href="<?= url('/admin-course/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>
<a href="#" id="<?= Input::get('uid') ?>" class="employer_id_input" style="display: none;"></a>




<?php  include('includes/footer.php') ?>







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
//  ADD USER IMAGE
// ============================================
$('.img-conatiner-x').on('change', '.profile_img_input', function(){
    var url = $(".ajax_url_page").attr('href');
    var image = $(".profile_img_input");
    var user_id = $(".employer_id_input").attr('id');
    $(".e-loader-kamo").show();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('user_id', user_id);
    data.append('upload_ourse_user_image', true);

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
                $("#profile_image_img").attr('src', data.data)
                img_preloader();
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





// ===========================================
// DEACTIVATE USERS
// ===========================================
$("#submit_course_user_deactivate_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var user_id =  $("#course_user_deactivate_id").val()
    $(".preloader-container").show() //show preloader
    $(".modal_dropdown_close").click();

    $.ajax({
        url: url,
        method: "post",
        data: {
            user_id: user_id,
            update_course_user_deactivate: 'update_course_user_deactivate'
        },
        success: function (response){
            var data = JSON.parse(response);
            location.reload();
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
    });
    
});








// ========================================
// DELETE USER
// ========================================
$("#submit_course_user_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#course_user_delete_id").val();
     var url = $(this).attr('data-url');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();
     

    $.ajax({
		url: url,
		method: 'post',
		data: {
			user_id: id,
			delete_course_user_action: 'delete_course_user_action'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }else{
                remove_preloader();
                $('.page_alert_danger').show();
                $('.page_alert_danger').html('*Network error, try again later');
            }
		},
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
	});
});



});
</script>                