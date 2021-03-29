<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}


$customers = $connection->select('users')->paginate(15);

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
                    <div class="alert-danger text-center p-3 mb-2 category_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage products</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/index.php') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin/customers.php') ?>">Customer</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Image</th>
                                    <th scope="col">Customer name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Activate</th>
                                    <th scope="col">Last login</th>
                                    <th scope="col">Date registered</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($customers->result()): 
                                foreach($customers->result() as $customer):    
                                ?>
                                    <tr>
                                        <td>
                                           <?php if($customer->user_image): ?>
                                            <img src="<?= asset($customer->user_image) ?>" alt="" class="table-img <?= $customer->is_active ? 'online' : 'offline' ?>">
                                            <?php else: ?>
                                            <img src="<?= asset('/shop/images/users/demo.png') ?>" alt="" class="table-img <?= $customer->is_active ? 'online' : 'offline' ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= ucfirst($customer->last_name).' '.ucfirst($customer->first_name)?></td>
                                        <td><?= $customer->email ?></td>
                                      
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" data-url="<?= url('/admin/ajax.php') ?>"  data-id="<?= $customer->id ?>" class="custom-control-input customer_deactivate_btn" id="customSwitch_<?= $customer->id ?>" <?= !$customer->is_deactivate ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $customer->id ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($customer->last_login)) ?></td>
                                        <td><?= date('d M Y', strtotime($customer->join_date)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin/edit-customer.php?cid='.$customer->id) ?>" title="Edit customer"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal" id="<?= $customer->id ?>" data-target="#exampleModal_customer_delete" class="delete_customer_btn" title="Delete customer"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $customers->links(); ?>

                                <?php if(!$customers->result()): ?>
                                    <div class="empty-table">There are no customers yet!</div>
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
<div class="sign_up_modal modal fade" id="exampleModal_customer_delete" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <p class="text-center">Do you wish to delete this product?</p>
                                <input type="hidden" id="customer_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="submit_customer_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){

// ==========================================
// CUSTOMER DEACTIVATE BUTTON
// ==========================================
$('.customer_deactivate_btn').click(function(e){
    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			customer_id: id,
			is_customer_deactivate: 'is_customer_deactivate'
		},
		success: function(response){
			console.log(response)
            remove_preloader()
		}
	});
});



// ==========================================
// DELETE CUSTOMER
// ==========================================
$(".delete_customer_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    $("#customer_delete_id").val(id);
    $('.category_alert_danger').hide();
});

$("#submit_customer_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#customer_delete_id").val();
     var url = $(this).attr('data-url');
     $("#modal_delete_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			customer_id: id,
			delete_customer: 'delete_customer'
		},
		success: function(response){
            var info = JSON.parse(response);
            if(info.data){
                location.reload();
            }else{
                $("#modal_delete_close").click();
                $('.category_alert_danger').show();
                $('.category_alert_danger').html('*Could not delete customer, try again later!');
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