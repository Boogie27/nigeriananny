<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/testimonial-detail?tid='.Input::get('tid'));
  return view('/admin/login');
}




// =====================================
// CHECK IF TESTIMONIAL WAS CLICK
// =====================================
if(!Input::exists('get') || !Input::get('tid'))
{
    return view('/admin-nanny/testimonial');
}



// ============================================
//  UPDATE EMPLOYER PROFILE
// ============================================
if(Input::post('update_testimonial'))
{
    if(Token::check())
    {
        $validate = new DB();
        $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'comment' => 'required|min:200|max:300',
        ]);

        if($validation->passed())
        {
            $update = new DB();
            $update->update('testimonial', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'comment' => Input::get('comment'),
                ])->where('id', Input::get('tid'))->save();
    
            if($update->passed())
            {
                Session::flash('success', 'Testimonial updated successfully!');
                return back();
            }
        }
    }
}









// ===========================================
// GET TESTIMONIAL
// ===========================================
$testimonial = $connection->select('testimonial')->where('id', Input::get('tid'))->first();
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
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
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
                                            <?php $profile_image = $testimonial->image ? $testimonial->image : '/images/testimonial/demo.png' ?>
                                            <img src="<?= asset($profile_image) ?>" alt="<?= $testimonial->first_name ?>" class="acc-img" id="profile_image_img">
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
                                                    <input type="text" name="first_name" class="form-control h50" value="<?= $testimonial->first_name ?? old('first_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['last_name'])) : ?>
                                                        <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Last name:</label>
                                                    <input type="text" name="last_name" class="form-control h50" value="<?= $testimonial->last_name ?? old('last_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="alert-x text-danger"></div>
                                                <label for="">Functions:</label>
                                                <div class="function-container" id="function_container">
                                                    <?php if($testimonial->function): 
                                                        $functions = json_decode($testimonial->function, true);?>
                                                        <div class="inner-function">
                                                            <?php foreach($functions as $key => $function): ?>
                                                                <span><?= $function ?> <a href="#" class="funciton_cancle_btn" id="<?= $key ?>"><i class="fa fa-times"></i></a></span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
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
                                                <textarea name="comment" cols="30" rows="5" class="form-control h50" placeholder="Write something..."><?= $testimonial->comment ?? old('comment') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button type="submit" name="update_testimonial" class="btn view-btn-fill float-right">Update...</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?= csrf_token() ?>
                                    </form>
                                </div>
                            </div>
                        </div><!-- content end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-copy-right">
    <p><?= $banner->alrights ?></p>
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
    var testimonial_id = $(".testimonial_id_input").attr('id');
    $(".e-loader-kamo").show();
    $(".page_alert_danger").hide();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('testimonial_id', testimonial_id);
    data.append('upload_testimonial_image', true);

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
//     GET EMPLOYER IMAGE
// ========================================
function get_employer_img(){
    var url = $(".ajax_url_page").attr('href');
    var testimonial_id = $(".testimonial_id_input").attr('id');

    $.ajax({
        url: url,
        method: "post",
        data: {
            testimonial_id: testimonial_id,
            get_testimonial_img: 'get_testimonial_img'
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






$("#Add_function_btn").click(function(e){
    e.preventDefault();
    $(".preloader-container").show() //show preloader

    add_testimonial_function();
});





// =======================================
// UPDATE TESTIMONIAL FUNCTIONS
// =======================================
$("#testimonial_function_input").keypress(function(e){
    if(e.which == 13 || e.keyCode == 13){
        e.preventDefault();
        $(".preloader-container").show() //show preloader
         add_testimonial_function();
    }
});




function add_testimonial_function(){
    var functions = $('#testimonial_function_input').val();
    $(".alert_0").html('');
    var url = $(".ajax_url_page").attr('href');
    var testimonial_id = $(".testimonial_id_input").attr('id');

    $.ajax({
        url: url,
        method: "post",
        data: {
            functions: functions,
            testimonial_id: testimonial_id,
            update_function_action: 'update_function_action'
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
    var testimonial_id = $(".testimonial_id_input").attr('id');

    $.ajax({
        url: url,
        method: "post",
        data: {
            testimonial_id: testimonial_id,
            get_function_action: 'get_function_action'
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
// TESTIMONIAL FUNCTION CANCLE
// ========================================
$('#function_container').on('click', '.funciton_cancle_btn', function(e){
    e.preventDefault();
    $(".alert-x").hide();
    var key = $(this).attr('id');
    var url = $(".ajax_url_page").attr('href');
    var testimonial_id = $(".testimonial_id_input").attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            key: key,
            testimonial_id: testimonial_id,
            function_cancle_action: 'function_cancle_action'
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