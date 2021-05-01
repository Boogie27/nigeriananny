<?php include('../Connection.php');  ?>
<?php
if(!Auth_employee::is_loggedin())
{
    Session::put('old_url', '/employee/account');
    Session::put('error', '*Signup or Login to access that page!');
    return view('/');
}



// ======================================
// CHECK FOR REQUEST
// ======================================
if(!Input::exists('get') || !Input::get('rid'))
{
    return view('/jobs');
}




// ======================================
// GET EMPLOYEE DETAILS
// ======================================
$employee = $connection->select('employee')->where('e_id', Auth_employee::employee('id'))->where('email', Auth_employee::employee('email'))->where('e_is_deactivate', 0)->first();
if(!$employee)
{
    Session::put('old_url', '/employee/account');
    Session::delete('employee');
    return view('/employee/login');
}



// ======================================
// GET REQUESTED OFFERS
// ======================================
$request = $connection->select('request_workers')->leftJoin('employers', 'request_workers.j_employer_id', '=', 'employers.id')->leftJoin('workers', 'request_workers.r_worker_id', '=', 'workers.worker_id')->where('j_employee_id', Auth_employee::employee('id'))->where('request_id', Input::get('rid'))->first();
if(!$request)
{
    return view('/jobs');
}

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
                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                <?php endif; ?>
                <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                <?php endif; ?>
           </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3"><!-- right nav start-->
                            <div class="account-x">
                                <div class="head-x flex-item"><i class="fa fa-briefcase"></i><h4>Request offers </h4> </div>
                                <div class="account-x-body">
                                   <div class="img-conatiner-x">
                                        <div class="em-img">
                                            <?php $profile_image = $employee->w_image ? $employee->w_image : '/images/employee/demo.png' ?>
                                            <img src="<?= asset($profile_image) ?>" alt="<?= $employee->first_name ?>" class="acc-img" id="profile_image_img">
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
                                       <div class="dob text-center text-success" style="font-size: 12px;"><span>Joined: </span><?= date('d M Y', strtotime($employee->date_joined)) ?></div>
                                        <ul class="anchor-acc">
                                            <li><a href="<?= url('/employee/account') ?>">Account</a></li>
                                            <li><a href="<?= url('/employee/job-offer') ?>">Job offeres</a></li>
                                            <li><a href="<?= url('/employee/accepted')?>">Accepted offers</a></li>
                                            <li><a href="<?= url('/employee/job-history')?>">Offer history</a></li>
                                            <li><a href="<?= url('/employee/change-password')?>">Change password</a></li>
                                            <li><a href="<?= url('/employee/logout')?>">Logout</a></li>
                                        </ul>
                                   </div>
                                </div>
                            </div>
                        </div><!-- right nav end-->
                        <div class="col-lg-9"><!-- content  start-->
                            <div class="mobile-alert">
                                <?php if(Session::has('error-m')): ?>
                                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error-m') ?></div>
                                <?php endif; ?>
                                <?php if(Session::has('success-m')): ?>
                                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success-m') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="account-x">
                                <div class="accepted-x">
                                    <h3 class="rh-head">  
                                        <a href="<?= url('/employee/job-offer') ?>" class="float-left pl-2"><i class="fa fa-angle-left text-primary"></i><i class="fa fa-angle-left text-primary"></i></a> 
                                        Employer job information</h3>
                                    <div class="request-img text-center">
                                        <?php $profile_image = $request->e_image ? $request->e_image : '/images/employer/demo.png' ?>
                                        <img src="<?= asset($profile_image) ?>" alt="<?= $request->first_name ?>" class="rid-img">
                                        <ul class="request-ul">
                                            <li><b><?= ucfirst($request->first_name.' '.$request->last_name) ?></b></li>
                                            <li><?= $request->email ?></li>
                                            <li><?= $request->city ?> | <?= $request->state ?> | <?= $request->city ?></li>
                                            <li class="text-primary"><b>Job title: </b><?= ucfirst($request->job_title) ?></li>
                                        </ul>
                                    </div>
                                    <div class="information-x">
                                        <div class="head-x flex-item"><i class="fa fa-user"></i><h4>Contact info </h4> </div>
                                        <ul>
                                            <li><b>Phone: </b><?= $request->e_phone ?></li>
                                            <li><b>Email: </b><?= $request->email ?></li>
                                            <li><b>Address: </b><?= $request->address ?></li>
                                            <li><b>City: </b><?= $request->city ?></li>
                                            <li><b>State: </b><?= $request->state ?></li>
                                            <li><b>Country: </b><?= $request->country ?></li>
                                        </ul>
                                    </div>

                                    <div class="information-x">
                                        <div class="head-x flex-item"><i class="fa fa-briefcase"></i><h4>Job information </h4> </div>
                                        <ul>
                                            <li><b>Phone: </b><?= $request->j_phone ?></li>
                                            <li><b>Address: </b><?= $request->j_address ?></li>
                                            <li><b>City: </b><?= $request->j_city ?></li>
                                            <li><b>State: </b><?= $request->j_state ?></li>
                                            <li><b>Description: </b><p class="inner"> <?= $request->j_message ?></p></li>
                                            <li><b>Amount: </b> <span class="text-warning"><?= money($request->j_amount) ?></span></li>
                                            <li><b>Date requested: </b><?= date('d M Y', strtotime($request->request_date)) ?></li>
                                        </ul>
                                    </div>
                                    <?php if($request->is_accept): ?>
                                    <div class="information-x">
                                        <div class="head-x flex-item"><i class="fa fa-users"></i><h4>Offer information </h4> </div>
                                        <ul>
                                            <li><b>Amount: </b> <span class="text-warning"><?= money($request->j_amount) ?></span></li>
                                            <li><b>Status: </b><span class="<?= $request->is_accept ? 'text-success' : 'text-warning' ?>"><?= $request->is_accept ? 'Accepted' : 'Pending' ?></span></li>
                                            <li><b>Date accepted: </b><?= date('d M Y', strtotime($request->accepted_date)) ?></li>
                                        </ul>
                                    </div>
                                    <?php endif; ?>

                                     <?php if($request->is_cancle): ?>
                                    <div class="information-x">
                                        <div class="head-x flex-item"><i class="fa fa-users"></i><h4>Offer information </h4> </div>
                                        <ul>
                                            <li><b>Amount: </b> <span class="text-warning"><?= money($request->j_amount) ?></span></li>
                                            <li><b>Status: </b><span class="text-danger">Cancled</span></li>
                                            <li><b>Date accepted: </b><?= date('d M Y', strtotime($request->cancled_date)) ?></li>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                    <br>

                                    <div class="accpt-conatiner">
                                        <?php if(!$request->is_accept):?>
                                            <a href="#" data-toggle="modal"  data-target="#employee_request_accept_btn" class="bg-success">Accept job offer</a>
                                        <?php endif; ?>
                                        <?php if(!$request->is_cancle):?>
                                            <a href="#" data-toggle="modal"  data-target="#employee_request_cancle_btn" class="text-secondary">Cancle offer</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- content end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




    <!-- Our Footer -->
    <?php include('../includes/footer.php');  ?>










<!-- Modal accept reqest -->
<div class="sign_up_modal modal fade" id="employee_request_accept_btn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_accept_request_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST">
                            <div class="heading">
                                <div class="alert-delete-review text-danger text-center"></div>
                                <p class="text-center">Do you wish to accept this offer?</p>
                            </div>
                            <input type="hidden" class="request_accept_input" value="<?= Input::get('rid') ?>">
                            <button type="submit"  name="subscribe" class="subcribe_now_btn" style="display: none;"></button>
                            <button type="submit" class="btn btn-log btn-block btn-primary" id="request_accept_modal_btn" style="color: #fff">Accept offer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>










<!-- Modal cancle request -->
<div class="sign_up_modal modal fade" id="employee_request_cancle_btn" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <p class="text-center">Do you wish to cancle this offer?</p>
                            </div>
                            <input type="hidden" class="request_cancle_input" value="<?= Input::get('rid') ?>">
                            <button type="submit"  name="subscribe" class="subcribe_now_btn" style="display: none;"></button>
                            <button type="submit" class="btn btn-log btn-block bg-danger" id="request_cancle_modal_btn" style="color: #fff">Cancle offer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>











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








// =========================================
// ACCEPT OFFER MODAL
// =========================================
$(".employee_request_accept_btn").click(function(){
    var request_id = $(this).attr('data-id');
    $(".request_accept_input").val(request_id);
});



// ========================================
// ACCPET REQUEST
// ========================================
$("#request_accept_modal_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var request_id = $(".request_accept_input").val();
    $(".preloader-container").show() //show preloader
    $(".close_accept_request_btn").click();
    
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
$("#request_cancle_modal_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var request_id = $(".request_cancle_input").val();
    $(".preloader-container").show() //show preloader
    $(".close_cancle_request_btn").click();
    
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
