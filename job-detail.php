<?php include('Connection.php');  ?>

<?php 
if(!Input::exists('get') && !Input::get('wid'))
{
    return view('/');
}




// =======================================================
// DELETE EMPLOYER WORKER VIEWS EVERY NEW DAY
// =======================================================
$dailyViews = $connection->select('worker_daily_view')->where('wdv_employer_id', Auth_employer::employer('id'))->first();
if($dailyViews)
{
    $today = date('Y-m-d', strtotime('+ 1day'));
    if($today >= $dailyViews->expire_date)
    {
        $connection->delete('worker_daily_view')->where('wdv_employer_id', Auth_employer::employer('id'))->save();
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
// JOB VIEW COUNTER
// =========================================
if(Auth_employer::is_loggedin())
{
    $data = false;
    $cookie_expiry = 2419200;
    $w_id = Input::get('wid');
    $jobview = $connection->select('workers')->where('worker_id', Input::get('wid'))->first();
    if($jobview)
    {
        if(Cookie::exists('viewed_job'))
        {
            $oldView = json_decode(Cookie::get('viewed_job'), true);
            if(!array_key_exists($w_id, $oldView))
            {
                $oldView[$w_id] = ['worker_id' => $w_id];

                $updateViews = $connection->update('workers', [
                            'job_views' => $jobview->job_views + 1,
                        ])->where('worker_id', Input::get('wid'))->save();
                if($updateViews)
                {
                    $data = true;
                }
            }
        }else{
            $oldView[$w_id] = ["worker_id" => $w_id];

            $updateViews = $connection->update('workers', [
                        'job_views' => $jobview->job_views + 1,
                    ])->where('worker_id', Input::get('wid'))->save();
            if($updateViews)
            {
                $data = true;
            }
        }
           
        
        if($data)
        {
            $viewd = json_encode($oldView);
            Cookie::put('viewed_job', $viewd, $cookie_expiry);
        }
    }
}







// ============================================
// GET WORK DETAILS
// ============================================
$job = $connection->select('workers')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('worker_id', Input::get('wid'))->where('job_approved', 1)->where('is_deactivate', 0)->where('is_job_feature', 1)->first(); 
if(!$job)
{
    return view('/jobs');
}




// =======================================================
//      CHECK IF EMPLOYER HAS SUBSCRIBED
// =======================================================
$daily_view = null;
$subscription = $connection->select('employer_subscriptions')->where('s_employer_id', Auth_employer::employer('id'))->where('is_expire', 0)->first();

if($subscription)
{
    $is_requested = $connection->select('request_workers')->where('j_employer_id', Auth_employer::employer('id'))->where('r_worker_id', Input::get('wid'))->where('is_completed', 0)->first();
    $daily_view = $connection->select('worker_daily_view')->where('s_reference', $subscription->reference)->where('wdv_employer_id', Auth_employer::employer('id'))->first();
  
    if(!$daily_view && $is_requested == null)
    {
        $expire_date = date('Y-m-d', strtotime('+ 1day'));
        $worker[$job->worker_id] = ['woker_id' => $job->worker_id];
        $worker_ids = json_encode($worker);

        $connection->create('worker_daily_view', [
                's_reference' => $subscription->reference,
                'wdv_employer_id' => Auth_employer::employer('id'),
                'worker_id' => $worker_ids,
                'count' => 1,
                'expire_date' => $expire_date
        ]);
    }else if($is_requested == null){
        $currntCount = $daily_view->count + 1;
        if($daily_view->count < 2)
        {
            $old_workerID = json_decode($daily_view->worker_id, true);
            if(!array_key_exists($job->worker_id, $old_workerID))
            {
                $old_workerID[$job->worker_id] = ['woker_id' => $job->worker_id];
                $workerID = json_encode($old_workerID);
                $connection->update('worker_daily_view', [
                    'worker_id' => $workerID,
                    'count' => $daily_view->count + 1
                ])->where('wdv_employer_id', Auth_employer::employer('id'))->save(); 
            }
        }
        if($currntCount == 2){
            if(!$daily_view->is_complete)
            {
                $connection->update('worker_daily_view', [
                    'is_complete' => 1
                ])->where('wdv_employer_id', Auth_employer::employer('id'))->save(); 
            }
        }
    }
}






// ======================================================
//      CHECK AND GET WORK THAT HAS BEEN VIEWED
// ======================================================
$viewed = false;
if($subscription)
{
    $is_requested = $connection->select('request_workers')->where('j_employer_id', Auth_employer::employer('id'))->where('r_worker_id', Input::get('wid'))->where('is_completed', 0)->first();
    if($is_requested)
    {
        $viewed = true;
    }else{
        $daily_views = $connection->select('worker_daily_view')->where('s_reference', $subscription->reference)->where('wdv_employer_id', Auth_employer::employer('id'))->first();
        if($daily_views)
        {
            $workers = json_decode($daily_views->worker_id, true);
            if(array_key_exists(Input::get('wid'), $workers))
            {
                $viewed = true;
            }
        }
    }
}







// =========================================
// CHECK IF WORKER HAS BEEN REQUESTED
// =========================================
$is_requested = false;
$request_worker = $connection->select('request_workers')->where('j_employer_id', Auth_employer::employer('id'))->where('r_worker_id', Input::get('wid'))->where('is_completed', 0)->first();
if($request_worker)
{
    $is_requested = true;
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
           <div class="inner-job-detail" >
                <div class="j-header" id="job_h_v">
                    <b>Job seeker</b> 
                    <?php if($daily_view && $daily_view->is_complete && !$viewed && !$is_requested): ?>
                        <div class="alert-daily_v text-danger"><i class="fa fa-bell"></i> You have exceeded your maximum view chances</div>
                    <?php endif; ?>
                    <?php if($is_requested): ?>
                        <div class="alert-daily_v text-warning"><i class="fa fa-bell"></i> You have requested for this worker!</div>
                    <?php endif; ?>
                </div>
                <div class="j-body">
                    <div class="row">
                        <div class="col-lg-4" id="apply_now_1">
                            <form action="" method="" class="p-apply-container">
                                <div class="apply-h"><h4>Hire worker here</h4></div>
                                <div class="apply-container">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="all_alert alert_1 text-danger"></div>
                                                <input type="text" class="first_name_input form-control h50" placeholder="Frist name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                            <div class="all_alert alert_2 text-danger"></div>
                                                <input type="text" class="last_name_input form-control h50" placeholder="Last name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                            <div class="all_alert alert_3 text-danger"></div>
                                                <input type="text" class="phone_input form-control h50" placeholder="Phone number" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                            <div class="all_alert alert_4 text-danger"></div>
                                                <input type="number" class="amount_input form-control h50" placeholder="Amount" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                            <div class="all_alert alert_6 text-danger"></div>
                                                <input type="text" class="city_input form-control h50" placeholder="City" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                            <div class="all_alert alert_7 text-danger"></div>
                                                <input type="text" class="state_input form-control h50" placeholder="State" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="all_alert alert_8 text-danger"></div>
                                               <textarea  class="address_input form-control h50" cols="30" rows="3" placeholder="Job address" required></textarea>
                                            </div>
                                        </div>
                                    
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="all_alert alert_9 text-danger"></div>
                                                <textarea class="message_input form-control h50" cols="30" rows="5" placeholder="Message..."></textarea>
                                                <label for="" class="cv-label">Max 400 characters</label>

                                                <?php $amount = !$job->amount_to ? money($job->amount_form) : money($job->amount_form).' - '.money($job->amount_to); 
                                                 if($job->amount_to): ?>
                                                 <p style="font-size: 13px;" class="text-center">Employee salary can be negotiated with the employee before any form of employment</p>
                                                 <p style="font-size: 13px;" class="text-success text-center"><i class="fa fa-money"></i><b> Salary:</b> <?= $amount ?></p>
                                                <?php else: ?>
                                                    <h5 class="text-success text-center"><i class="fa fa-money"></i> <b>Salary: <?= $amount ?></b></h5>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="col-lg-12">
                                <div class="adds-news">
                                    <div class="job-alert-banner"><!-- job-alert jobs start-->
                                        <!-- <div class="alert-header">
                                            <h3>Jobs in Nigeria</h3>
                                        </div>
                                        <div class="alert-body">
                                            <p><b>1280</b> jobs found</p>
                                            <a href="#">Create job alert</a>
                                        </div> -->
                                    </div><!-- job-alert jobs start-->

                                    <div class="advert-banner-2">
                                        <a href="#"><img src="<?= asset('/images/adverts/4.jpg')?>" alt=""></a>
                                    </div>
                                    <div class="advert-banner-2">
                                        <a href="#"><img src="<?= asset('/images/adverts/4.jpg')?>" alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <!-- featured jobs start-->
                            <?php if(Session::has('error')): ?>
                                <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                            <?php endif; ?>
                            <?php if(Session::has('success')): ?>
                                <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                            <?php endif; ?>
                           <?php 
                                $savedJob = saved_jobs($job->worker_id);
                                $w_image = $job->w_image ? $job->w_image : '/employee/images/demo.png'; 
                                $amount = !$job->amount_to ? money($job->amount_form) : money($job->amount_form).' - '.money($job->amount_to);?>
                            <div class="job-body">
                                <div class="jobs-info">
                                    <img src="<?= asset($w_image)?>" alt="">
                                    <ul class="ul">
                                        <li>
                                            <h4>
                                                <?= ucfirst($job->job_title)?>
                                                <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> <?= date('d M Y', strtotime($job->date_added)) ?></span>
                                            </h4>
                                        </li>
                                        <li><?= stars($job->ratings, $job->rating_count) ?></li>
                                        <li> <?= ucfirst($job->first_name.' '.$job->last_name)?></li>
                                        <li>
                                            <?php if($job->job_type != 'live in'):
                                            $living = json_decode($job->job_type, true); ?>
                                                <b>Job Location: </b><?= ucfirst($living['city'])?> | <?= ucfirst($living['state'])?> 
                                            <?php else: ?>
                                                <?= $job->job_type ?>
                                            <?php endif; ?>
                                            | <span class="text-warning money-amount"><?= $amount ?></span>
                                        </li>
                                        <li class="text-right j-action">
                                            <?php if(Auth_employer::is_loggedin()): ?>
                                                <a href="<?= url('/ajax.php') ?>" title="Save job" class="work_wishlist_btn item-action" id="<?= $job->worker_id ?>">
                                                    <i class="fa <?= $savedJob ? 'fa-heart text-danger' : 'fa-heart-o text-primary'?>"></i>
                                                    <span class="save_job alert_success">Job has been saved</span>
                                                </a>
                                            <?php endif; ?>
                                            <i class="fa fa-eye  text-primary" title="views"></i> <span style="font-size: 13px;"><span><?= ucfirst($job->job_views) ?></span></span>
                                        </li>
                                    </ul>
                                </div>
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

                                <!-- CONTACT START-->
                                <div class="j-contact">
                                    <div class="js-head">Contact info:</div>
                                        <?php if($subscription && $viewed):?>
                                        <ul>
                                            <li><i class="fa fa-phone text-success"></i> <b>Phone:</b> <?= $job->phone ?></li>
                                            <li><i class="fa fa-envelope text-success"></i> <b>Email:</b> <?= $job->email ?></li>
                                            <li><i class="fa fa-home text-success"></i> <b>Address:</b> <?= $job->address ?></li>
                                            <li><i class="fa fa-circle text-success"></i> <b>City:</b> <?= $job->city ?></li>
                                            <li><i class="fa fa-users text-success"></i> <b>state:</b> <?= $job->state ?></li>
                                            <li><i class="fa fa-flag text-success"></i> <b>Country:</b> <?= $job->country ?></li>
                                        </ul>
                                        <?php endif;?>
                                     <div class="unsub">
                                        <?php if($daily_view && $daily_view->is_complete && !$viewed):?>
                                            <p class="text-center alert-danger p-2"><i class="fa fa-bell"></i> You have exceeded your maximum view chances for the day</p>
                                        <?php endif; ?>
                                        <?php if(!$subscription):?>
                                            <p class="text-danger">
                                                Employer signup, Login or Subscribe to view contact information
                                                <span class="float-right"><a href="<?= url('/subscription') ?>" class="text-primary">Subscribe</a></span>
                                            </p>
                                         <?php endif; ?>
                                    </div>
                                </div>
                                <!-- CONTACT END-->

                                <!-- SUMMARY START-->
                                <div class="j-bio">
                                    <div class="js-head">Summary:</div>
                                     <p><?= $job->summary ?></p>
                                </div>
                                <!-- SUMMARY END -->

                                 <!-- SAFTY START-->
                                 <div class="j-safety">
                                    <div class="js-head">Important saftey tips:</div>
                                    <ul>
                                        <li>1. Do not make any payment without confirming with the Jobberman Customer Support Team.</li>
                                        <li>2. If you think this advert is not genuine, please report it via the Report Job link below.</li>
                                    </ul>
                                </div>
                                <!-- SAFETY END-->

                                 <!-- CV START-->
                                 <?php if($subscription && $viewed):
                                    if($job->cv): 
                                    $cv = json_decode($job->cv, true);  
                                    ?>
                                    <div class="j-safety">
                                        <div class="js-head">CV, Resume:</div>
                                        <ul>
                                            <li class="text-center"><a href="<?= url($cv['cv']) ?>" class="btn btn-success">Download CV</a></li>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- CV END-->

                                 <!-- SHARE START-->
                                 <div class="j-share">
                                    <div class="js-head">Share post:</div>
                                    <ul>
                                        <li>
                                            <a href="#" class="facebook_share"><i class="fa fa-facebook share-icon"></i></a>
                                            <a href="#" class="twitter_share"><i class="fa fa-twitter share-icon"></i></a>
                                            <a href="#" class="linkedin_share"><i class="fa fa-linkedin share-icon"></i></a>
                                            <a href="#" class="whatsapp_share"><i class="fa fa-whatsapp share-icon"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- SHARE END-->
                               
                            </div>
                            <!-- featured jobs end-->
                            <?php if($subscription && $viewed && !$request_worker):?>
                                <div class="j-apply apply_now_1">
                                    <div class="loading_container text-center" style="display: none;">Loading...</div>
                                    <div class="all_alert alert_0 text-center text-danger p-2" style="font-size: 13px;"></div>
                                    <div class="btn-anchor" id="j-apply-btn">
                                        <a href="<?= url('/ajax.php') ?>" class="employer_hire_btn" id="<?= Input::get('wid') ?>">Hire now</a>
                                    </div>
                                    <p>
                                        By click 'Apply now', You agree to our <a href="#" class="text-primary">terms & conditions</a>
                                        and <a href="#" class="text-primary">Privacy policy</a>
                                    </p>
                                </div>
                            <?php endif;?>
                            <?php if(!Auth_employer::is_loggedin()):?>
                                <div class="j-apply apply_now_1">
                                    <div class="all_alert alert_0 text-center text-danger p-2" style="font-size: 13px;"></div>
                                    <div class="btn-anchor" id="j-apply-btn">
                                       <form action="<?= current_url()?>" method="POST">
                                            <button type="submit" name="check_online_employer" class="btn-fill">HIRE NOW</button>
                                        </form>
                                    </div>
                                    <p>
                                        By click 'Apply now', You agree to our <a href="#" class="text-primary">terms & conditions</a>
                                        and <a href="#" class="text-primary">Privacy policy</a>
                                    </p>
                                </div>
                             <?php endif;?>
                        </div>
                        <div class="col-lg-3" id="apply_now_2">
                             <?php include('includes/apply_now.php') ?>
                        </div>
                        <div class="col-lg-12">
                            <div class="adds-news-small">
                                <div class="job-alert-banner"><!-- job-alert jobs start-->
                                    <div class="alert-header">
                                        <h3>Jobs in Nigeria</h3>
                                    </div>
                                    <div class="alert-body">
                                        <p><b>1280</b> jobs found</p>
                                        <a href="#">Create job alert</a>
                                    </div>
                                </div><!-- job-alert jobs start-->

                                <div class="advert-banner-2">
                                    <a href="#"><img src="<?= asset('/images/adverts/4.jpg')?>" alt=""></a>
                                </div>
                                <div class="advert-banner-2">
                                    <a href="#"><img src="<?= asset('/images/adverts/4.jpg')?>" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
       </div>




<!-- Our Footer -->
<?php include('includes/footer.php');  ?>


















<script>
$(document).ready(function(){

// =======================================
// ADD JOB TO WISHLIST
// ======================================
var wishListIcon = $('.work_wishlist_btn');
$(wishListIcon).click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    var worker_id = $(this).attr('id');
    var icon = $(this).children('i');

    if($(this).children().hasClass('fa-heart-o')){
        $(icon).removeClass('fa-heart-o text-primary');
        $(icon).addClass('fa-heart text-danger');
         
        $(this).children('.alert_success').show();
        alert_success();
    }else{
        $(this).children('.alert_success').hide();
        $(icon).removeClass('fa-heart text-danger')
        $(icon).addClass('fa-heart-o text-primary')
    }
 
    save_for_later(url, worker_id);
});

function save_for_later(url, worker_id){
        $.ajax({
            url: url,
            method: 'post',
            data: {
                worker_id: worker_id,
                save_job: 'save_job'
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.error){
                    console.log(data.error);
                }else if(data.data){
                    console.log('job saved');
                }
            }
        });
}

// =====================================
// REMOVE ALERT SUCCESS
// =====================================
function alert_success(){
    setTimeout(function(){
        $(".alert_success").hide();
    }, 3000);
}


// =====================================
// MOBILE SCREEN HIRE A WORKER
// =====================================
// you can find the mobile hire a worker form in include folder with the file name of apply_now.php

$(".mobile_employer_hire_btn").click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    var worker_id = $(this).attr('id');

    $(".all_alert").html('');
    $(".preloader-container").show(); //preloader
    mobile_hire_worker(url, worker_id);
});


function mobile_hire_worker(url, worker_id){
    var first_name = $(".first_name_input_mobile").val();
    var last_name = $(".last_name_input_mobile").val();
    var phone = $(".phone_input_mobile").val();
    var amount = $(".amount_input_mobile").val();
    var city = $(".city_input_mobile").val();
    var state = $(".state_input_mobile").val();
    var message = $(".message_input_mobile").val();
    var address = $(".address_input_mobile").val();

    $.ajax({
        url: url,
        method: 'post',
        data: {
            worker_id: worker_id,
            first_name: first_name,
            last_name: last_name,
            phone: phone,
            amount: amount,
            city: city,
            state: state,
            message: message,
            address: address,
            hire_worker: 'hire_worker'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.not_login){
                location.reload();
            }else if(data.subscribe){
                remove_loading(data.subscribe.subscribe);
            }else if(data.error){
                get_error(data.error);
            }else if(data.data){
                location.reload();
            }else if(data.hired){
                remove_loading(data.hired.hired);
            }else{
                remove_loading('*Something went wrong, try again later');
            }
            remove_preloader(); //remove preloader
        }
    });
}








// =====================================
// HIRE A WORKER
// =====================================

$(".employer_hire_btn").click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    var worker_id = $(this).attr('id');

    $(".all_alert").html('');
    $(".preloader-container").show(); //preloader
    hire_worker(url, worker_id);
});



function hire_worker(url, worker_id){
    var first_name = $(".first_name_input").val();
    var last_name = $(".last_name_input").val();
    var phone = $(".phone_input").val();
    var amount = $(".amount_input").val();
    var city = $(".city_input").val();
    var state = $(".state_input").val();
    var message = $(".message_input").val();
    var address = $(".address_input").val();

    $.ajax({
        url: url,
        method: 'post',
        data: {
            worker_id: worker_id,
            first_name: first_name,
            last_name: last_name,
            phone: phone,
            amount: amount,
            city: city,
            state: state,
            message: message,
            address: address,
            hire_worker: 'hire_worker'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.not_login){
                location.reload();
            }else if(data.subscribe){
                remove_loading(data.subscribe.subscribe);
            }else if(data.error){
                get_error(data.error);
            }else if(data.data){
                location.reload();
            }else if(data.hired){
                remove_loading(data.hired.hired);
            }else{
                remove_loading('*Something went wrong, try again later');
            }
            remove_preloader(); //remove preloader
        }
    });
}



function get_error(error){
    $(".alert_1").html(error.first_name);
    $(".alert_2").html(error.last_name);
    $(".alert_3").html(error.phone);
    $(".alert_4").html(error.amount);
    $(".alert_6").html(error.city);
    $(".alert_7").html(error.state);
    $(".alert_8").html(error.address);
    $(".alert_9").html(error.message);
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











// =========================================
// CHECK IF USER IS LOGGEDIN
// =========================================
$(".employer_hire_camo_btn").click(function(e){
    e.preventDefault();
    url = $(this).attr('href');

     $.ajax({
        url: url,
        method: 'post',
        data: {
            check_online_employer: 'check_online_employer'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                location.assign(data.location);
            }
        }
    });
});






// =========================================
//SOCIAL MEDIA SHARE BUTTON
// =========================================
var facebook = $(".facebook_share");
var twitter = $(".twitter_share");
var linkedin = $(".linkedin_share");
var whatsapp = $(".whatsapp_share");

var post_url = encodeURI($(location).attr('href'));
var post_title =encodeURI( "Hire a worker form nigeriananny company");

$(facebook).attr('href', `https://www.facebook.com/sharer/sharer.php?u=${post_url}`);
$(twitter).attr('href', `https://twitter.com/share?url=${post_url}&text=${post_title}`);
$(linkedin).attr('href', ` https://www.linkedin.com/shareArticle?url=${post_url}&title=${post_title}`);
$(whatsapp).attr('href', `https://api.whatsapp.com/send?text=${post_title} ${post_url}`);




// end
});
</script>