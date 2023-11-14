<?php include('../Connection_Admin.php');  ?>


<?php
if(!Admin_auth::is_loggedin())
{
    Session::delete('admin');
    return view('/admin/login');
}


if(!Input::exists('get') || empty(Input::get('rid')) || !is_numeric(Input::get('rid')))
{
    return view('/admin-nanny/employments');
}

// **************GET EMPLOYEES REQUESTED DETAILS ****************//
$employment = $connection->select('request_workers')->leftJoin('workers', 'request_workers.r_worker_id', '=', 'workers.worker_id')->leftJoin('employee', 'request_workers.j_employee_id', '=', 'employee.e_id')->where('request_id', Input::get('rid'))->first();
if(!$employment)
{
    return view('/admin-nanny/employments');
}


// **************GET EMPLOYERS DETAILS ****************//
$employer = $connection->select('employers')->where('id', $employment->j_employer_id)->first();




// app banner settings
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
                       <?php endif;?>
                       <?php if(Session::has('error')): ?>
                            <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                       <?php endif;?>
                       <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Employment detail</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/employments') ?>"> Employments</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="edit-product-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-detail">
                                        <div class="p-header">
                                            <div class="drop-down float-right">
                                                <i class="fa fa-ellipsis-h dot-icon"></i>
                                                <ul class="drop-down-ul">
                                                    <li><a href="<?= url('/admin-nanny/employee-detail?wid='.$employment->j_employee_id)?>" id="<?= Input::get('rid') ?>">Details</a></li>                                       
                                                    <li><a href="<?= url('/admin-nanny/employee-ratings?eid='.$employment->j_employee_id.'&rid='.Input::get('rid'))?>" id="<?= Input::get('rid') ?>">Ratings</a></li>                                       
                                                    <li><a href="<?= url('/admin-nanny/report-detail?eid='.$employment->j_employee_id) ?>" id="<?= Input::get('rid') ?>">Flags</a></li>                                       
                                                </ul>
                                            </div>
                                            <label for="" class='h'><b>Employee details: </b></label>
                                            <div class="inner-detail-content flex">
                                                <div class="img-item-img">
                                                     <?php  $w_image = $employment->w_image ? $employment->w_image : '/employee/images/demo.png'; ?>
                                                    <img src="<?= asset($w_image)?>" alt="<?=$employment->first_name ?>">
                                                </div>
                                                <ul class="ul-inner-detail">
                                                    <li><b>Full name: </b><?= ucfirst($employer->last_name.' '.$employer->first_name)?></li>
                                                    <li><b>Email: </b><?= $employment->email?></li>
                                                    <li><b>Phone: </b><?= $employment->phone?></li>
                                                    <li><b>Default address: </b><?= $employment->address?></li>
                                                    <li><b>City: </b><?= $employment->city?></li>
                                                    <li><b>State: </b><?= $employment->state?></li>
                                                    <li>
                                                        <b>Country: </b><?= $employment->country?>
                                                        <span class="float-right"><a href="#" class="text-primary">view detail</a></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="product-detail">
                                        <div class="row">
                                            <div class="col-lg-6">
                                            <label for="" class='h'><b>Employment details</b></label>
                                                <ul class="ul-inner-detail">
                                                    <li><b>Phone: </b><?= $employment->j_phone ?></li>
                                                    <li><b>Job address: </b><?= $employment->j_address ?></li>
                                                    <li><b>Job city: </b><?= $employment->j_city ?></li>
                                                    <li><b>Job state: </b><?= $employment->j_state ?></li>
                                                    <li><b>Description: </b><p> <?= $employment->j_message ?></p></li>
                                                    <li><b>Amount: </b> <span class="text-warning"><?= money($employment->j_amount) ?></span></li>
                                                    <li><b>Date requested: </b><span class="text-success"><?= date('d M Y', strtotime($employment->request_date)) ?></span></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="" class='h'><b>Employment status</b></label>
                                                <ul class="ul-inner-detail">
                                                    <li><b>Status: </b><span class="<?= $employment->is_accept ? 'delivered' : 'pending' ?>"><?= $employment->is_accept ? 'Accepted' : 'Pending' ?></span></li>
                                                    <li><b>Accepted date: </b><span class="text-success"><?= $employment->accepted_date ? date('d M Y', strtotime($employment->accepted_date)) : ''?></span></li>
                                                    <li><b>Completed: </b><span class="<?= $employment->is_completed ? 'delivered' : 'pending' ?>"><?= $employment->is_completed ? 'Completed' : 'Pending' ?></span></li>
                                                    <li><b>Completed date: </b><span class="text-success"><?= $employment->completed_date ? date('d M Y', strtotime($employment->completed_date)) : ''?></span></li>
                                                    <li><b>Description: </b><p> <?= $employment->j_message ?></p></li>
                                                    <li><b>Amount: </b> <span class="text-warning"><?= money($employment->j_amount) ?></span></li>
                                                    <li><b>Date requested: </b><span class="text-success"><?= date('d M Y', strtotime($employment->request_date)) ?></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="product-detail">
                                        <div class="p-header">
                                            <label for="" class='h'><b>Employment details:</b></label>
                                            <div class="inner-detail-content flex">
                                                <div class="img-item-img">
                                                     <?php  $e_image = $employer->e_image ? $employer->e_image : '/employer/images/demo.png'; ?>
                                                    <img src="<?= asset($e_image)?>" alt="<?=$employer->first_name ?>">
                                                </div>
                                                <ul class="ul-inner-detail">
                                                    <li><b>Full name: </b><?= ucfirst($employer->last_name.' '.$employer->first_name)?></li>
                                                    <li><b>Email: </b><?= $employer->email?></li>
                                                    <li><b>Phone: </b><?= $employer->e_phone?></li>
                                                    <li><b>Default address: </b><?= $employer->address?></li>
                                                    <li><b>City: </b><?= $employer->city?></li>
                                                    <li><b>State: </b><?= $employer->state?></li>
                                                    <li>
                                                        <b>Country: </b><?= $employer->country?>
                                                        <span class="float-right"><a href="<?= url('/admin-nanny/employer-detail?wid='.$employer->id) ?>" class="text-primary">view detail</a></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <form action="<?= current_url()?>" method="post">
                                        <div class="form-group text-right">
                                        <?php if(!$employment->is_completed): ?>
                                            <a href="#" data-toggle="modal"  data-target="#exampleModal_complete_employement" class="btn btn-primary" id="complete_employment_btn">Complete employement</a>
                                        <?php else: ?>
                                            <a href="#" data-toggle="modal"  data-target="#exampleModal_uncomplete_employement" class="btn btn-info" id="complete_employment_btn">Uncomplete employement</a>
                                        <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                                <!-- something here -->
                            </div>
                        </div>
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











<!-- Modal complete employement-->
<div class="sign_up_modal modal fade" id="exampleModal_complete_employement" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close dynamic_modal_btn_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to complete this emploment?</p>
                                <input type="hidden" id="employee_complete_id" value="<?= Input::get('rid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_complete_employment_btn" class="btn bg-primary btn-log btn-block" style="color: #fff;">Complete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<!-- Modal uncomplete employement-->
<div class="sign_up_modal modal fade" id="exampleModal_uncomplete_employement" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close dynamic_modal_btn_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to uncomplete this emploment?</p>
                                <input type="hidden" id="employee_complete_id" value="<?= Input::get('rid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_uncomplete_employment_btn" class="btn bg-primary btn-log btn-block" style="color: #fff;">Uncomplete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>

<input type="hidden" id="request_employee_id_input" value="<?= $employment->e_id ?>" style="display: none;">







<?php  include('includes/footer.php') ?>










<script>
$(document).ready(function(){

// ********** COMPLETE EMPLOYEE EMPLOYMENT ************//
$("#submit_complete_employment_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $("#request_employee_id_input").val()
    var request_id = $("#employee_complete_id").val()
    $(".preloader-container").show() //show preloader
    $(".page_alert_danger").hide();
    $(".dynamic_modal_btn_close").click()

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            request_id: request_id,
            complete_employee_employment: 'complete_employee_employment'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload()
            }else{
                $(".page_alert_danger").show();
                $(".page_alert_danger").html('*Network error, try again later!');
            }
            remove_preloader();
        },
        error: function(){
            remove_preloader();
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






// ********** UNCOMPLETE EMPLOYEE EMPLOYMENT ************//
$("#submit_uncomplete_employment_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $("#request_employee_id_input").val()
    var request_id = $("#employee_complete_id").val()
    $(".preloader-container").show() //show preloader
    $(".page_alert_danger").hide();
    $(".dynamic_modal_btn_close").click()

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            request_id: request_id,
            uncomplete_employee_employment: 'uncomplete_employee_employment'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload()
            }else{
                $(".page_alert_danger").show();
                $(".page_alert_danger").html('*Network error, try again later!');
            }
            remove_preloader();
        },
        error: function(){
            remove_preloader();
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
    });
    
});




// end
});
</script>