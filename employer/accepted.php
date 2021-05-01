<?php include('../Connection.php');  ?>
<?php
if(!Auth_employer::is_loggedin())
{
    Session::put('old_url', '/employer/account');
    Session::put('error', '*Signup or Login to access that page!');
    return view('/');
}





// ======================================
// GET EMPLOYER DETAILS
// ======================================
$employer = $connection->select('employers')->where('id', Auth_employer::employer('id'))->where('email', Auth_employer::employer('email'))->where('e_deactivate', 0)->first();
if(!$employer)
{
    Session::put('old_url', '/employer/account');
    Session::delete('employer');
    return view('/employer/login');
}



// ======================================
// GET REQUESTED OFFERS
// ======================================
$requests = $connection->select('request_workers')->leftJoin('workers', 'request_workers.r_worker_id', '=', 'workers.worker_id')->leftJoin('employee', 'request_workers.j_employee_id', '=', 'employee.e_id')->where('is_accept', 1)->where('is_cancle', 0)->where('j_employer_id', Auth_employer::employer('id'))->get();



?>


<?php include('../includes/header.php');  ?>


<!--  navigation-->
<?php include('../includes/navigation.php');  ?>

<?php include('../includes/side-navigation.php');  ?>

    

<!-- jobs  start-->
<div class="page-content">
    <div class="items-container">
        <div class="account-container">
            <div class="desktop-alert">
                <?php if(Session::has('success')): ?>
                    <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                <?php endif; ?>
                <?php if(Session::has('error')): ?>
                    <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                <?php endif; ?>
           </div>
            <div class="row">
                <div class="col-lg-3"><!-- right nav start-->
                    <div class="account-x accepted-x-body">
                        <div class="head-x flex-item"><i class="fa fa-briefcase"></i><h4>Request offers </h4> </div>
                        <div class="account-x-body">
                            <div class="img-conatiner-x">
                                <div class="em-img">
                                    <?php $profile_image = $employer->e_image ? $employer->e_image : '/images/employer/demo.png' ?>
                                    <img src="<?= asset($profile_image) ?>" alt="<?= $employer->first_name ?>" class="acc-img" id="profile_image_img">
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
                                <div class="dob text-center text-success" style="font-size: 12px;"><span>Joined: </span><?= date('d M Y', strtotime($employer->e_date_joined)) ?></div>
                                <ul class="anchor-acc">
                                    <li><a href="<?= url('/employer/account') ?>">Account</a></li>
                                    <li><a href="<?= url('/employer/job-offer') ?>">Job offeres</a></li>
                                    <li><a href="<?= url('/employer/accepted')?>">Accepted offers</a></li>
                                    <li><a href="<?= url('/employer/change-password')?>">Change password</a></li>
                                    <li><a href="<?= url('/employer/logout')?>">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!-- right nav end-->
                <div class="col-lg-9"><!-- content start-->
                    <div class="mobile-alert">
                        <?php if(Session::has('error-m')): ?>
                            <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error-m') ?></div>
                        <?php endif; ?>
                        <?php if(Session::has('success-m')): ?>
                            <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success-m') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="account-x accepted-x-body">
                    <div class="accepted-x">
                    <?php if(count($requests)): ?>
                        <h3 class="rh-head">Employer job Offers</h3><br><br>
                    <?php endif; ?>
                        <?php if(count($requests)): 
                         foreach($requests as $request):
                            $profile_image = $request->w_image ? $request->w_image : '/images/employee/demo.png';
                            $amount = !$request->amount_to ? money($request->amount_form) : money($request->amount_form).' - '.money($request->amount_to);
                        ?>
                            <div class="jobs-info accept-x-inner">
                                <img src="<?= asset($profile_image) ?>" alt="">
                                <ul class="ul">
                                    <li class="text-right lupe-x">
                                        <div class="drop-down">
                                            <i class="fa fa-ellipsis-h dot-icon"></i>
                                            <ul class="drop-down-ul">
                                                <li><a href="<?= url('/employer/employee-detail.php?wid='.$request->request_id ) ?>">Detail</a></li>
                                                <li><a href="#" class="request_accept_btn" id="<?= $request->request_id?>">Accept offer</a></li>
                                                <li><a href="#" class="request_cancle_btn" id="<?= $request->request_id?>">Cancle offer</a></li>
                                                <li><a href="#" class="request_delete_btn" id="<?= $request->request_id?>">Delete offer</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <h4><a href="<?= url('/job-detail.php?wid='.$request->r_worker_id) ?>"> <?= ucfirst($request->job_title)?></a> </h4>
                                    </li>
                                    <li>Name: <?= ucfirst($request->first_name).' '.ucfirst($request->last_name) ?></li>
                                    <li>Email: <?= $request->email ?></li>
                                    <li>
                                        <?php if($request->job_type != 'live in'):
                                        $living = json_decode($request->job_type, true); ?>
                                            <b>Job Location: </b><?= ucfirst($living['city'])?> | <?= ucfirst($living['state'])?> 
                                        <?php else: ?>
                                            <?= $request->job_type ?>
                                        <?php endif; ?>
                                        | <span class="text-warning money-amount"><?= $amount ?></span>
                                    </li>
                                    <li>
                                        <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> <?= date('d M Y', strtotime($request->request_date)) ?></span>
                                   </li>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="empty-worker-z">
                            <div class="empty-inner">
                                <img src="<?= asset('/images/icons/1.svg')?>" alt="">
                                <h3>No job offers yet!</h3>
                                <h5>You have no pending job offers!</h5>
                            </div>
                        </div>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




<!-- Our Footer -->
<?php include('../includes/footer.php');  ?>















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
//  ADD PROFILE IMAGE
// ============================================
$('.img-conatiner-x').on('change', '.profile_img_input', function(){
    var url = $(".ajax_url_page").attr('href');
    var image = $(".profile_img_input");
    $(".e-loader-kamo").show();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('upload_employee_image', true);

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
        }
    });
});







// ========================================
//     GET EMPLOYER IMAGE
// ========================================
function get_employer_img(){
    var url = $(".ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
            get_employee_img: 'get_employee_img'
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
    $(".e-loader-kamo").show();
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





// ========================================
// ACCPET OFFER
// ========================================
$(".request_accept_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var request_id = $(this).attr('id');
    $(".preloader-container").show() //show preloader
    
    $.ajax({
        url: url,
        method: "post",
        data: {
            request_id: request_id,
            employee_accept_offer: 'employee_accept_offer'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                location.reload();
            }else if(data.data){
                location.reload();
            }
        }
    });
});







// ========================================
// CANCLE JOB OFFER
// ========================================
$(".request_cancle_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var request_id = $(this).attr('id');
    $(".preloader-container").show() //show preloader
    
    $.ajax({
        url: url,
        method: "post",
        data: {
            request_id: request_id,
            employee_cancle_action: 'employee_cancle_action'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                location.reload();
            }else if(data.data){
                location.reload();
            }
        }
    });
});






// ========================================
// DELETE JOB OFFER
// ========================================
$(".request_delete_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var request_id = $(this).attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            request_id: request_id,
            employee_delete_request: 'employee_delete_request'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                location.reload();
            }else if(data.data){
                location.reload();
            }
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
