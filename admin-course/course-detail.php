<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
    Session::delete('admin');
    Session::put('old_url', '/admin-course/course-detail.php?cid='.Input::get('cid'));
    return view('/admin/login');
}



// ******** CHECK IF COURSE WAS CLICKED ******//
if(!Input::exists('get') || !Input::get('cid'))
{
    return view('/admin-course/courses');
}



// ******* GET CATEGORIES ******************//
$course_categories = $connection->select('course_categories')->get();



// ******* GET COURSE ******************//
$course = $connection->select('courses')->where('course_id', Input::get('cid'))->first();
if(!$course)
{
    return view('/admin-course/courses');
}


// ******* GET COURSE LIKES******************//
$likes = $connection->select('course_likes')->where('likes', 1)->where('like_course_id', Input::get('cid'))->get();


// ******* GET COURSE DISLIKES******************//
$dislikes = $connection->select('course_likes')->where('dislikes', 1)->where('like_course_id', Input::get('cid'))->get();


// ************* COURSE REVIEWS ****************//
$reviews = $connection->select('course_reviews')->leftJoin('course_users', 'course_reviews.course_user_id', '=', 'course_users.id')->where('course_id', Input::get('cid'))->get();


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
                            <h4 class="title float-left">Course detail</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-course') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-course/courses') ?>">Courses</a></li>
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
                        </div>
                        <div class="account-x">
                            <div class="account-x-body" id="account-x-body"><br>
                                <ul class="ul-course-detail">
                                   <li>Ratings:
                                        <?= stars($course->ratings, $course->rating_count) ?>
                                    </li>
                                    <li><h4>Development tutorial</h4></li>
                                    <li class="course-img">
                                        <img src="<?= asset($course->course_poster) ?>" alt="<?= $course->title?>">
                                        <span class="course-duration"><?= $course->duration ?></span>
                                    </li>
                                    <li class="course-thumb-likes">
                                        <span><i class="fa fa-thumbs-up"></i> <?= count($likes) ?></span>
                                        <span><i class="fa fa-thumbs-down"></i> <?= count($dislikes) ?></span>
                                    </li>
                                    <li class="course-content">
                                        <h4>Course Description:</h4>
                                        <?php if($course->description):?>
                                            <p><?= $course->description ?></p>
                                        <?php else: ?>
                                            <p>Empty</p>
                                        <?php endif; ?>
                                    </li>

                                    <li class="course-content">
                                        <h4>What you will learn:</h4>
                                        <?php if($course->description):?>
                                            <ul class="w-y-learn">
                                                <?php $learns = json_decode($course->learn, true);
                                                foreach($learns as $learn):?>
                                                    <li><i class="fa fa-check"></i><p><?= $learn ?></p></li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p>Empty</p>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                    <li class="course-content">
                                        <h4>Who the course is for:</h4>
                                        <?php if($course->course_for):?>
                                            <p><?= $course->course_for ?></p>
                                        <?php else: ?>
                                            <p>Empty</p>
                                        <?php endif; ?>
                                    </li>
                                    <li class="course-content">
                                        <h4>Course detail:</h4>
                                        <p>Size: <?= $course->course_size?></p>
                                        <p>Duration: <?= $course->duration?></p>
                                        <p>Uploaded on: <?= date('d M Y', strtotime($course->date_posted))?></p>
                                    </li>
                                    <li>
                                        <h4>Course tutor:</h4><br>
                                        <?php if($course->tutor): 
                                            $tutor = json_decode($course->tutor, true); ?>
                                            <div class="autho-info">
                                                <?php $tutor_image = $tutor["image"] ? $tutor["image"] : "/courses/images/tutor/demo.png"; ?>
                                                <img src="<?= asset($tutor_image) ?>" alt="author-name">
                                                <ul>
                                                    <li>
                                                        <h4><?= $tutor["name"]?></h4>
                                                    </li>
                                                    <li class="auto-title"><b><?= $tutor["title"] ?></b></li>
                                                    <li><p><?= $tutor["about"]?></p></li>
                                                </ul>
                                            </div>
                                        <?php else: ?>
                                            <p>No tutor</p>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                                <div class="start-review"> <!-- review start-->
                                    <div class="title"><h4>Student reviews</h4></div>
                                    <div class="review-body" id="student_course_review">
                                        <ul>
                                            <li>Ratings:
                                                <?= stars($course->ratings, $course->rating_count) ?>
                                            </li>
                                        </ul>
                                        <?php if(count($reviews)): 
                                            foreach($reviews as $review):    
                                            $user_image = $review->image ? $review->image : '/courses/images/user/demo.png';
                                            ?>
                                            <div class="review-comments"><!-- student review start -->
                                                <a href="<?= url('/admin-course/user-detail?uid='.$review->course_user_id) ?>">
                                                    <img src="<?= asset($user_image) ?>" alt="<?= $review->first_name?>">
                                                </a>
                                                <ul>
                                                    <li><h5><?= ucfirst($review->last_name.' '.$review->first_name)?></h5></li>
                                                    <li>
                                                        <?= ratings($review->review_stars) ?>
                                                        <span class="review-time"><?= date('d M Y', strtotime($review->review_date))?></span>
                                                    </li>
                                                    <li>
                                                        <p><?= $review->comment ?></p>
                                                    </li>
                                                    <li class="review-edit">
                                                        <a href="#"  data-id="<?= $review->course_user_id ?>" data-toggle="modal"  data-target="#exampleModal_course_user_delete" class="delete_course_user_btn">Delete</a>
                                                    </li>
                                                </ul>
                                            </div><!-- student review end -->
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                        <div class="error-alert">
                                            <div class="error-alert-content">There are no reviews yet!</div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="text-right mt-3">
                                            <i class="fa fa-angle-left text-primary"></i>
                                            <i class="fa fa-angle-left text-primary"></i>
                                            <a href="<?= url('/admin-course/edit-course.php?cid='.Input::get('cid'))?>" class="text-primary">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- review end-->
                        </div>
                    </div><!-- content end-->
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
                                <p class="text-center">Do you wish to delete this review?</p>
                                <input type="hidden" id="course_user_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin-course/ajax.php') ?>" id="submit_course_user_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<a href="<?= url('/admin-course/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>
<a href="#" id="<?= Input::get('cid')?>" class="course_id_input" style="display: none;"></a>




<?php  include('includes/footer.php') ?>









<script>
$(document).ready(function(){

// *********** OPEN DELETE USER MODAL ***************//
$(".delete_course_user_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('data-id');
    $("#course_user_delete_id").val(id);
    $('.page_alert_danger').hide();
});






// ************ DELETE COURSE REVIEW ***************//
$("#submit_course_user_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#course_user_delete_id").val();
     var course_id = $(".course_id_input").attr('id');
     var url = $(this).attr('data-url');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();
     

    $.ajax({
		url: url,
		method: 'post',
		data: {
			user_id: id,
            course_id: course_id,
			delete_course_user_review: 'delete_course_user_review'
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

                        