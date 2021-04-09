<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/subscriptions');
  return view('/admin/login');
}


// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$employers = $connection->select('employers')->paginate(15);




// ===========================================
// GET ALL SUBSCRIPTION
// ===========================================
$subscriptions = $connection->select('subscription_pan')->paginate(15);



// ===============================================
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
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage subscriptions</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/add-subscription') ?>" class="view-btn-fill">Add subscription</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Type</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Access</th>
                                    <th scope="col">Activate</th>
                                    <th scope="col">Date registered</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($subscriptions->result()): 
                                foreach($subscriptions->result() as $subscription):    
                                ?>
                                    <tr>
                                        <td><?= ucfirst($subscription->type) ?></td>
                                        <td><?= $subscription->duration ?></td>
                                        <td><?= $subscription->access?></td>
                                        <td><?= money($subscription->amount) ?></td>
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  data-id="<?= $subscription->sub_id  ?>" class="custom-control-input employer_deactivate_btn" id="customSwitch_<?= $subscription->sub_id  ?>" <?= $subscription->is_feature ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $subscription->sub_id  ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td><?= date('d M Y', strtotime($subscription->date_added)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin-nanny/edit-subscription.php?sid='.$subscription->sub_id ) ?>" title="Edit employee"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal" id="<?= $subscription->sub_id  ?>" data-target="#exampleModal_subscription_delete" class="delete_employee_btn" title="Delete customer"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $subscriptions->links(); ?>

                                <?php if(!$subscriptions->result()): ?>
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
<div class="sign_up_modal modal fade" id="exampleModal_subscription_delete" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <input type="hidden" id="subscription_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_subscription_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
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
$('.employer_deactivate_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			sub_id: id,
			is_subscription_feature: 'is_subscription_feature'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                remove_preloader();
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
    $("#subscription_delete_id").val(id);
    $('.page_alert_danger').hide();
});





// ========================================
// DELETE SUBSCRIPTION
// ========================================
$("#submit_subscription_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#subscription_delete_id").val();
     var url = $(".ajax_url_page").attr('href');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			sub_id: id,
			delete_subscription_action: 'delete_subscription_action'
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