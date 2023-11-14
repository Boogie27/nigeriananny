<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/message-detail.php?mid='.Input::get('mid'));
  return view('/admin/login');
}




// ===========================================
// CHECK IF MESSAGE WAS CLICK
// ===========================================
if(!Input::exists('get') || !Input::get('mid'))
{
    return view('/admin-nanny/message');
}



// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$message = $connection->select('contact_us')->where('id', Input::get('mid'))->first();
if(!$message)
{
    return view('/admin-nanny/message');
}




// =========================================
// SET MESSAGE TO SEEN
// =========================================
if(!$message->is_seen)
{
    $update = $connection->update('contact_us', [
        'is_seen' => 1
    ])->where('id', Input::get('mid'))->save();
}


// ============================================
    // app banner settings
// ============================================
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
                            <h4 class="title float-left">Message detail</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/message') ?>">Messages</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                         <div class="account-x">
                            <h3 class="rh-head">Message detail</h3><br><br>
                            <div class="message-body">
                                <ul class="ul-message">
                                    <li><b>Name:</b> <?= ucfirst($message->full_name)?> <span class="float-right text-success"><?= date('d M Y', strtotime($message->date))?></span></li>
                                    <li><b>Email:</b> <?= $message->email ?></li>
                                    <li><b>Subject:</b> <?= ucfirst($message->subject)?></li>
                                    <li>
                                        <b>Message:</b><br> 
                                        <p><?= ucfirst($message->message)?></p>
                                    </li>
                                </ul>
                                <div class="delete-msg text-right">
                                    <a href="#" data-toggle="modal"  data-target="#exampleModal_message_delete" class="btn btn-danger">Delete message</a>
                                </div>
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
<div class="sign_up_modal modal fade" id="exampleModal_message_delete" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <p class="text-center">Do you wish to delete this message?</p>
                                <input type="hidden" id="message_delete_id" value="<?= Input::get('mid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_message_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
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
// OPEN DELETE EMPLOYEE MODAL
// ==========================================
$(".delete_employee_btn").click(function(e){
    e.preventDefault();
    $('.page_alert_danger').hide();
});





// ========================================
// DELETE MESSAGE
// ========================================
$("#submit_message_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#message_delete_id").val();
     var url = $(this).attr('data-url');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			message_id: id,
			delete_message_action: 'delete_message_action'
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