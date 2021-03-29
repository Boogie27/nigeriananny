<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/employees');
  return view('/admin/login');
}


// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$employers = $connection->select('employer_subscriptions')->leftJoin('employers', 'employer_subscriptions.s_employer_id', '=', 'employers.id')->paginate(15);



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
                        <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Subscribed employers</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Subscription</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Image</th>
                                    <th scope="col">Employer name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($employers->result()): 
                                foreach($employers->result() as $employer):    
                                ?>
                                    <tr>
                                        <td>
                                           <?php if($employer->e_image): ?>
                                            <img src="<?= asset($employer->e_image) ?>" alt="" class="table-img <?= $employer->e_active ? 'online' : 'offline' ?>">
                                            <?php else: ?>
                                            <img src="<?= asset('/employer/images/employer/demo.png') ?>" alt="" class="table-img <?= $employer->e_active ? 'online' : 'offline' ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= ucfirst($employer->last_name).' '.ucfirst($employer->first_name)?></td>
                                        <td><?= $employer->s_type?></td>
                                        <td><?= $employer->s_duration?></td>
                                        <td><?= money($employer->s_amount) ?></td>
                                        <td><span class="<?= !$employer->is_expire ? 'delivered' : 'deactivate'?>"><?= !$employer->is_expire ? 'active' : 'expired'?></span></td>
                                        <td>
                                            <a href="<?= url('/admin-nanny/sub-detail.php?sid='.$employer->subscription_id ) ?>"  title="View subscription"><i class="fa fa-eye"></i></a>
                                           <span class="expand"></span>
                                           <a href="#" id="<?= $employer->subscription_id ?>"  data-toggle="modal"  data-target="#exampleModal_employer_delete" class="delete_employee_btn" title="Delete subscription"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $employers->links(); ?>

                                <?php if(!$employers->result()): ?>
                                    <div class="empty-table">There are no subscription yet!</div>
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
<div class="sign_up_modal modal fade" id="exampleModal_employer_delete" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <p class="text-center">Do you wish to delete this subscription?</p>
                                <input type="hidden" id="employer_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_employer_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
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
// EMPLOYER DEACTIVATE BUTTON
// ==========================================
$('.employee_deactivate_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			employee_id: id,
			is_employee_deactivate: 'is_employee_deactivate'
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
// OPEN DELETE EMPLOYER MODAL
// ==========================================
$(".delete_employee_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    $("#employer_delete_id").val(id);
    $('.page_alert_danger').hide();
});





// ========================================
// DELETE EMPLOYER SUBSCRIPTION
// ========================================
$("#submit_employer_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#employer_delete_id").val();
     var url = $(this).attr('data-url');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			sub_id: id,
			delete_employer_subscription: 'delete_employer_subscription'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
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