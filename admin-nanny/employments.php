<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/employments');
  return view('/admin/login');
}



// **************GET ALL EMPLOYEES REQUESTED****************//
$employments = $connection->select('request_workers')->leftJoin('workers', 'request_workers.r_worker_id', '=', 'workers.worker_id')->leftJoin('employee', 'request_workers.j_employee_id', '=', 'employee.e_id')->paginate(15);



// ***************app banner settings***************//
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
                    <?php endif; ?>
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage employments</h4>
                            <ol class="breadcrumb float-right">
								<li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Employments</li>
							</ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Image</th>
                                    <th scope="col">Employee name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Accepted</th>
                                    <th scope="col">Last login</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($employments->result()): 
                                foreach($employments->result() as $employment):    
                                ?>
                                    <tr>
                                        <td>
                                           <?php if($employment->w_image): ?>
                                            <img src="<?= asset($employment->w_image) ?>" alt="" class="table-img <?= $employment->is_active ? 'online' : 'offline' ?>">
                                            <?php else: ?>
                                            <img src="<?= asset('/employee/images/demo.png') ?>" alt="" class="table-img <?= $employment->is_active ? 'online' : 'offline' ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= ucfirst($employment->last_name).' '.ucfirst($employment->first_name)?></td>
                                        <td><?= $employment->email ?></td>
                                      
                                        <td><span class="<?= $employment->is_accept ? 'delivered' : 'pending'?>"><?= $employment->is_accept ? 'Accepted' : 'Pending'?></span></td>
                                        <td><?= date('d M Y', strtotime($employment->last_login)) ?></td>
                                        <td><?= date('d M Y', strtotime($employment->request_date)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin-nanny/employment-details?rid='.$employment->request_id) ?>" title="Employment details"><i class="fa fa-eye"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal"  data-target="#exampleModal_employee_delete" id="<?= $employment->e_id ?>" class="delete_employee_btn" title="Delete customer"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $employments->links(); ?>

                                <?php if(!$employments->result()): ?>
                                    <div class="empty-table">There are no employees yet!</div>
                                <?php endif; ?>
                            </div>
                        </div><!-- table end-->
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





<!-- Modal -->
<div class="sign_up_modal modal fade" id="exampleModal_employee_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_delete_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete this employee?</p>
                                <input type="hidden" id="employee_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_employee_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
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

// ===========================================
// FEATURE EMPLOYEE
// ===========================================
$(".employee_feature_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader
    $(".page_alert_danger").hide();

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            update_employee_feature: 'update_employee_feature'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('^Network error, try again later!');
        }
    });
    
});



// ==========================================
// OPEN DELETE EMPLOYEE MODAL
// ==========================================
$(".delete_employee_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    $("#employee_delete_id").val(id);
    $('.page_alert_danger').hide();
});





// ========================================
// DELETE EMPLOYEE
// ========================================
$("#submit_employee_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#employee_delete_id").val();
     var url = $(this).attr('data-url');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			employee_id: id,
			delete_employee_action: 'delete_employee_action'
		},
		success: function(response){
            var info = JSON.parse(response);
            if(info.data){
                location.reload();
            }else{
                remove_preloader();
                $('.page_alert_danger').show();
                $('.page_alert_danger').html('*Network error, try again later');
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