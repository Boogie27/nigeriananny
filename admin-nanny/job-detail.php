<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/employees');
  return view('/admin/login');
}




// =====================================
// CHECK IF EMPLOYEE WAS CLICK
// =====================================
if(!Input::exists('get') && !Input::get('rid'))
{
    return view('/admin-nanny/employers');
}




// =========================================
// GET EMPLOYEE
// =========================================
$job = $connection->select('request_workers')->leftJoin('workers', 'request_workers.j_employee_id', '=','workers.employee_id')->leftJoin('employee', 'request_workers.j_employee_id', '=', 'employee.e_id')->where('request_id ', Input::get('rid'))->first(); 
if(!$job)
{
    return view('/admin-nanny/employers');
}



// =========================================
// GET EMPLOYEE REVIEW
// =========================================
$reviews = $connection->select('employee_reviews')->leftJoin('employers', 'employee_reviews.r_employer_id', '=', 'employers.id')->where('r_employee_id', $job->worker_id)->get();



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
                                <h4 class="title float-left">Job information</h4>
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/employees') ?>">Employees</a></li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-lg-12"> <!-- inner content start -->   
                            <div class="account-z">
                                <!-- featured jobs start-->
                                <?php if(Session::has('error')): ?>
                                    <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                                <?php endif; ?>
                                <?php if(Session::has('success')): ?>
                                    <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                <?php endif; ?>
                                <?php 
                                    $w_image = $job->w_image ? $job->w_image : '/employee/images/demo.png'; 
                                    $detail = json_decode($job->work_detail, true);
                                    $amount = $detail['amount_to'] ? money($detail['amount_form']).' - '.money($detail['amount_to']) : money($detail['amount_form']); ?>
                                <div class="job-body">
                                    <div class="jobs-info">
                                        <img src="<?= asset($w_image)?>" alt="">
                                        <ul class="ul">
                                            <li>
                                                <h4>
                                                    <?= ucfirst($detail['job_title'])?>
                                                    <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> <?= date('d M Y', strtotime($detail['date_added'])) ?></span>
                                                </h4>
                                            </li>
                                            <li><?= stars($job->ratings, $job->rating_count) ?></li>
                                            <li> <?= ucfirst($job->first_name.' '.$job->last_name)?></li>
                                            <li>
                                                <?php if($detail['job_type'] != 'live in'):
                                                $living = json_decode($detail['job_type'], true); ?>
                                                    <b>Job Location: </b><?= ucfirst($living['city'])?> | <?= ucfirst($living['state'])?> 
                                                <?php else: ?>
                                                    <?= $detail['job_type'] ?>
                                                <?php endif; ?>
                                                | <span class="text-warning money-amount"><?= $amount ?></span>
                                            </li>
                                            <li class="text-right j-action">
                                                <a href="#" class="text-danger" style="font-size: 12px;" data-toggle="modal" data-target="#employee_report_form" >Report</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- EDUCATION START--> 
                                    <?php if($job->education): 
                                    $educations = json_decode($job->education, true); ?>
                                    <div class="j-expirience">
                                        <div class="js-head">Education:</div>
                                        <?php foreach($educations as $education): ?>
                                        <ul class="inner-ex">
                                            <li><b>Qualification:</b> <?= ucfirst($education['qualification']) ?></li>
                                            <li><b>Institution: </b> <?= ucfirst($education['institution']) ?></li>
                                            <li><b>City: </b><?= ucfirst($education['city']) ?></li>
                                            <li><b>State: </b><?= ucfirst($education['state']) ?></li>
                                            <li><b>Country: </b><?= ucfirst($education['country']) ?></li>
                                            <li>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6"><b>Start date: </b><?= $education['start_date'] ?></div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <?php if(!$education['inview']): ?>
                                                                <b>End date: </b><?= $education['end_date'] ?>
                                                            <?php else: ?>
                                                            <b>End date: </b><span class="inview-x">inview</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                        </ul>
                                    <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                    <!-- EDUCATION START--> 

                                    <!-- ABILITY START-->
                                    <div class="j-bio">
                                        <div class="js-head">Bio:</div>
                                        <p><?= $job->bio ?></p>
                                        <ul class="ability-x">
                                            <li><b>Reading: </b><?= $job->reading ? 'Yes' : 'No'?></li>
                                            <li><b>Writing: </b><?= $job->writing ? 'Yes' : 'No'?></li>
                                            <li></li>
                                        </ul>
                                    </div>
                                    <!-- ABILITY END -->

                                    <div class="j-contact">
                                        <div class="js-head">Contact info:</div>
                                        <ul>
                                            <li><i class="fa fa-phone text-success"></i> <b>Phone:</b> <?= $job->phone ?></li>
                                            <li><i class="fa fa-envelope text-success"></i> <b>Email:</b> <?= $job->email ?></li>
                                            <li><i class="fa fa-home text-success"></i> <b>Address:</b> <?= $job->address ?></li>
                                            <li><i class="fa fa-circle text-success"></i> <b>City:</b> <?= $job->city ?></li>
                                            <li><i class="fa fa-users text-success"></i> <b>state:</b> <?= $job->state ?></li>
                                            <li><i class="fa fa-flag text-success"></i> <b>Country:</b> <?= $job->country ?></li>
                                        </ul>
                                    </div>
                                    
                                    <!-- WORK EXPERIENCE START -->
                                    <?php if($job->work_experience): 
                                    $experiences = json_decode($job->work_experience, true); ?>
                                    <div class="j-summary">
                                        <div class="j-summary-h">Work expirience:</div>
                                        <?php foreach($experiences as $experience): ?>
                                        <div class="experience-x">
                                            <ul>
                                                <li><b>Job title:</b> <?= ucfirst($experience['job_title']) ?></li>
                                                <li><b>Job function:</b> <?= $experience['job_function'] ?></li>
                                                <li><b>Employer:</b> <?= $experience['employer_name'] ?></li>
                                                <li><b>Employer phone:</b> <?= $experience['employer_phone'] ?></li>
                                                <li><b>Employer email:</b> <?= $experience['employer_email'] ?></li>
                                                <li><b>Description: </b></li>
                                            </ul>
                                            <div class="j-summary-detail">
                                                <img src="<?= asset('/images/icons/1.svg')?>" alt="">
                                                <p><?= $experience['description'] ?></p>
                                            </div>
                                            <ul>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6"><b>Start date: </b><?= $experience['start_date'] ?></div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <?php if(!$experience['inview']): ?>
                                                                <b>End date: <?= $experience['end_date'] ?></b>
                                                            <?php else: ?>
                                                                <b>End date: </b><span class="inview-x">inview</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                    <!-- WORK EXPERIENCE END -->

                                    <!-- SUMMARY START-->
                                    <div class="j-bio">
                                        <div class="js-head">Summary:</div>
                                        <p><?= $job->summary ?></p>
                                    </div>
                                    <!-- SUMMARY END -->
                                
                                </div>
                                <!-- featured jobs end-->
                            </div>
                        </div><!-- inner content end --> 

                        <!-- review start -->
                        <div class="col-lg-12"><br><br>
                            <div class="account-z">
                                <div class="head-x flex-item"><i class="fa fa-star"></i><h4>Employee review</h4></div>
                                <!-- review start -->
                                <div class="employee-review" id="employee_review_container">
                                    <?php if(count($reviews)): 
                                        foreach($reviews as $review):
                                        ?>
                                        <div class="emp-rev flex-item">
                                            <?php $review_image = $review->e_image ? $review->e_image : '/employer/images/employer/demo.png';  ?>
                                            <img src="<?= asset($review_image) ?>" alt="<?= $review->first_name ?>" class="review-img">
                                            <ul class="infos">
                                                <ul>
                                                    <li>
                                                        <?= employee_star($review->review_stars)?>
                                                        <span class="float-right text-success"><?= date('d M Y', strtotime($review->review_date)) ?></span>
                                                    </li>
                                                </ul>
                                                <li><b>Title: </b><?= ucfirst($review->title)?></li>
                                                <li><b>Review: </b><?= ucfirst($review->comment)?>
                                                </li>
                                                    <li class="text-right">
                                                        <a href="#" data-toggle="modal" data-target="#employee_delete_review" id="<?= $review->review_id ?>" class="text-danger delete_employee_review_modal">Delete</a>
                                                    </li>
                                            </ul>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <!-- review start -->
                            </div>
                        </div>
                        <!-- review end -->
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
                       
                        









<!-- Modal delete review -->
<div class="sign_up_modal modal fade" id="employee_delete_review" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_delete_review_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST">
                            <div class="heading">
                                <div class="alert-delete-review text-danger text-center"></div>
                                <p class="text-center">Do you wish to delete this review?</p>
                            </div>
                            <input type="hidden" class="delete_review_input" value="">
                            <button type="submit"  name="subscribe" class="subcribe_now_btn" style="display: none;"></button>
                            <button type="submit" class="btn btn-log btn-block bg-danger" id="delete_review_btn" style="color: #fff">Delete review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>
<a href="#" id="<?= $job->worker_id ?>" class="employer_id_input" style="display: none;"></a>




<?php  include('includes/footer.php') ?>







<script>
$(document).ready(function(){

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







// =======================================
//   PASS REVIEW ID TO REVIEW DELETE MODAL
// =======================================
$(".delete_employee_review_modal").click(function(e){
    e.preventDefault();
    var review_id = $(this).attr('id');
    $(".delete_review_input").val(review_id)
});






// =======================================
//   DELETE REVIEW
// =======================================
$("#delete_review_btn").click(function(e){
    e.preventDefault();
    $('.alert-delete-review').html('');
    var url = $(".ajax_url_page").attr('href');
    var review_id =  $('.delete_review_input').val();
    $(".preloader-container").show() //show preloader
    $(".close_delete_review_btn").click();

    $.ajax({
        url: url,
        method: 'post',
        data: {
            review_id: review_id,
            delete_employee_review: 'delete_employee_review',
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                $('.alert-delete-review').html(data.error.error);
            }else if(data.data){
                get_all_reviews();
            }
        }
    });
});






// =======================================
//   GET ALL EMPLOYEE REVIEWS
// =======================================
function get_all_reviews(){
    var url = $(".ajax_url_page").attr('href');
    var employe_id = $(".employer_id_input").attr('id');
    
    $.ajax({
        url: url,
        method: 'post',
        data: {
            employe_id: employe_id,
            get_all_employee_reviews: 'get_all_employee_reviews',
        },
        success: function(response){
            remove_dark_preloader();
            $("#employee_review_container").html(response);
        }
    });
}






// ================================
// REMOVE PRELOADER
// ================================
function remove_dark_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
    }, 1000);
}








});
</script>                