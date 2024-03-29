<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/testimonial');
  return view('/admin/login');
}


// ===========================================
// GET ALL TESTIMONIAL
// ===========================================
$testimonials = $connection->select('testimonial')->paginate(15);



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
                    <?php if(Session::has('error')): ?>
                        <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                    <?php endif; ?>
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage testimonial</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/add-testimonial') ?>" class="view-btn-fill">Add testimonial</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Image</th>
                                    <th scope="col">Full name</th>
                                    <th scope="col">Featured</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($testimonials->result()): 
                                foreach($testimonials->result() as $testimonial):    
                                ?>
                                    <tr>
                                        <td>
                                           <?php if($testimonial->image): ?>
                                            <img src="<?= asset($testimonial->image) ?>" alt="<?= $testimonial->first_name?>" class="table-img">
                                            <?php else: ?>
                                            <img src="<?= asset('/images/testimonial/demo.png') ?>" alt="<?= $testimonial->first_name?>" class="table-img">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= ucfirst($testimonial->last_name).' '.ucfirst($testimonial->first_name)?></td>
                                      
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  data-id="<?= $testimonial->id ?>" class="custom-control-input testimonial_feature_btn" id="customSwitch_<?= $testimonial->id ?>" <?= $testimonial->is_featured ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $testimonial->id ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($testimonial->date_added)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin-nanny/testimonial-detail?tid='.$testimonial->id) ?>" title="Edit employee"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal" id="<?= $testimonial->id ?>" data-target="#exampleModal_testimony_delete" class="delete_testimony_btn" title="Delete customer"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $testimonials->links(); ?>

                                <?php if(!$testimonials->result()): ?>
                                    <div class="empty-table">There are no testimonial yet!</div>
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
<div class="sign_up_modal modal fade" id="exampleModal_testimony_delete" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <p class="text-center">Do you wish to delete this testimony?</p>
                                <input type="hidden" id="testimony_delete_id" value="">
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

// ==========================================
// TESTIMONIAL FEATURE BUTTON
// ==========================================
$('.testimonial_feature_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			testimonial_id: id,
			is_testimonial_feature: 'is_testimonial_feature'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                location.reload()
            }else if(data.data){
                remove_preloader();
            }
		},
        error: function(){
            remove_preloader();
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('There was an error, try again later!');
        }
	});
});







// ==========================================
// OPEN DELETE EMPLOYER MODAL
// ==========================================
$(".delete_testimony_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    $("#testimony_delete_id").val(id);
    $('.page_alert_danger').hide();
});





// ========================================
// DELETE TESTIMONIAL
// ========================================
$("#submit_employee_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#testimony_delete_id").val();
     var url = $(this).attr('data-url');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			testimonial_id: id,
			delete_testimonial_action: 'delete_testimonial_action'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                location.reload();
            }else if(data.data){
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