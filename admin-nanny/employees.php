<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/employees');
  return view('/admin/login');
}



// ************ GET ALL EMPLOYEES *************//
$employees = $connection->select('employee')->where('e_approved', 1)->where('e_is_deactivate', 0);


if($search = Input::get('search'))
{
    if(preg_match('/@/', $search))
    {
        $employees->where('email', $search);
    }else{
        $employees->where('first_name', 'RLIKE', $search);
    }
}
$employees->paginate(50);




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
                    <?php endif; ?>
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage employees</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/add-employees') ?>" class="view-btn-fill">Add employees</a></li>
                            </ol>
                        </nav>
                        <div class="text">
                            Total Employees: <?= count($employees->result())?>
                            <a href="#" class="text-primary" id="open_mass_member_newsletter_modal_btn">| Send newsletter |</a>
                            <a href="#" class="text-primary" id="open_mass_member_deactivate_modal_btn"> Deactivate |</a>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="top-table-container">
                            <div class="icon-container"><i class="fa fa-users"></i></div>
                            <form action="" method="GET" class="form-search-input">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" value="" placeholder="Search...">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive" id="members_parent_table_container"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col"><input type="checkbox" data-type="employee" id="mass_member_check_box_input"></th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Employee name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Feature</th>
                                        <th scope="col">Last login</th>
                                        <th scope="col">Date registered</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($employees->result()): 
                                foreach($employees->result() as $employee):    
                                ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" data-type="employee" id="<?= $employee->e_id ?>" class="check-box-members-input-btn">
                                        </td>
                                        <td>
                                            <a href="<?= url('/admin-nanny/employee-detail?wid='.$employee->e_id) ?>">
                                                <?php if($employee->w_image): ?>
                                                    <img src="<?= asset($employee->w_image) ?>" alt="" class="table-img <?= $employee->is_active ? 'online' : 'offline' ?>">
                                                <?php else: ?>
                                                    <img src="<?= asset('/employee/images/demo.png') ?>" alt="" class="table-img <?= $employee->is_active ? 'online' : 'offline' ?>">
                                                <?php endif; ?>
                                            </a>
                                        </td>
                                        <td><?= ucfirst($employee->last_name).' '.ucfirst($employee->first_name)?></td>
                                        <td><?= $employee->email ?></td>
                                      
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  data-id="<?= $employee->e_id ?>" class="custom-control-input employee_feature_btn" id="customSwitch_<?= $employee->e_id ?>" <?= $employee->is_feature ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $employee->e_id ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($employee->last_login)) ?></td>
                                        <td><?= date('d M Y', strtotime($employee->date_joined)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin-nanny/employee-detail?wid='.$employee->e_id) ?>" title="Edit employee"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal"  data-target="#exampleModal_employee_delete" id="<?= $employee->e_id ?>" class="delete_employee_btn" title="Delete employee"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $employees->links(); ?>

                                <?php if(!count($employees->result())): ?>
                                    <div class="empty-table">There are no employees yet!</div>
                                <?php endif; ?>
                            </div>
                        </div><!-- table end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-copy-right">
    <p><?= $banner->alrights ?></p>
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
            $(".page_alert_danger").html('*Network error, try again later!');
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