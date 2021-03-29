<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/report-employee');
  return view('/admin/login');
}



// ==========================================
// CHECK IF GET PARAMETER EXISTS
// ==========================================
if(!Input::exists('get') || !Input::get('eid'))
{
    return view('/admin-nanny/report-employee');
}




// ===========================================
// GET REPORTED EMPLOYE
// ===========================================
$reports = $connection->select('employer_reports')->leftJoin('employers', 'employer_reports.employer_rid', '=', 'employers.id')->where('employee_rid', Input::get('eid'))->get();


$employee = $connection->select('employee')->where('e_id', Input::get('eid'))->first();

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
                    <?php if(Session::has('error')): ?>
                        <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                    <?php endif; ?>
                    <?php if(Session::has('success')): ?>
                        <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage details</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/report-employee') ?>">Reports</a></li>
                            </ol>
                        </nav>
                    </div>
                    <?php if($employee->is_flagged): ?>
                        <div class="col-lg-12">
                            <div class="alert-warning text-center p-3"><i class="fa fa-bell"></i> This employee has been flagged</div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-12">
                         <!-- review start -->
                            <br><br>
                            <div class="account-z">
                                <div class="head-x flex-item"><i class="fa fa-circle"></i> <h4> Employer report</h4></div>
                                <!-- review start -->
                                <p class="text-center">Employers report about <b><?= ucfirst($employee->first_name.' '.$employee->last_name) ?></b></p>
                                <?php if($reports): ?>
                                <div class="employee-review" id="employee_review_container">
                                       <?php foreach($reports as $report): ?>
                                        <div class="emp-rev flex-item">
                                            <?php $review_image = $report->e_image ? $report->e_image : '/employer/images/employer/demo.png';  ?>
                                            <img src="<?= asset($review_image) ?>" alt="<?= $report->first_name ?>" class="review-img">
                                            <ul class="a-info pt-2">
                                                <li><b>Name: </b><?= ucfirst($report->first_name.' '.$report->last_name)?> <span class="float-right text-success"><?= date('d M Y', strtotime($report->date_reported))?></span></li>
                                                <li><b>Email: </b><?= $report->email ?></li>
                                                <li><b>Report: </b><?= $report->report ?></li>
                                                <li><b>Comment: </b><?= $report->comment ?></li>
                                            </ul>
                                        </div>
                                        <?php endforeach; ?>
                                </div>
                                <div class="form-group text-right">
                                    <a href="#"  data-toggle="modal"  data-target="#exampleModal_clear_restore" class="btn btn-primary">Clear and restore</a>   
                                    <?php if(!$employee->is_flagged): ?>                                 
                                        <a href="#"  data-toggle="modal"  data-target="#exampleModal_deactivate_delete" class="btn btn-danger">Flag employee</a>
                                    <?php else: ?>
                                        <a href="#"  data-toggle="modal"  data-target="#exampleModal_unflag_employee" class="btn btn-info">Unflag employee</a>
                                    <?php endif; ?>
                                    </div>
                                <?php else:?>
                                    <div class="empty-page-flag">
                                        <div class="inner-flag">
                                            <h4>FLag empty</h4>
                                            <p>This employee has not been flagged yet!</p>
                                            <a href="<?= url('/admin-nanny/report-employee') ?>" class="text-primary"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> back</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- review start -->
                            </div>
                        </div>
                        <!-- review end -->
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
<div class="sign_up_modal modal fade" id="exampleModal_deactivate_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_deactivate_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to deactivate this employee?</p>
                                <input type="hidden" id="employee_deactivate_id_input" value="<?= Input::get('eid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_employee_deactivate_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Flag employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<!-- Modal clear and restore-->
<div class="sign_up_modal modal fade" id="exampleModal_clear_restore" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_restore_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to restore this employee?</p>
                                <input type="hidden" id="employee_restore_id_input" value="<?= Input::get('eid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_employee_restoree_btn" class="btn bg-primary btn-log btn-block" style="color: #fff;">Clear & restore</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<!-- Modal clear and restore-->
<div class="sign_up_modal modal fade" id="exampleModal_unflag_employee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_unflag_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to unflag this employee?</p>
                                <input type="hidden" id="employee_restore_id_input" value="<?= Input::get('eid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_unflag_employee_btn" class="btn bg-info btn-log btn-block" style="color: #fff;">Unflag employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>













<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){

// ==========================================
// EMPLOYEE FLAG
// ==========================================
$('#submit_employee_deactivate_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $('#employee_deactivate_id_input').val();
    $(".preloader-container").show() //show preloader
    $("#modal_deactivate_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			employee_id: id,
			admin_employee_flag: 'admin_employee_flag'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload()
            }else{
                location.reload()
            }
		}
	});
});








// ==========================================
// EMPLOYEE CLEAR AND RESTORE
// ==========================================
$('#submit_employee_restoree_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $('#employee_restore_id_input').val();
    $(".preloader-container").show() //show preloader
    $("#modal_restore_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			employee_id: id,
			admin_restore_deactivate: 'admin_restore_deactivate'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload()
            }else{
                location.reload()
            }
		}
	});
});









// ==========================================
// ADMIN UNFLAG EMPLOYEE
// ==========================================
$('#submit_unflag_employee_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $('#employee_restore_id_input').val();
    $(".preloader-container").show() //show preloader
    $("#modal_unflag_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			employee_id: id,
			admin_unflag_employee: 'admin_unflag_employee'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload()
            }else{
                location.reload()
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










// end
});
</script>
























