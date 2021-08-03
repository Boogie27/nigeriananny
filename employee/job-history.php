<?php include('../Connection.php');  ?>
<?php
if(!Auth_employee::is_loggedin())
{
    return view('/employee/login');
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
$requests = $connection->select('request_workers')->leftJoin('employers', 'request_workers.j_employer_id', '=', 'employers.id')->where('is_employee_delete', 0)->where('j_employee_id', Auth_employee::employee('id'))->get();



?>



<?php include('../includes/header.php');  ?>


<!-- top navigation-->
<?php include('../includes/navigation.php');  ?>

<?php include('../includes/side-navigation.php');  ?>

    

    

<!-- jobs  start-->
<div class="page-content">
    <div class="items-container">
        <div class="account-container">
            <div class="desktop-alert">
                <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                <?php endif; ?>
                <?php if(Session::has('success')): ?>
                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                <?php endif; ?>
           </div>
            <div class="row">
                <div class="col-lg-3"><!-- right nav start-->
                    <div class="account-x accepted-x-body">
                        <div class="head-x flex-item"><i class="fa fa-briefcase"></i><h4>Request offers </h4> </div>
                        <div class="account-x-body">
                            <div class="img-conatiner-x">
                                <div class="em-img">
                                    <?php $profile_image = $employee->w_image ? $employee->w_image : '/employee/images/demo.png' ?>
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
                                    <li><a href="<?= url('/employee/job-offer') ?>">Job offers</a></li>
                                    <li><a href="<?= url('/employee/accepted')?>">Accepted offers</a></li>
                                    <li><a href="<?= url('/employee/job-history')?>">Offer history</a></li>
                                    <li><a href="<?= url('/employee/change-password')?>">Change password</a></li>
                                    <li><a href="<?= url('/employee/logout')?>">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!-- right nav end-->
                <div class="col-lg-9"><!-- content start-->
                    <div class="mobile-alert">
                        <?php if(Session::has('error-m')): ?>
                            <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error-m') ?></div>
                        <?php endif; ?>
                        <?php if(Session::has('success-m')): ?>
                            <div class="alertalert-success text-center p-3 mb-2"><?= Session::flash('success-m') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="account-x accepted-x-body">
                        <div class="accepted-x">
                        <?php if(count($requests)): ?>
                            <h3 class="rh-head">Employer job Offers</h3><br><br>
                        <?php endif; ?>
                        <?php if(count($requests)): 
                        foreach($requests as $request):
                            $profile_image = $request->e_image ? $request->e_image : '/employee/images/demo.png';
                        ?>
                            <div class="jobs-info accept-x-inner">
                                <img src="<?= asset($profile_image) ?>" alt="">
                                <ul class="ul">
                                    <li>
                                        <h4>
                                            <a href="<?= url('/employee/job-detail.php?rid='.$request->request_id ) ?>">Carles anonye</a> 
                                            <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> <?= date('d M Y', strtotime($request->request_date)) ?></span>
                                        </h4>
                                    </li>
                                    <li>Email: <?= $request->email ?></li>
                                    <li><?= ucfirst($request->j_city) ?> | <?= ucfirst($request->j_state) ?> | <?= ucfirst($request->country) ?> </li>
                                    <li>Amount: <b class="text-warning"> <?= money($request->j_amount) ?></b></li>
                                    <li><b>Status: </b><span class="<?= $request->is_accept ? 'text-success' : 'text-warning' ?>"><?= $request->is_accept ? 'Accepted' : 'Pending' ?></span></li>
                                    <li class="text-right acc-detail">
                                    <?php if($request->is_completed): ?>
                                        <a href="#" data-toggle="modal"  data-target="#employee_delete_offer_btn" class="text-primary delete_job_offer_btn" id="<?= $request->request_id ?>" title="Delete offer"><i class="fa fa-trash" style="font-size: 15px;"></i></a>
                                    <?php endif; ?>
                                        <span class=""><a href="<?= url('/employee/job-detail.php?rid='.$request->request_id ) ?>" class="text-primary v-deatil">View detail</a></span>
                                    </li>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-worker-z">
                                <div class="empty-inner">
                                    <img src="<?= asset('/images/icons/1.svg')?>" alt="">
                                    <h3>Empty job history!</h3>
                                    <h5>You have no job history!</h5>
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








<!-- Modal Delete offer -->
<div class="sign_up_modal modal fade" id="employee_delete_offer_btn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_delete_request_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST">
                            <div class="heading">
                                <div class="alert-delete-review text-danger text-center"></div>
                                <p class="text-center">Do you wish to delete this offer?</p>
                            </div>
                            <input type="hidden" class="employee_delete_offer_input" value="">
                            <button type="submit"  name="subscribe" class="subcribe_now_btn" style="display: none;"></button>
                            <button type="submit" class="btn btn-log btn-block bg-danger" id="delete_offer_modal_btn" style="color: #fff">Delete offer</button>
                        </form>
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
                $(".nav-profile-img").attr('src', data.data)
                $("#profile_image_img").attr('src', data.data)
                img_preloader()
            }
        }
    });
});







// ========================================
//     GET ERROR PRELOADER
// ========================================
function img_preloader(string){
    setTimeout(function(){
        $(".e-loader-kamo").hide();
    }, 1000);
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
// REMOVE PRELOADER
// ========================================
function remove_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
        $(".alert-success").hide();
    }, 2000);
}






// ========================================
// GET DELETE JOB OFFER ID
// ========================================
$(".delete_job_offer_btn").click(function(e){
    e.preventDefault();
    var request_id = $(this).attr('id');
    $(".employee_delete_offer_input").val(request_id)
});



// ========================================
// DELETE JOB OFFER
// ========================================
$("#delete_offer_modal_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var request_id = $(".employee_delete_offer_input").val();
    $(".preloader-container").show() //show preloader
    $(".close_delete_request_btn").click();

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


});
</script>
