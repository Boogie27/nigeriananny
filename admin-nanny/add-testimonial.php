<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/add-testimonial');
  return view('/admin/login');
}






// ============================================
//  UPDATE EMPLOYER PROFILE
// ============================================
if(Input::post('create_testimonial'))
{
        $validate = new DB();
        $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'comment' => 'required|min:50|max:3000',
        ]);

        if($validation->passed())
        {
            $connection = new DB();
            $image = Cookie::has('testimoial_image') ? Cookie::get('testimoial_image') : null;
            $create = $connection->create('testimonial', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'comment' => Input::get('comment'),
                    'image' => $image,
                    'function' => json_encode(Session::get('functions'))
                ]);
    
            if($create->passed())
            {
                Session::delete('functions');
                Cookie::delete('testimoial_image');
                Session::flash('success', 'Testimonial created successfully!');
                return view('/admin-nanny/testimonial');
            }
        }

}









// ===========================================
// GET TESTIMONIAL
// ===========================================
$testimonial = $connection->select('testimonial')->where('id', 1)->first();
if(!$testimonial)
{
    return view('/admin-nanny/testimonial');
}




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
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Testimonial infomation</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/testimonial') ?>">testimonial</a></li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="col-lg-12"><!-- content start-->
                            <div class="mobile-alert">
                                <?php if(Session::has('error')): ?>
                                    <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                                <?php endif; ?>
                                <?php if(Session::has('success')): ?>
                                    <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                <?php endif; ?>
                                <div class="alert-error-ajax p-3 mb-2 alert-danger text-center" style="display: none;"></div>
                            </div>
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body"><br>
                                    <div class="img-conatiner-x">
                                        <div class="em-img">
                                            <?php $profile_image = Cookie::has('testimoial_image') ? Cookie::get('testimoial_image') : '/images/testimonial/demo.png'?>
                                            <img src="<?= asset($profile_image) ?>" alt="name" class="acc-img" id="profile_image_img">
                                            <?php if(Cookie::has('testimoial_image')): ?>
                                                <i class="fa fa-trash" id="profile_img_delete"></i>
                                            <?php endif; ?>
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
                                                    <input type="text" name="first_name" class="form-control h50" value="<?= old('first_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['last_name'])) : ?>
                                                        <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Last name:</label>
                                                    <input type="text" name="last_name" class="form-control h50" value="<?= old('last_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <?php  if(isset($errors['function'])) : ?>
                                                    <div class="text-danger"><?= $errors['function']; ?></div>
                                                <?php endif; ?>
                                                <div class="alert-x text-danger"></div>
                                                <label for="">Functions:</label>
                                                <div class="function-container" id="function_container">
                                                    <?php if(Session::has('functions')): 
                                                        $functions = Session::get('functions');?>
                                                        <div class="inner-function">
                                                            <?php foreach($functions as $key => $function): ?>
                                                                <span><?= $function ?> <a href="#" class="funciton_cancle_btn" id="<?= $key ?>"><i class="fa fa-times"></i></a></span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <input type="text" name="function" value="" style="display: none;">
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="alert_0 alert_all text-danger"></div>
                                                    <input type="text" id="testimonial_function_input" class="form-control h50" value="" placeholder="Examplye: CEO, founder">
                                                     <a href="#" id="Add_function_btn" class="float-right text-primary">Add</a>
                                                </div>
                                            </div>
                                           
                                            
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['comment'])) : ?>
                                                        <div class="text-danger"><?= $errors['comment']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Comment:</label>
                                                <textarea name="comment" cols="30" rows="5" class="form-control h50" placeholder="Write something..."><?= old('comment') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button type="submit" name="create_testimonial" class="btn view-btn-fill float-right">Submit...</button>
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
                       
                        




<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>
<a href="#" id="<?= Input::get('tid') ?>" class="testimonial_id_input" style="display: none;"></a>




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




// ======================================
// ADD TESTIMONIAL PROFILE IMAGE
// ======================================
$('.img-conatiner-x').on('change', '.profile_img_input', function(){
    var url = $(".ajax_url_page").attr('href');
    var image = $(".profile_img_input");
    $(".e-loader-kamo").show();
    $(".page_alert_danger").hide();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('add_testimonial_image', true);

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
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('There was an error, try again later!');
        }
    });
});







// ========================================
//     GET ADD TESTIMONIAL  IMAGE
// ========================================
function get_testimonial_img(){
    var url = $(".ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
            get_add_testimonial_img: 'get_add_testimonial_img'
        },
        success: function (response){
            $(".img-conatiner-x .em-img").html(response)
        }
    });
}







// ======================================
// DELETE TESTIMONIAL PROFILE IMAGE
// ======================================
$('.img-conatiner-x').on('click', '#profile_img_delete', function(){
    var url = $(".ajax_url_page").attr('href');
    $(".e-loader-kamo").show();
    $(".page_alert_danger").hide();

    $.ajax({
        url: url,
        method: "post",
        data: {
            delete_testimonial_img: 'delete_testimonial_img'
        },
        success: function (response){
           var data = JSON.parse(response);
           if(data.data){
                img_preloader();
            }else{
                $(".e-loader-kamo").hide();
                $(".page_alert_danger").show();
                $(".page_alert_danger").html('There was an error, try again later!');
            }
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('There was an error, try again later!');
        }
    });
});


// ========================================
//     GET ERROR PRELOADER
// ========================================
function img_preloader(string){
    setTimeout(function(){
        get_testimonial_img()
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






$("#Add_function_btn").click(function(e){
    e.preventDefault();
    $(".preloader-container").show() //show preloader

    add_testimonial_function();
});





// =======================================
// ADD TESTIMONIAL FUNCTIONS
// =======================================
$("#testimonial_function_input").keypress(function(e){
    if(e.which == 13 || e.keyCode == 13){
        e.preventDefault();
        $(".preloader-container").show() //show preloader
         add_testimonial_function();
    }
});




function add_testimonial_function(){
    $(".alert_0").html('');
    var functions = $('#testimonial_function_input').val();
    var url = $(".ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
            functions: functions,
            add_function_action: 'add_function_action'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                $(".alert_0").html(data.error.functions);
            }else if(data.data){
                $("#testimonial_function_input").val('');
                get_testimonial_function();
            }
            remove_preloader();
        },
        error: function(){
            remove_preloader();
            $(".alert-x").show();
            $(".alert-x").html('There was an error, try again later!');
        }
    });
}






// ========================================
// GET TESTIMONIAL FUNCTION 
// ========================================
function get_testimonial_function(){
    var url = $(".ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
            get_add_function_action: 'get_add_function_action'
        },
        success: function (response){
            $("#function_container").html(response);
            remove_preloader();
        },
        error: function(){
            remove_preloader();
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('There was an error, try again later!');
        }
    });
}






// ========================================
// ADD TESTIMONIAL FUNCTION CANCLE
// ========================================
$('#function_container').on('click', '.funciton_cancle_btn', function(e){
    e.preventDefault();
    $(".alert_0").html('');
    $(".alert-x").hide();
    var key = $(this).attr('id');
    var url = $(".ajax_url_page").attr('href');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            key: key,
            add_testimonial_function_cancle: 'add_testimonial_function_cancle'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                get_testimonial_function();
                remove_preloader();
            }
        },
        error: function(){
            remove_preloader();
            $(".alert-x").show();
            $(".alert-x").html('There was an error, try again later!');
        }
    });
});







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