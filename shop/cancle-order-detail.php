<?php include('../Connection.php');  ?>


<?php
if(!Auth::is_loggedin())
{
  Session::delete('users');
  return Redirect::to('login.php');
}


if(!Input::exists('get') || empty(Input::get('ocid')) || !is_numeric(Input::get('ocid')))
{
    return Redirect::to('order.php');
}

$connection = new DB();
$orderDetail = $connection->select('cancled_product')->leftJoin('shop_products', 'cancled_product.cancled_product_id','=', 'shop_products.id')
                            ->leftJoin('users', 'cancled_product.cancled_user_id','=', 'users.id')
                            ->where('cancled_id', Input::get('ocid'))->first();


$order_information = $connection->select('shop_transactions')->where('reference', $orderDetail->cancled_reference)->first();


?>

<?php include('includes/header.php'); ?>


<?php include('includes/dash-board-navigation.php'); ?>


<?php include('includes/account-mobile-navigation.php') ?>


<?php include('includes/side-bar.php'); ?>




<!-- Our Dashbord -->
<div class="our-dashbord dashbord">
    <div class="dashboard_main_content">
        <div class="container-fluid">
            <div class="main_content_container">
                <div class="row">
                    <div class="col-lg-12">
                    <!-- mobile side bar -->
						<?php include('includes/mobile-side-bar.php'); ?>
					<!-- mobile side bar end -->
                    </div>
                    <div class="col-lg-12">
                       <?php if(Session::has('success')): ?>
                            <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                       <?php endif;?>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Cancle Details</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/shop/index.php') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Cancle order</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="edit-product-form">
                            <form action="<?= current_url() ?>" method="post">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="product-detail">
                                            <div class="p-header">
                                                <h3><?= strtoupper($orderDetail->last_name.' '.$orderDetail->first_name)?> <span class="money"><?= money($orderDetail->cancled_total)?></span></h3>
                                                 <ul>
                                                     <li>
                                                        <?php if($orderDetail->user_image): ?>
                                                            <img src="<?= asset($orderDetail->user_image) ?>" alt="<?= $orderDetail->first_name?>">
                                                        <?php else:?>
                                                            <img src="<?= asset('/shop/images/users/demo.png') ?>" alt="<?= $orderDetail->first_name?>">
                                                        <?php endif; ?>
                                                     </li>
                                                     <br>
                                                     <li><b>Email: </b><?= $orderDetail->email?></li>
                                                     <li><b>Phone: </b><?= $orderDetail->phone?></li>
                                                     <li><b>Default address: </b><?= $orderDetail->address?></li>
                                                     <li><b>City: </b><?= $orderDetail->city?></li>
                                                     <li><b>State: </b><?= $orderDetail->state?></li>
                                                     <li><b>Country: </b><?= $orderDetail->country?></li>
                                                 </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="product-detail">
                                             <div class="d-img">
                                                 <img src="<?= asset(image($orderDetail->big_image, 0))?>" alt="<?= $orderDetail->product_name ?>">                                               
                                             </div>
                                        </div>
                                    </div>
                                
                                   
                                    <div class="col-lg-12">
                                        <div class="product-detail">
                                           <div class="row">
                                               <div class="col-lg-6">
                                                <label for="" class='h'><b>Cancle detail</b></label>
                                                    <ul>
                                                            <li><b>Product name: </b><?= $orderDetail->product_name?></li>
                                                            <li><b>Reference ID: </b><?= $orderDetail->cancled_reference?></li>
                                                            <li><b>Product price: </b><span class="text-success"><?= money($orderDetail->cancled_product_price)?></span></li>
                                                            <li><b>Quantity: </b><?= $orderDetail->cancled_product_quantity ?></li>
                                                            <li><b>Total price: </b><span class="text-warning"><?= money($orderDetail->cancled_total)?></span></li>
                                                            <li><b>Reference ID: </b><?= $orderDetail->cancled_reference?></li>
                                                            <li><b>Shipping fee: </b><span class="text-danger"><?= money($orderDetail->shipping_fee * $orderDetail->cancled_product_quantity) ?></span></li>
                                                    </ul>
                                               </div>
                                               <div class="col-lg-6">
                                                    <label for="" class='h'><b>Cancle status</b></label>
                                                    <ul>
                                                        <li><b>Cancled  on:</b> <span class="view-btn"><?= date('d M Y', strtotime($orderDetail->cancled_date)) ?></span></li><br>
                                                        <li><b>Refund on:</b> <span class="view-btn <?= $orderDetail->refund_date ? 'bg-success' : 'bg-warning'?>"><?= $orderDetail->refund_date ? date('d M Y', strtotime($orderDetail->refund_date)) : 'pending' ?></span></li><br>
														<li><b>Refund amount:</b> <span class="view-btn <?= $orderDetail->is_refund ? 'bg-success' : 'bg-warning'?>"><?= $orderDetail->is_refund ? money($orderDetail->cancled_total) : 'pending' ?></span></li>
                                                    </ul>
                                               </div>
                                           </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="product-detail">
                                            <?php if($order_information):?>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                    <label for="" class='h'><b>Contact information</b></label>
                                                        <ul>
                                                            <li><b>Full name:</b> <?= ucfirst($order_information->first_name.' '.$order_information->last_name)?></li>
                                                            <li><b>Email: </b><?= $order_information->email ?></li>
                                                            <li><b>Phone: </b><?= $order_information->phone ?></li>
                                                            <li><b>Postal code: </b><?= $order_information->postal_code ?></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="" class='h'><b>Shipping information</b></label>
                                                        <ul>
                                                            <li><b>Address: </b><?= $order_information->address ?></li>
                                                            <li><b>City: </b><?= $order_information->city ?></li>
                                                            <li><b>State: </b><?= $order_information->state ?></li>
                                                            <li><b>Country: </b><?= $order_information->country ?></li>
                                                            <li><b>Message: </b>
                                                                <p><?= $order_information->message ?></p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-center p-3">There are no shipping informations!</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>


<a href="<?= url('/admin/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>








<?php  include('includes/footer.php') ?>



