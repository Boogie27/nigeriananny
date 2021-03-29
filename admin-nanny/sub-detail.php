<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/employees');
  return view('/admin/login');
}



// ==========================================
// CHECK IF SUB WAS CLICKED
// ==========================================
if(!Input::exists('get') || !Input::get('sid'))
{
    return view('/admin-nanny/subscription');
}




// ===========================================
// GET EMPLOYER SUBSCRIPTION
// ===========================================
$subscription = $connection->select('employer_subscriptions')->leftJoin('employers', 'employer_subscriptions.s_employer_id', '=', 'employers.id')->where('subscription_id', Input::get('sid'))->first();
if(!$subscription)
{
    return view('/admin-nanny/subscription');
}
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
                            <h4 class="title float-left">Subscribed details</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/subscription') ?>">Subscription</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="modal_subscription_container">
                            <div class="modal-img">
                                <div class="inner-modal-img">
                                    <?php  $profile_image = $subscription->e_image ? $subscription->e_image : '/employer/images/employer/demo.png' ?>
                                    <img src="<?= asset($profile_image) ?>" alt="<?= $subscription->first_name ?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <ul class="ul-sub">
                                    <li class="text-center"><?= ucfirst($subscription->first_name.' '.$subscription->last_name)?></li>
                                    <li class="text-center"><?= $subscription->email ?></li>
                                </ul>
                                <ul class="ul-sub">
                                    <li><h1><?= money($subscription->s_amount) ?></h1></li>
                                    <li class="s-type"><h4>premium</h4></li>
                                    <br>
                                    <li><b>Reference ID: </b><?= $subscription->reference ?></li>
                                    <li><b>Duration: </b><?= $subscription->s_duration ?></li>
                                    <li><b>Start date : </b><?= date('d M Y', strtotime($subscription->start_date)) ?></li>
                                    <li><b>End date : </b><?= date('d M Y', strtotime($subscription->end_date)) ?></li>
                                    <br>
                                    <li class="text-center"><span class="<?= !$subscription->is_expire ? 'delivered' : 'deactivate'?>"><?= !$subscription->is_expire ? 'active' : 'expired'?></span></li>
                                    <li class="text-center"><b>Expiry date : </b><?= $subscription->is_expire_date ? date('d M Y', strtotime($subscription->is_expire_date)) : ''; ?></li>
                                    <li class="text-center"><a href="#" id="<?= $subscription->subscription_id ?>"  data-toggle="modal"  data-target="#exampleModal_employer_delete" class="text-danger delete_employee_btn" >Delete subscription</a></li>
                                </ul> 
                                <br>
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


