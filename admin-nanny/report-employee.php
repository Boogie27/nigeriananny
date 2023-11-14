<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/report-employee');
  return view('/admin/login');
}


// ===========================================
// GET REPORTED EMPLOYEES
// ===========================================
$employees = $connection->select('employer_reports')->leftJoin('employee', 'employer_reports.employee_rid', '=', 'employee.e_id');

if($search = Input::get('search'))
{
    if(preg_match('/@/', $search))
    {
        $employees->where('employee.email', $search);
    }else{
        $employees->where('employee.first_name', 'RLIKE', $search);
    }
}
$employees->paginate(50);





// ==============================================
// app banner settings
// ===========================================
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
                        <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage reports</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Reports</a></li>
                            </ol>
                        </nav>
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
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Image</th>
                                    <th scope="col">Employee name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Answered</th>
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
                                           <?php if($employee->w_image): ?>
                                            <img src="<?= asset($employee->w_image) ?>" alt="" class="table-img <?= $employee->is_active ? 'online' : 'offline' ?>">
                                            <?php else: ?>
                                            <img src="<?= asset('/employee/images/demo.png') ?>" alt="" class="table-img <?= $employee->is_active ? 'online' : 'offline' ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= ucfirst($employee->last_name).' '.ucfirst($employee->first_name)?></td>
                                        <td><?= $employee->email ?></td>
                                      
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  data-id="<?= $employee->rid ?>" class="custom-control-input employee_answered_btn" id="customSwitch_<?= $employee->rid ?>" <?= $employee->is_answered ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $employee->rid ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($employee->last_login)) ?></td>
                                        <td><?= date('d M Y', strtotime($employee->date_joined)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin-nanny/report-detail?eid='.$employee->e_id) ?>" title="Edit employee"><i class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $employees->links(); ?>

                                <?php if(!$employees->result()): ?>
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









<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){

// ==========================================
// EMPLOYEE ANSWERED BUTTON
// ==========================================
$('.employee_answered_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			answered_id: id,
			is_answered_employer: 'is_answered_employer'
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