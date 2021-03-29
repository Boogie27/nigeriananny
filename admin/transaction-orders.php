<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}

if(!Input::exists('get') || empty(Input::get('tid')))
{
    return Redirect::to('transactions.php');
}



    $transactions = $connection->select('paid_products')->leftJoin('shop_products', 'paid_products.product_id','=', 'shop_products.id')
                    ->where('paid_reference', Input::get('tid'))->paginate(15);

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
                        <div class="alert-danger p-3 mb-2 text-center alert_transaction_error" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage orders</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/index.php') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin/transactions.php') ?>"> Transactions</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Reference</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Shipped</th>
                                    <th scope="col">Delivery status</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php 
                                $table_count = 0;
                                if($transactions->result()): 
                                foreach($transactions->result() as $transaction):  
                                    if($transaction->quantity > $transaction->cancled_quantity): 
                                        $table_count++;
                                    ?>
                                    <tr>
                                            <td>
                                                <a href="<?= url('/admin/product-detail.php?pid='.$transaction->product_id) ?>">
                                                    <img src="<?= asset(image($transaction->big_image, 0)) ?>" alt="<?= $transaction->product_name ?>" class="table-img">
                                                    <?= ucfirst($transaction->product_name)?>
                                                </a>
                                            </td>
                                            <td><?= $transaction->paid_reference ?></td>
                                            <td><?= $transaction->quantity - $transaction->cancled_quantity?></td>
                                            <td><?= money($transaction->total_price) ?></td>
                                            <td>
                                                <div class="ui_kit_whitchbox">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" data-url="<?= url('/admin/ajax.php') ?>" data-id="<?= $transaction->paid_product_id ?>" class="custom-control-input order_shipped_btn" id="customSwitch<?= $transaction->paid_product_id ?>" <?= $transaction->shipped_on ? 'checked' : '';?>>
                                                        <label class="custom-control-label" for="customSwitch<?= $transaction->paid_product_id ?>"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="<?= url('/admin/ajax.php') ?>" id="<?= $transaction->paid_product_id ?>" class="product_delivery_btn" data-toggle="modal" data-target="#modal_delivery_form">
                                                    <span class="<?= $transaction->is_delivered ? 'delivered' : 'pending'?>"><?= $transaction->is_delivered ? 'Delivered' : 'Pending'?></span>
                                                </a>
                                            </td>
                                            <td>
                                            <a href="<?= url('/admin/order-detail.php?pid='.$transaction->paid_product_id ) ?>"> <span class="view-btn">view details</span></a>
                                            </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $transactions->links(); ?>

                                <?php if($table_count == 0 && Input::get('tid')): ?>
                                    <div class="empty-table">Transactions has been cancled! <a href="<?= url('/admin/ajax.php') ?>" class="hide_transaction_btn float-right view-btn pr-3" id="<?= Input::get('tid') ?>">Hide</a></div>
                                <?php endif ?>
                                <?php if($transactions->result() == 0): ?>
                                <div class="empty-table">There are no transactions yet!</div>
                                <?php endif; ?>
                            </div>
                        </div><!-- table end-->
                    </div>
                </div>
                <div class="row mt50 mb50">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="copyright-widget text-center">
                            <p class="color-black2"><?= $banner->alrights?></p>
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
<div class="sign_up_modal modal fade" id="modal_delivery_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="delete_modal_subcategroy_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="alert_deliver_error alert-danger text-center p-2 mb-2" style="display: none;">Something</div>
                            <div class="heading">
                                <p class="text-center">Do you wish to set order delivery status?</p>
                                <input type="hidden" id="paid_order_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="confirm_order_delivery_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Set status</button>
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
$(".product_delivery_btn").click(function(e){
    e.preventDefault();
    $(".alert_deliver_error").hide();
    var order_id = $(this).attr('id');
     $("#paid_order_id").val(order_id);
});





// =========================================
// SET ORDER DELIVERY STATUS
// =========================================
$("#confirm_order_delivery_btn").click(function(e){
    e.preventDefault();
    var order_id = $("#paid_order_id").val();
    var url = $(this).attr('data-url');

      $.ajax({
        url: url,
        method: "post",
        data: {
            order_id: order_id,
            order_delivery_status: 'order_delivery_status'
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







// =========================================
// SET ORDER SHIPPING STATUS
// =========================================
$(".order_shipped_btn").click(function(e){
    var order_id = $(this).attr('data-id');
    var url = $(this).attr('data-url');

      $.ajax({
        url: url,
        method: "post",
        data: {
            order_id: order_id,
            order_shipping_status: 'order_shipping_status'
        },
        success: function (response){
            var info = JSON.parse(response);
            if(!info.data){
                $(".alert_deliver_error").html('*Something went wrong, try again later');
                $(".alert_deliver_error").show();
            }
        }
    });
});







// =========================================
// SET ORDER SHIPPING STATUS
// =========================================
$(".hide_transaction_btn").click(function(e){
    e.preventDefault();
    $(".alert_transaction_error").hide();
    var reference = $(this).attr('id');
    var url = $(this).attr('href');


     $.ajax({
        url: url,
        method: "post",
        data: {
            reference: reference,
            transaction_status: 'transaction_status'
        },
        success: function (response){
            var info = JSON.parse(response);
            if(info.location){
                location.assign(info.location);
            }else{
                $(".alert_transaction_error").html('*Something went wrong, try again later');
                $(".alert_transaction_error").show();
            }
        }
    });
});


// end
});
</script>