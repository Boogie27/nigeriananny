<?php include('../Connection.php');  ?>


<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return view('/admin/login');
}




if(!Input::exists('get') || empty(Input::get('pid')) || !is_numeric(Input::get('pid')))
{
    return view('/admin/transactions');
}

$connection = new DB();
$orderDetail = $connection->select('paid_products')->leftJoin('shop_products', 'paid_products.product_id','=', 'shop_products.id')
                            ->leftJoin('users', 'paid_products.paid_buyer_id','=', 'users.id')
                            ->where('paid_product_id ', Input::get('pid'))->first();


$order_information = $connection->select('shop_transactions')->where('reference', $orderDetail->paid_reference)->first();




// ********** CANCLE NOTIFICATION ***********//
$notification = $connection->select('notifications')->where('to_id', 1)->where('to_user', 'admin')->where('is_seen', 0)
                       ->where('not_reference', $orderDetail->paid_reference)->where('link', '/admin/order-detail.php?pid='.Input::get('pid'))->first();
if($notification)
{
    $connection->update('notifications', [
                     'is_seen' => 1
                ])->where('to_id', 1)->where('not_reference', $orderDetail->paid_reference)->where('to_user', 'admin')->where('is_seen', 0)->where('link', '/admin/order-detail.php?pid='.Input::get('pid'))->save();
}





//************ app banner settings *************//
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
                       <?php endif;?>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Order Details</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin/transaction-orders') ?>"> Transaction order</a></li>
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
                                                <h3><?= strtoupper($orderDetail->last_name.' '.$orderDetail->first_name)?> <span class="money"><?= money($orderDetail->total_price)?></span></h3>
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
                                                <label for="" class='h'><b>Order detail</b></label>
                                                    <ul>
                                                            <li><b>Product name: </b><?= $orderDetail->product_name?></li>
                                                            <li><b>Reference ID: </b><?= $orderDetail->paid_reference?></li>
                                                            <li><b>Product price: </b><span class="text-success"><?= money($orderDetail->price)?></span></li>
                                                            <li><b>Quantity: </b><?= $orderDetail->quantity - $orderDetail->cancled_quantity?></li>
                                                            <li><b>Total price: </b><span class="text-warning"><?= money($orderDetail->total_price)?></span></li>
                                                            <li><b>Shipping Method: </b><span><?=$orderDetail->shipping_method ?></span></li>
                                                            <li><b>Shipping fee: </b><span class="text-danger"><?= money($orderDetail->shipping_fee * ($orderDetail->quantity - $orderDetail->cancled_quantity)) ?></span></li>
                                                    </ul>
                                               </div>
                                               <div class="col-lg-6">
                                                    <label for="" class='h'><b>Order status</b></label>
                                                    <ul>
                                                        <li><b>Order placed on:</b> <span class="view-btn"><?= date('d M Y', strtotime($orderDetail->item_date_paid)) ?></span></li><br>
                                                        <li><b>Shipped on:</b> <span class="view-btn <?= $orderDetail->shipped_on ? 'bg-success' : 'bg-warning' ?>"><?= $orderDetail->shipped_on ? date('d M Y', strtotime($orderDetail->shipped_on)) : 'pending' ?></span></li><br>
                                                        <li><b>Delivered on:</b> 
                                                                <span class="view-btn <?= $orderDetail->delivered_on ? 'bg-success' : 'bg-warning'?>"><?= $orderDetail->delivered_on ? date('d M Y', strtotime($orderDetail->delivered_on)) : 'pending' ?></span>
                                                        </li>
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


<a href="<?= url('/admin/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>








<?php  include('includes/footer.php') ?>



