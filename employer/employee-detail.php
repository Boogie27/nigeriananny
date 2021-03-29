<?php include('../Connection.php');  ?>

<?php 
if(!Input::exists('get') && !Input::get('wid'))
{
    return view('/');
}



// ========================================
//    WORKER STAR RATING
// ========================================
if(Input::post('rate_workers'))
{
    $validate = new DB();
    $validation = $validate->validate([
        'star_rate' => 'required',
        'title' => 'required|min:3|max:50',
        'comment' => 'required|min:3|max:200',
    ]);
    if($validation->passed())
    {
        $old_review = $connection->select('employee_reviews')->where('r_employer_id', Auth_employer::employer('id'))->where('r_employee_id', Input::get('wid'))->first();
        if($old_review)
        {
            Session::flash('error', '*Employee has already been rated by you!');
            return back();
        }
        $connection->create('employee_reviews', [
            'r_employer_id' => Auth_employer::employer('id'),
            'r_employee_id' => Input::get('worker_id'),
            'title' => Input::get('title'),
            'comment' => Input::get('comment'),
            'review_stars' => Input::get('star_rate'),
        ]);
         
        // update worker rating_count and ratings
        $worker = $connection->select('workers')->where('worker_id', Input::get('wid'))->first();
        $update = $connection->update('workers', [
                    'ratings' => $worker->ratings += Input::get('star_rate'),
                    'rating_count' => $worker->rating_count += 1,
                ])->where('worker_id', Input::get('wid'))->save();

        if($update->passed())
        {
            Session::flash('success', 'Employee rated successfully!');
            return back();
        }
    }

   
}


// =========================================
// CHECK IF USER IS LOGGEDIN
// =========================================
if(Input::post('check_online_employer'))
{
    if(!Auth_employer::is_loggedin())
    {
        $old_url = current_page();
        Session::put('old_url', $old_url);
        return view('/form');
    }
}






// =========================================
// GET EMPLOYEE
// =========================================
$job = $connection->select('request_workers')->leftJoin('workers', 'request_workers.j_employee_id', '=','workers.employee_id')->leftJoin('employee', 'request_workers.j_employee_id', '=', 'employee.e_id')->where('request_id ', Input::get('wid'))->where('is_accept', 1)->where('j_employer_id', Auth_employer::employer('id'))->first(); 
if(!$job)
{
    return view('/jobs');
}





// =========================================
// GET EMPLOYEE REVIEW
// =========================================
$reviews = $connection->select('employee_reviews')->leftJoin('employers', 'employee_reviews.r_employer_id', '=', 'employers.id')->where('r_employee_id', $job->worker_id)->get();





// =========================================
// GET REPORT OPTIONS
// =========================================
$reports =  $connection->select('reports')->where('is_feature', 1)->get();
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
           <div class="inner-job-detail" >
                <div class="j-header" id="job_h_v"><b><?= ucfirst($job->last_name.' '.$job->first_name) ?> details</b> </div>
                <div class="j-body">
                    <div class="row">
                        <div class="col-lg-7">
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
                        <!-- review start -->
                        <div class="col-lg-5">
                            <div class="account-x" id="account-x">
                                <div class="head-x flex-item"><i class="fa fa-star"></i><h4>Employee review</h4></div>
                                <!-- review start -->
                                <div class="employee-review" id="employee_review_container">
                                    <?php if(count($reviews)): 
                                        foreach($reviews as $review):
                                        ?>
                                        <div class="emp-rev flex-item">
                                            <?php $review_image = $review->e_image ? $review->e_image : '/employer/images/employer/demo.png';  ?>
                                            <img src="<?= asset($review_image) ?>" alt="<?= $review->first_name ?>" class="review-img">
                                            <ul class="info">
                                                <ul>
                                                    <li>
                                                        <?= employee_star($review->review_stars)?>
                                                        <span class="float-right text-success"><?= date('d M Y', strtotime($review->review_date)) ?></span>
                                                    </li>
                                                </ul>
                                                <li><b>Title: </b><?= ucfirst($review->title)?></li>
                                                <li><b>Review: </b><?= ucfirst($review->comment)?>
                                                </li>
                                                <?php if($review->r_employer_id == Auth_employer::employer('id')):?>
                                                    <li class="text-right">
                                                        <a href="#" data-toggle="modal" data-title="<?= $review->title ?>" data-comment="<?= $review->comment?>" data-star="<?= $review->review_stars?>" id="<?= $review->review_id ?>" data-target="#employee_edit_review" class="text-primary employee_star_rating_btn">Edit</a>
                                                        <a href="#" data-toggle="modal" data-target="#employee_delete_review" id="<?= $review->review_id ?>" class="text-danger delete_employee_review_modal">Delete</a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <!-- review start -->
                                <form action="<?= current_url() ?>" method="POST" id="employer_review_form">
                                    <div class="review-h text-center"><h4>Review and rate workers</h4></div>
                                    <?php if(Session::has('success')): ?>
                                        <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                    <?php endif; ?>
                                    <?php if(Session::has('error')): ?>
                                        <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                                    <?php endif; ?>
                                    <div class="col-lg-12">
                                       <?php  if(isset($errors['star_rate'])) : ?>
                                            <div class="text-danger"><?= $errors['star_rate']; ?></div>
                                        <?php endif; ?>
                                        <ul>
                                            <li><span>Ratings:</span>
                                                <i class="fa fa-star star_rating"></i>
                                                <i class="fa fa-star star_rating"></i>
                                                <i class="fa fa-star star_rating"></i>
                                                <i class="fa fa-star star_rating"></i>
                                                <i class="fa fa-star star_rating"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                             <?php  if(isset($errors['title'])) : ?>
                                                <div class="text-danger"><?= $errors['title']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="title" class="form-control" placeholder="Title" value="<?= old('title') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                       <div class="form-group">
                                           <?php  if(isset($errors['comment'])) : ?>
                                                <div class="text-danger"><?= $errors['comment']; ?></div>
                                            <?php endif; ?>
                                            <input type="hidden" name="star_rate" class="star_rate_input" value="">

                                            
                                            <input type="hidden" name="worker_id" value="<?= $job->worker_id ?>">
                                          <textarea  name="comment" class="form-control" cols="30" rows="3" placeholder="Write something..." required><?= old('comment') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-gorup">
                                             <button type="submit" name="rate_workers" class="btn-fill">Review</button>
                                         </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- review end -->
                    </div>
                </div>
           </div>
       </div>








<!-- Modal edit review-->
<div class="sign_up_modal modal fade" id="employee_edit_review" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_employee_review_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST" id="edit_employee_review_form">
                            <div class="rv-head text-center"><h3>Edit review</h3></div>
                            <div class="col-lg-12">
                                <div class="alert-review alert_0 text-danger"></div>
                                <ul>
                                    <li><span>Ratings:</span>
                                        <i class="fa fa-star edit_star_rating"></i>
                                        <i class="fa fa-star edit_star_rating"></i>
                                        <i class="fa fa-star edit_star_rating"></i>
                                        <i class="fa fa-star edit_star_rating"></i>
                                        <i class="fa fa-star edit_star_rating"></i>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="alert-review alert_1 text-danger"></div>
                                    <input type="text" class="review_title_input form-control" placeholder="Title" value="" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="alert-review alert_2 text-danger"></div>
                                    <input type="hidden" class="star_rate_input" value="">
                                    <input type="hidden" class="review_id_input" value="">
                                    <textarea  class="form-control review_comment_input" cols="30" rows="5" placeholder="Write something..." required></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-log btn-block btn-thm2" id="update_employee_review_btn">Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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







<!-- Modal report employee -->
<div class="sign_up_modal modal fade" id="employee_report_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_report_employee_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST">
                            <div class="report-head"><h4 style="color: #555"><i class="fa fa-thumbs-down text-danger"></i> Report <?= ucfirst($job->first_name)?></h4></div>
                            <div class="row">
                                <div class="col-lg-12 p-3"><div class="all_alert text-danger x_alert_0" style="font-size: 13px;"></div></div>
                                <?php if(count($reports)): 
                                    foreach($reports as $report):
                                    ?>
                                        <div class="col-lg-12 flex-item">
                                            <input type="checkbox" class="report_options_check" value="<?= $report->report_id ?>"><label for="" class="check-x-report"><?= ucfirst($report->report) ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="all_alert text-danger x_alert_1" style="font-size: 13px;"></div>
                                        <label for="">Comment: </label>
                                        <input type="hidden" class="report_checkbox_hidden_input" vale="">
                                        <textarea class="form-control report_comment_input" cols="30" rows="5" placeholder="Be more specific why you are reporting this employee"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-log btn-block bg-danger" id="report_review_btn" style="color: #fff">Report employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<input type="hidden" class="emplpoyee_id_input" id="<?= $job->worker_id ?>" data-id="<?= $job->request_id ?>" value="<?= $job->e_id ?>">
<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>


<!-- Our Footer -->
<?php include('includes/footer.php');  ?>


















<script>
$(document).ready(function(){
     
// =======================================
// STAR CLICK EFFECT
// =======================================
var star = $(".star_rating");

$.each(star, function(index, current){
	$(current).click(function(e){
		e.preventDefault();
        star_effect(index, star);
	});
});



function star_effect(index, star){
	for(var i = 0; i < star.length; i++){
        if(i <= index){
            $(star[i]).addClass('text-warning');
            $(".star_rate_input").val(index+1);
        }else{
            $(star[i]).removeClass('text-warning'); 
        }
    }
}







// =========================================
// EDIT STAR RATING
// =========================================
$(".employee_star_rating_btn").click(function(){
    var title = $(this).attr('data-title');
    var comment = $(this).attr('data-comment');
    var star = $(this).attr('data-star');
    var review_id = $(this).attr('id');
    
    get_modal_stars(star);
    $(".star_rate_input").val(star);
    $(".review_title_input").val(title);
    $(".review_comment_input").val(comment);
    $(".review_id_input").val(review_id)
});






// ===========================================
// GET MODAL STARS
// ===========================================
function get_modal_stars(star){
    var count = star - 1;
    var edit_stars = $("#edit_employee_review_form .edit_star_rating");
    for(var i = 0; i < edit_stars.length; i++){
            if(i <= count){
                $(edit_stars[i]).addClass('text-warning')
            }
    }
}




// =======================================
// MODAL STAR CLICK EFFECT
// =======================================
var stars = $("#edit_employee_review_form .edit_star_rating");

$.each(stars, function(index, current){
	$(current).click(function(e){
		e.preventDefault();
        star_effect(index, stars);
	});
});







// ========================================
// UPDATE EMPLOYEE REVIEW
// ========================================
$("#update_employee_review_btn").click(function(e){
    e.preventDefault();
    $(".alert-review").html('');
    var url = $(".ajax_url_page").attr('href');
    var star = $('.star_rate_input').val();
    var title = $('.review_title_input').val();
    var comment = $('.review_comment_input').val();
    var review_id =  $('.review_id_input').val();

    $(".preloader-container").show() //show preloader
    $(".close_employee_review_btn").click();

    $.ajax({
        url: url,
        method: 'post',
        data: {
            star: star,
            title: title,
            comment: comment,
            review_id: review_id,
            edit_employee_review: 'edit_employee_review',
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                error_handler(data.error);
            }else if(data.data){
                location.reload();
            }

            console.log(response)
        }
    });
});




// =======================================
//HANDLE ERROR REPORT
// =======================================
function error_handler(error){
    $(".alert_0").html(error.star);
    $(".alert_1").html(error.title);
    $(".alert_2").html(error.comment);
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
    var employe_id = $(".emplpoyee_id_input").val();
    
    $.ajax({
        url: url,
        method: 'post',
        data: {
            employe_id: employe_id,
            get_all_employee_reviews: 'get_all_employee_reviews',
        },
        success: function(response){
            remove_preloader();
            $("#employee_review_container").html(response);
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
    }, 2000);
}



// ========================================
// ASSIGN REPORT FIELD
// ========================================
var report = $(".report_options_check");
$.each($(".report_options_check"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < report.length; i++){
            if(index != i)
            {
               $($(report)[i]).prop('checked', false);
            }
        }
    });
});


$(report).click(function(){
   var report_id = $(this).val();
   $(".report_checkbox_hidden_input").val(report_id);
});


// ========================================
// REPORT AN EMPLOYEE
// ========================================
$("#report_review_btn").click(function(e){
   e.preventDefault();
   $(".all_alert").html('');
   var url = $(".ajax_url_page").attr('href');
   var work_id = $(".emplpoyee_id_input").attr('id');
   var employee_id = $(".emplpoyee_id_input").val();
   var request_id = $(".emplpoyee_id_input").attr('data-id');
   var comment = $(".report_comment_input").val();
   var reason =  $(".report_checkbox_hidden_input").val();

    $(".preloader-container").show() //show preloader
  
   $.ajax({
        url: url,
        method: 'post',
        data: {
            work_id: work_id,
            request_id: request_id,
            employee_id: employee_id,
            comment: comment,
            reason: reason,
            report_employee: 'report_employee',
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                error_alert(data.error);
                $(".preloader-container").hide() //hidepreloader
            }else if(data.data){
                location.reload();
            }
        }
    });
});



// =======================================
// handle modal error
// =======================================
function error_alert(error){
    $(".x_alert_0").html(error.reason);
    $(".x_alert_1").html(error.comment);
}





// end
});
</script>