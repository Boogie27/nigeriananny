<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}


$transactions = $connection->select('cancled_product')->leftJoin('shop_products', 'cancled_product.cancled_product_id','=', 'shop_products.id')->paginate(50);


$cancleAmount = 0;
$amounts = $connection->select('paid_products')->get();
if($amounts)
{
    foreach($amounts as $amount)
    {
        $cancleAmount += $amount->price * $amount->cancled_quantity;
    }
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
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage transactions</h4>
                            <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><b>Total refunds:</b> <span class="delivered"><?= money($cancleAmount) ?></span></li>
                            </ol>
                        </nav>
                        <div class="text">
                            Total Cancled Orders: <?= count($transactions->result())?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Product name</th>
                                    <th scope="col">Reference</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Refund</th>
                                    <th scope="col">Date cancled</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($transactions->result()): 
                                foreach($transactions->result() as $transaction):    
                                ?>
                                   <tr>
                                        <td>
                                            <a href="<?= url('/admin/cancled-order-detail.php?cod='.$transaction->cancled_id) ?>">
                                                <img src="<?= asset(image($transaction->big_image, 0)) ?>" alt="<?= $transaction->product_name ?>" class="table-img">
                                            </a>
                                        </td>
                                        <td><?= ucfirst($transaction->product_name)?></td>
                                        <td><?= $transaction->cancled_reference?></td>
                                        <td><?= $transaction->cancled_product_quantity ?></td>
                                        <td><?= money($transaction->cancled_total) ?></td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#modal_refund_form" class="cancle_refund_btn" id="<?= $transaction->cancled_id ?>">
                                                <span class="view-btn <?= $transaction->is_refund ? 'bg-success' : 'bg-warning' ?>"><?= $transaction->is_refund ? 'Refund' : 'Pending' ?></span>
                                            </a>
                                        </td>
                                        <td><?= date('d M Y', strtotime($transaction->cancled_date)) ?></td>
                                        <td>
                                           <a href="<?= url('/admin/cancled-order-detail.php?cod='.$transaction->cancled_id ) ?>"> <span class="view-btn">view orders</span></a>
                                        </td>
                                   </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $transactions->links(); ?>

                                 <?php if(!count($transactions->result())):?>
                                    <div class="empty-table">There are no transactions yet!</div>
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
<div class="sign_up_modal modal fade" id="modal_refund_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_refund_close_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="alert_deliver_error alert-danger text-center p-2 mb-2" style="display: none;">Something</div>
                            <div class="heading">
                                <p class="text-center">Do you wish to set refund status?</p>
                                <input type="hidden" id="refund_product_id_input" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="confirm_refund_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Set status</button>
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
  
// =========================================
// ORDER DELIVERY STATUS MODAL
// =========================================
$(".cancle_refund_btn").click(function(e){
    e.preventDefault();
    $(".alert_deliver_error").hide();
    var cancle_id = $(this).attr('id');
     $("#refund_product_id_input").val(cancle_id);
});





// =========================================
// SET REFUND STATUS
// =========================================
$("#confirm_refund_btn").click(function(e){
    e.preventDefault();
    var cancle_id = $("#refund_product_id_input").val();
    var url = $(this).attr('data-url');

      $.ajax({
        url: url,
        method: "post",
        data: {
            cancle_id: cancle_id,
            cancle_refund_status: 'cancle_refund_status'
        },
        success: function (response){
            var info = JSON.parse(response);
            if(info.data){
                location.reload();
            }else{
                $(".alert_deliver_error").html('*Something went wrong, try again later');
                $(".alert_deliver_error").show();
            }
        }
    });
});








});
</script>















