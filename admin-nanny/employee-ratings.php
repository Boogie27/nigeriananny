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
if(!Input::exists('get') || !Input::get('eid') || !Input::get('rid'))
{
    return view('/admin-nanny/employments');
}


// *********** GET EMPLOYEE DETAIL ***************//
$employee = $connection->select('employee')->leftJoin('workers', 'employee.e_id', '=', 'workers.employee_id')->where('e_id', Input::get('eid'))->first();



// *********** GET EMPLOYEE REPORTS ***************//
$employee_reports = $connection->select('employer_reports')->where('employee_rid', Input::get('eid'))->get();



// *********** GET EMPLOYEE REVIEWS ***************//
$reviews = $connection->select('employee_reviews')->leftJoin('employers', 'employee_reviews.r_employer_id', '=', 'employers.id')->where('r_employee_id', Input::get('eid'))->get();



// *********** GET EMPLOYEE EMPLOYMENTS ***************//
$employments = 0;
$completed = 0;
$employee_employmets = $connection->select('request_workers')->where('j_employee_id', Input::get('eid'))->get();
foreach($employee_employmets as $employee_employmet)
{
    if($employee_employmet->is_accept)
    {
        $employments += 1;
    }
    if($employee_employmet->is_completed)
    {
        $completed += 1;
    }
    
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
                            <h4 class="title float-left">Employee ratings</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny/employments') ?>">Employments</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/employment-details?rid='.Input::get('rid')) ?>">Employment details</a></li>
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
                            <div class="account-x-body" id="account-x-body">
                                <div class="img-conatiner-x">
                                    <ul>
                                        <li>Ratings: <?= stars($employee->ratings, $employee->rating_count) ?></li>
                                    </ul>
                                    <div class="em-img">
                                        <?php $profile_image = $employee->w_image ? $employee->w_image : '/employee/images/demo.png' ?>
                                        <img src="<?= asset($profile_image) ?>" alt="<?= $employee->first_name ?>" class="acc-img" id="profile_image_img">
                                    </div>
                                    <div class="approved text-center">
                                        <span class="text-<?= $employee->is_active ? 'success' : 'danger'?>"><?= $employee->is_active ? 'online' : 'offline'?></span>
                                        <ul>
                                            <li>Reports: <?=count($employee_reports) ?></li>
                                            <li>Employmets: <?= $employments ?></li>
                                            <li>Jobs completed: <?= count($employee_employmets)?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                             
                             
                              <!-- inner content start -->
                              <div class="account-h">
                                <div class="inner-content-x">
                                    <div class="inner-h">
                                            <h4 class="">Employee reviews</h4>
                                    </div>
                                    <div class="employee-reviews" id="employee_reviews">
                                        <?php if(count($reviews)):
                                        foreach($reviews as $review):
                                            $e_image = $review->e_image ? $review->e_image : '/employer/images/demo.png'; 
                                        ?>
                                            <div class="review-body flex">
                                                <img src="<?= asset($e_image)?>" alt="<?= $reviews->first_name ?>">
                                                <ul class="ul-review-comment">
                                                    <li>
                                                        Raings:  <?= employee_star($review->review_stars)?>
                                                        <span class="float-right date"><?= date('d M Y', strtotime($review->review_date)) ?></span>
                                                    </li>
                                                    <li><b>Name:</b> <span><?= ucfirst($review->last_name.' '.$review->first_name) ?></span></li>
                                                    <li><b>Title:</b> <span><?= ucfirst($review->title) ?></span></li>
                                                    <li><b>Comment: </b><span><?= $review->comment ?></span></li>
                                                    <li class="text-right"><a href="#" data-id="<?= $review->review_id  ?>" data-toggle="modal" data-target="#modal_employee_delete_review_btn" id="employee_delete_review_btn" class="text-danger">Delete</a></li>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="alert text-center"> There are no reviews yet!</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- inner content end -->

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
                       
                        






<!-- Modal delete education -->
<div class="sign_up_modal modal fade" id="modal_employee_delete_review_btn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_cancle_request_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST">
                            <div class="heading">
                                <div class="alert-delete-review text-danger text-center"></div>
                                <p class="text-center">Do you wish to delete this review?</p>
                            </div>
                            <input type="hidden" id="review_employer_id_input" value="">
                            <button type="submit" class="btn btn-log btn-block bg-danger" id="delete_review_btn" style="color: #fff">Delete review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>
<a href="#" id="<?= Input::get('eid') ?>" class="employee_id_input" style="display: none;"></a>




<?php  include('includes/footer.php') ?>







<script>
$(document).ready(function(){
// *********** GET EMPLOYER REVIEW ID ********//
$("#employee_reviews").on('click', '#employee_delete_review_btn', function(e){
    e.preventDefault();
    var employer_id = $(this).attr('data-id')
    $("#review_employer_id_input").val(employer_id)
})




// ************* DELETE EMPLOYER REVIEW *************//
$("#delete_review_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var review_id =  $("#review_employer_id_input").val()
    $(".page_alert_danger").hide()
    $('.close_cancle_request_btn').click()
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            review_id: review_id,
            delete_employer_review: 'delete_employer_review'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                get_reviews()
            }else{
                remove_preloader()
                $(".page_alert_danger").show()
                $(".page_alert_danger").html('*Network error, try again later!')
            }
        }, 
        error: function(){
            remove_preloader()
            $(".page_alert_danger").show()
            $(".page_alert_danger").html('*Network error, try again later!')
        }
    });
});




// ************* DELETE ALL EMPLOYEE REVIEW *************//
function get_reviews(employee_id){
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id')

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            get_all_employee_review: 'get_all_employee_review'
        },
        success: function (response){
            remove_preloader();
            $("#employee_reviews").html(response)
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
    }, 1000);
}




})
</script>


                        