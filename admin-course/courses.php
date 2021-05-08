<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
    Session::delete('admin');
    Session::put('old_url', '/admin-course/courses');
    return view('/admin/login');
}


// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$courses = $connection->select('courses')->paginate(15);


// ============================================
    // app banner settings
// ============================================
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
                    <?php if(Session::has('success')): ?>
                        <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage courses</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-course/add-course') ?>" class="view-btn-fill">Add courses</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Image</th>
                                    <th scope="col">Course title</th>
                                    <th scope="col">Tutor name</th>
                                    <th scope="col">Feature</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($courses->result()): 
                                foreach($courses->result() as $course): 
                                    $image = $course->course_poster ?  $course->course_poster : '/images/employee/demo.png';
                                    $tutor =  $course->tutor ? json_decode($course->tutor, true) : null;
                                ?>
                                    <tr>
                                        <td>
                                            <img src="<?= asset($image) ?>" alt="<?=$course->title ?>" class="table-img">
                                        </td>
                                        <td><?= ucfirst($course->title)?></td>
                                        <td><?= isset($tutor['title']) ? $tutor['title'] : $tutor ?></td>
                                      
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  data-id="<?= $course->course_id ?>" class="custom-control-input course_feature_btn" id="customSwitch_<?= $course->course_id ?>" <?= $course->is_feature ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $course->course_id ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($course->date_posted)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin-course/edit-course?cid='.$course->course_id) ?>" title="Edit course"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal"  data-target="#exampleModal_employee_delete" id="<?= $course->course_id ?>" class="delete_course_btn" title="Delete course"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $courses->links(); ?>

                                <?php if(!$courses->result()): ?>
                                    <div class="empty-table">There are no employees yet!</div>
                                <?php endif; ?>
                            </div>
                        </div><!-- table end-->
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
<div class="sign_up_modal modal fade" id="exampleModal_employee_delete" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <p class="text-center">Do you wish to delete this course?</p>
                                <input type="hidden" id="course_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin-course/ajax.php') ?>" id="submit_course_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>













<a href="<?= url('/admin-course/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){

// ===========================================
// FEATURE COURSES
// ===========================================
$(".course_feature_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var course_id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader
    $(".page_alert_danger").hide();
    $(".alert-success").hide();

    $.ajax({
        url: url,
        method: "post",
        data: {
            course_id: course_id,
            update_course_feature: 'update_course_feature'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
    });
    
});



// ==========================================
// OPEN DELETE COURSE MODAL
// ==========================================
$(".delete_course_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    $("#course_delete_id").val(id);
    $('.page_alert_danger').hide();
});





// ========================================
// DELETE COURSE
// ========================================
$("#submit_course_delete_btn").click(function(e){
    e.preventDefault();
    var id = $("#course_delete_id").val();
    var url = $(this).attr('data-url');
    $(".preloader-container").show() //show preloader
    $("#modal_delete_close").click();
    $(".alert-success").hide();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			course_id: id,
			delete_course_action: 'delete_course_action'
		},
		success: function(response){
            var info = JSON.parse(response);
            if(info.data){
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




// ========================================
// REMOVE PRELOADER
// ========================================
function remove_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
        $(".alert-success").hide();
    }, 2000);
}










// end
});
</script>