<?php include('Connection.php');  ?>

<?php 
if(!Input::exists('get') && !Input::get('wid'))
{
    return view('/');
}


// ===================================================
// HIRE AN EMPLOYEE
// ===================================================
if(Input::post('hire_employee'))
{
    $connection = new DB();
    $result = hire_employee($connection);
    dd($result);
}


if(Input::post('hire_employee_mobile'))
{
    $connection = new DB();
    $result = hire_employee($connection);
    dd($result);
}



function hire_employee($connection)
{
    if(!Auth_employer::is_loggedin())
    {
        Session::put('old_url', '/job-detail.php?wid='.Input::get('wid'));
        Session::flash('error', '*Signup or Login to be able to hire a worker');
        return view('/employer/login');
    }

    $subscription = $connection->select('employer_subscriptions')->where('s_employer_id', Auth_employer::employer('id'))->where('is_expire', 0)->first();
    
    if(!$subscription)
    {
        Session::flash('error', '*Subscribe to be able to hire a worker');
        return back();
    }

    $request_worker = $connection->select('request_workers')->where('j_employer_id', Auth_employer::employer('id'))->where('r_worker_id', Input::get('wid'))->where('is_accept', 0)->first();
    if($request_worker)
    {
        Session::flash('error', '*This employee has been hired by you!');
        return back();
    }


    $employee = $connection->select('workers')->where('worker_id', Input::get('wid'))->first();
    if(!$employee)
    {
        Session::flash('error', '*This employee has no work profile!');
        return back();
    }

    $validate = new DB();
    $validation = $validate->validate([
        'first_name' => 'required|min:3|max:50',
        'last_name' => 'required|min:3|max:50',
        'phone' => 'required|min:11|max:11|number:phone',
        'amount' => 'required|number:amount',
        'city' => 'required|min:1|max:50',
        'state' => 'required|min:1|max:50',
        'message' => 'min:6|max:5000',
        'address' => 'min:6|max:400',
    ]);
    
    $message = Input::get('message') ? Input::get('message') : null;
    $address = Input::get('address') ? Input::get('address') : null;

    $connection = new DB();
    $create = $connection->create('request_workers', [
                'j_employer_id' => Auth_employer::employer('id'),
                'j_employee_id' => $employee->employee_id,
                'r_worker_id' => Input::get('wid'),
                'j_first_name' => Input::get('first_name'),
                'j_last_name' => Input::get('last_name'),
                'j_phone' => Input::get('phone'),
                'j_amount' => Input::get('amount'),
                'j_city' => Input::get('city'),
                'j_state' => Input::get('state'),
                'j_message' => $message,
                'j_address' => $address,
            ]);
    if($create)
    {
        Session::flash('success', "Employee has been requested successfully!");
        return back();
    }
}






// ************ LOCK THE EMPLOYEE IF EMPLOYEMENT REQUEST ACCEPTED *******//
$job_is_accepted = false;
$employer_requests = $connection->select('request_workers')->where('r_worker_id', Input::get('wid'))->get();
if(count($employer_requests))
{
    foreach($employer_requests as $employer_request)
    {
        if($employer_request->is_accept == 0 && $employer_request->is_completed == 0)
        {
            $job_is_accepted = false;
        }else if($employer_request->is_accept && $employer_request->is_completed)
        {
            $job_is_accepted = false;
        }else if($employer_request->is_accept && $employer_request->is_completed == 0)
        {
            $job_is_accepted = true;
        }
    }
}









// =======================================================
// REMOVE EMPLOYER WORKER VIEWS EVERY MONTH
// =======================================================
$dailyViews = $connection->select('worker_daily_view')->where('wdv_employer_id', Auth_employer::employer('id'))->first();
if($dailyViews)
{
    $today = date('Y-m-d', strtotime('+ 1month'));
    if($today >= $dailyViews->expire_date)
    {
        $connection->update('worker_daily_view', [
            'worker_id' => null,
            'count' => 0,
            'is_complete' => 0
        ])->where('wdv_employer_id', Auth_employer::employer('id'))->save();
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
$job = $connection->select('workers')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('worker_id', Input::get('wid'))->where('is_deactivate', 0)->where('is_job_feature', 1)->first(); 
if(!$job)
{
    return view('/jobs');
}





// =======================================================
//      CHECK IF EMPLOYER HAS SUBSCRIBED
// =======================================================
$daily_view = null;
$subscription = $connection->select('employer_subscriptions')->where('s_employer_id', Auth_employer::employer('id'))->where('is_expire', 0)->first();

if($subscription && !$job_is_accepted)
{
    $is_requested = $connection->select('request_workers')->where('j_employer_id', Auth_employer::employer('id'))->where('r_worker_id', Input::get('wid'))->where('is_accept', 0)->first();
    $daily_view = $connection->select('worker_daily_view')->where('s_reference', $subscription->reference)->where('wdv_employer_id', Auth_employer::employer('id'))->first();
  
    if(!$daily_view && $is_requested == null)
    {
        $expire_date = date('Y-m-d', strtotime('+ 1month'));
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
        if($daily_view->count < $subscription->s_access)
        {
            if(!$daily_view->worker_id)
            {
                $old_workerID[$job->worker_id] = ['woker_id' => $job->worker_id];
                $workerID = json_encode($old_workerID);
                $connection->update('worker_daily_view', [
                    'worker_id' => $workerID,
                    'count' => 1
                ])->where('wdv_employer_id', Auth_employer::employer('id'))->save(); 
            }else{
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
        }
        if($currntCount == $subscription->s_access){
            if(!$daily_view->is_complete)
            {
                $connection->update('worker_daily_view', [
                    'is_complete' => 1
                ])->where('wdv_employer_id', Auth_employer::employer('id'))->save(); 
            }
        }
    }
}else{
   
}






// ======================================================
//      CHECK AND GET EMPLOYEE THAT HAS BEEN VIEWED
// ======================================================
$viewed = false;
if($subscription && !$job_is_accepted)
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




// ************ CHECK IF WORKERS IS SAVED ***************//
$savedJob = false;
if(Cookie::has('saved_worker'))
{
    $saved_worker = json_decode(Cookie::get('saved_worker'), true);
    if(array_key_exists(Input::get('wid'), $saved_worker))
    {
        $savedJob = true;
    }
}



// *********** SHOW EMPLOYEE CONTACT IF LOGGED IN *********//
$is_employee = false;
if(Auth_employee::is_loggedin())
{
    if(Auth_employee::employee('id') == $job->employee_id)
    {
        $is_employee = true;
    }
}

?>


<?php include('includes/header.php');  ?>

<!-- navigation-->
<?php include('includes/navigation.php');  ?>

<?php include('includes/side-navigation.php');  ?>
	





    
	   <!-- jobs  start-->
       <div class="page-content">
           <div class="inner-job-detail" >
                <div class="j-header" id="job_h_v">
                    <b>Job seeker</b> 
                    <?php if($daily_view && $daily_view->is_complete && !$viewed && !$is_requested): ?>
                        <div class="alert-daily_v text-danger"><i class="fa fa-bell text-danger"></i> You have exceeded your maximum view chances</div>
                    <?php endif; ?>

                    <?php if($job_is_accepted && !Auth_employee::is_loggedin()): ?>
                        <div class="alert-daily_v text-danger"><i class="fa fa-bell text-danger"></i> This employee has been hired</div>
                    <?php endif; ?>

                    <?php if($job_is_accepted && Auth_employee::is_loggedin()): ?>
                        <div class="alert-daily_v text-warning"><i class="fa fa-bell text-warning"></i> You accepted this offer</div>
                    <?php endif; ?>

                    <?php if(!$job->e_approved && Auth_employee::is_loggedin()): ?>
                        <div class="alert-daily_v text-warning"><i class="fa fa-bell text-warning"></i> Complete account details to be approved! <a href="<?= url('/employee/account') ?>" class="text-primary">click here</a></div>
                    <?php endif; ?>

                    <?php if($is_requested): ?>
                        <div class="alert-daily_v text-warning"><i class="fa fa-bell text-warning"></i> You have requested for this worker!</div>
                    <?php endif; ?>
                </div>
                <div class="j-body">
                    <div class="row"> 
                        <?php if(!$job_is_accepted):?>
                        <div class="col-lg-4" id="apply_now_1">
                            <form action="<?= current_url() ?>" method="POST" class="p-apply-container">
                                <div class="apply-h"><h4>HIRE WORKER HERE</h4></div>
                                <div class="apply-container">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['first_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="first_name" class="form-control h50" placeholder="Frist name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['last_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="last_name" class="form-control h50" placeholder="Last name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['phone'])) : ?>
                                                    <div class="text-danger"><?= $errors['phone']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="phone" class="form-control h50" placeholder="Phone number" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['amount'])) : ?>
                                                    <div class="text-danger"><?= $errors['amount']; ?></div>
                                                <?php endif; ?>
                                                <input type="number" name="amount" class="form-control h50" placeholder="Amount" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['city'])) : ?>
                                                    <div class="text-danger"><?= $errors['city']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="city" class="form-control h50" placeholder="City" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['state'])) : ?>
                                                    <div class="text-danger"><?= $errors['state']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="state" class="form-control h50" placeholder="State" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['address'])) : ?>
                                                    <div class="text-danger"><?= $errors['address']; ?></div>
                                                <?php endif; ?>
                                               <textarea  name="address" class="form-control h50" cols="30" rows="3" placeholder="Job address"></textarea>
                                            </div>
                                        </div>
                                    
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['message'])) : ?>
                                                    <div class="text-danger"><?= $errors['message']; ?></div>
                                                <?php endif; ?>
                                                <textarea name="message" class="form-control h50" cols="30" rows="5" placeholder="Message..."></textarea>
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
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <button type="submit" name="hire_employee" class="btn-fill">HIRE NOW</button>
                                                <p class="text-center pt-2" style="font-size: 12px;">
                                                    By click <b>Here now</b>, You agree to our <a href="<?= url('/terms') ?>" class="text-primary">terms & conditions</a>
                                                    and <a href="<?= url('/privacy') ?>" class="text-primary">Privacy policy</a>
                                                </p>
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

                                    <!-- <div class="advert-banner-2">
                                        <a href="#"><img src="images/adverts/4.jpg" alt=""></a>
                                    </div>
                                    <div class="advert-banner-2">
                                        <a href="#"><img src="/images/adverts/4.jpg" alt=""></a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-lg-<?= $job_is_accepted ? '12' : '8'?>">
                            <!-- featured jobs start-->
                            <?php if(Session::has('error')): ?>
                                <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                            <?php endif; ?>
                            <?php if(Session::has('success')): ?>
                                <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                            <?php endif; ?>
                           <?php 
                                $w_image = $job->w_image ? $job->w_image : '/employee/images/demo.png'; 
                                $amount = !$job->amount_to ? money($job->amount_form) : money($job->amount_form).' - '.money($job->amount_to);?>
                            <div class="job-body">
                                <div class="jobs-info">
                                    <img src="<?= asset($w_image)?>" alt="<?= $job->first_name ?>">
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
                                            <?php 
                                            if($job->job_type):
                                                if($job->job_type != 'live in'):
                                                $living = json_decode($job->job_type, true); ?>
                                                    <b>Job Location: </b><?= ucfirst($living['city'])?> | <?= ucfirst($living['state'])?> 
                                                <?php else: ?>
                                                    <?= $job->job_type ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                            <span class="money-amount">No job type</span>
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
                                $education = json_decode($job->education, true); ?>
                                <div class="j-expirience">
                                    <div class="js-head">Education:</div>
                                    <ul class="inner-ex" id="inner-ex">
                                        <li><b>Qualification:</b> <span> <?= ucfirst($education['qualification']) ?></span></li>
                                        <li><b>Institution: </b> <span> <?= ucfirst($education['institution']) ?></span></li>
                                        <li><b>City: </b> <span><?= ucfirst($education['city']) ?></span></li>
                                        <li><b>State: </b> <span><?= ucfirst($education['state']) ?></span></li>
                                        <li><b>Country: </b> <span><?= ucfirst($education['country']) ?></span></li>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6"><b>Start date: </b> <span><?= $education['start_date'] ?></span></div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <?php if(!$education['inview']): ?></span>
                                                        <b>End date: </b> <span><?= $education['end_date'] ?></span>
                                                    <?php else: ?>
                                                    <b>End date: </b> <span><span class="inview-x">inview</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <?php endif; ?>
                                
                                <!-- ABILITY START-->
                                <div class="j-bio">
                                    <div class="js-head">Bio:</div>
                                     <p><?= $job->bio ?></p>
                                     <ul class="ability-x" id="inner-ex">
                                         <li><b>Reading: </b> <span><?= $job->reading ? 'Yes' : 'No'?></span></li>
                                         <li><b>Writing: </b> <span><?= $job->writing ? 'Yes' : 'No'?></span></li>
                                         <li></li>
                                     </ul>
                                </div>
                                <!-- ABILITY END -->


                                <!-- WORK EXPERIENCE START -->
                                <?php if($job->work_experience): 
                                $experience = json_decode($job->work_experience, true); ?>
                                <div class="j-summary">
                                    <div class="j-summary-h">Work expirience:</div>
                                    <div class="experience-x" >
                                        <ul id="inner-ex">
                                            <li><b>Job title:</b> <span> <?= ucfirst($experience['job_title']) ?></span></li>
                                            <li><b>Job function:</b> <span> <?= $experience['job_function'] ?></span></li>
                                            <li><b>Employer:</b> <span> <?= $experience['employer_name'] ?></span></li>
                                            <li><b>Employer phone:</b> <span> <?= $experience['employer_phone'] ?></span></li>
                                            <li><b>Employer email:</b> <span> <?= $experience['employer_email'] ?></span></li>
                                            <li><b>Description: </b> <span></li>
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
                                </div>
                                <?php endif; ?>
                                <!-- WORK EXPERIENCE END -->

                                <!-- CONTACT START-->
                                <div class="j-contact">
                                    <div class="js-head">Contact info:</div>
                                        <?php if($is_employee || $subscription && $viewed):?>
                                        <ul id="inner-ex">
                                            <li><i class="fa fa-phone text-success"></i> <b>Phone:</b> <span> <?= $job->phone ?><span></li>
                                            <li><i class="fa fa-envelope text-success"></i> <b>Email:</b> <span> <?= $job->email ?><span></li>
                                            <li><i class="fa fa-circle text-success"></i> <b>City:</b> <span> <?= $job->city ?><span></li>
                                            <li><i class="fa fa-users text-success"></i> <b>state:</b> <span> <?= $job->state ?><span></li>
                                            <li><i class="fa fa-flag text-success"></i> <b>Country:</b> <span> <?= $job->country ?><span></li>
                                        </ul>
                                        <?php endif;?>
                                     <div class="unsub">
                                        <?php if(!$is_employee && $daily_view && $daily_view->is_complete && !$viewed):?>
                                            <p class="text-center alert-danger p-2"><i class="fa fa-bell"></i> You have exceeded your maximum view chances for the month!</p>
                                        <?php endif; ?>
                                        <?php if(!$is_employee && !$subscription):?>
                                            <p class="text-danger">
                                                Employer signup, Login or Subscribe to view contact information
                                                <span class="float-right"><a href="<?= url('/subscription') ?>" class="text-primary">Subscribe</a></span>
                                            </p>
                                         <?php endif; ?>
                                    </div>
                                </div>
                                <!-- CONTACT END-->


                                 <!-- CV START-->
                                 <?php if($is_employee || $subscription && $viewed):
                                    if($job->cv): 
                                    $cv = json_decode($job->cv, true);  
                                    ?>
                                    <div class="j-safety">
                                        <div class="js-head">CV, Resume:</div>
                                        <ul>
                                            <li class="text-center"><a href="<?= url($cv['cv']) ?>" class="btn btn-success" download>Download CV</a></li>
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
                        </div>
                        <?php if(!$job_is_accepted):?>
                        <div class="col-lg-3" id="apply_now_2">
                            <form action="<?= current_url() ?>" method="POST" class="p-apply-container">
                                <div class="apply-h"><h4>HIRE WORKER HERE</h4></div>
                                <div class="apply-container">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['first_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="first_name" class="form-control h50" placeholder="Frist name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['last_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="last_name" class="form-control h50" placeholder="Last name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['phone'])) : ?>
                                                    <div class="text-danger"><?= $errors['phone']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="phone" class="form-control h50" placeholder="Phone number" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['amount'])) : ?>
                                                    <div class="text-danger"><?= $errors['amount']; ?></div>
                                                <?php endif; ?>
                                                <input type="number" name="amount" class="form-control h50" placeholder="Amount" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['city'])) : ?>
                                                    <div class="text-danger"><?= $errors['city']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="city" class="form-control h50" placeholder="City" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['state'])) : ?>
                                                    <div class="text-danger"><?= $errors['state']; ?></div>
                                                <?php endif; ?>
                                                <input type="text" name="state" class="form-control h50" placeholder="State" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['address'])) : ?>
                                                    <div class="text-danger"><?= $errors['address']; ?></div>
                                                <?php endif; ?>
                                               <textarea  name="address" class="form-control h50" cols="30" rows="3" placeholder="Job address"></textarea>
                                            </div>
                                        </div>
                                    
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['message'])) : ?>
                                                    <div class="text-danger"><?= $errors['message']; ?></div>
                                                <?php endif; ?>
                                                <textarea name="message" class="form-control h50" cols="30" rows="5" placeholder="Message..."></textarea>
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
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <button type="submit" name="hire_employee_mobile" class="btn-fill">HIRE NOW</button>
                                                <p class="text-center pt-2" style="font-size: 12px;">
                                                    By click <b>Here now</b>, You agree to our <a href="<?= url('/terms') ?>" class="text-primary">terms & conditions</a>
                                                    and <a href="<?= url('/privacy') ?>" class="text-primary">Privacy policy</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
                        <div class="col-lg-12">
                            <div class="adds-news-small">
                                <div class="job-alert-banner"><!-- job-alert jobs start-->
                                    <!-- <div class="alert-header">
                                        <h3>Jobs in Nigeria</h3>
                                    </div>
                                    <div class="alert-body">
                                        <p><b>1280</b> jobs found</p>
                                        <a href="#">Create job alert</a>
                                    </div> -->
                                </div><!-- job-alert jobs start-->

                                  <!-- <div class="advert-banner-2">
                                        <a href="#"><img src="images/adverts/4.jpg" alt=""></a>
                                    </div>
                                    <div class="advert-banner-2">
                                        <a href="#"><img src="/images/adverts/4.jpg" alt=""></a>
                                    </div> -->
                            </div>
                        </div>
                    </div>
                </div>
           </div>
       </div>





<a href="<?= url('/ajax.php') ?>" id="ajax_url_page" style="display: none;"></a>


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
                get_saved_workers();
            }
        });
}



function get_saved_workers(){
    var url = $("#ajax_url_page").attr('href')
    $.ajax({
        url: url,
        method: 'post',
        data: {
            get_save_job: 'get_save_job'
        },
        success: function(response){
            var data = JSON.parse(response);
            var count = data.data ? `(${data.data})` : '';
            // $(".nav-right .saved-workers").html(count)
            $(".side-navigation .side-saved-workers").html(count)
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
//SOCIAL MEDIA SHARE BUTTON
// =========================================
var facebook = $(".facebook_share");
var twitter = $(".twitter_share");
var linkedin = $(".linkedin_share");
var whatsapp = $(".whatsapp_share");

var post_url = encodeURI($(location).attr('href'));
var post_title = encodeURI( "Hire a worker form nigeriananny company");

$(facebook).attr('href', `https://www.facebook.com/sharer/sharer.php?u=${post_url}`);
$(twitter).attr('href', `https://twitter.com/share?url=${post_url}&text=${post_title}`);
$(linkedin).attr('href', ` https://www.linkedin.com/shareArticle?url=${post_url}&title=${post_title}`);
$(whatsapp).attr('href', `https://api.whatsapp.com/send?text=${post_title} ${post_url}`);




// end
});
</script>