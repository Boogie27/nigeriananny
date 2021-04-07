<?php include('../Connection.php');  ?>

<?php
if(!Auth::is_loggedin())
{
	return Redirect::to('login.php');
}
?>



<?php include('includes/header.php') ?>

	
<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php include('includes/search-bar.php') ?>




<!-- Inner Page Breadcrumb -->
<section class="inner_page_breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 offset-xl-3 text-center">
                <div class="breadcrumb_content">
                    <h4 class="page_title">My order</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/shop/index.php') ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My order</li>
                    </ol>
				</div>
				<div class="banner-icon-x">
					<i class="fa fa-shopping-cart"></i>
					<span class="cart_total_quantity"><?= Session::has('cart') ? Session::get('cart')->_totalQty : 0 ?></span>
				</div>
            </div>
        </div>
    </div>
</section>

<?php
   $connection = new DB();
   $orders = $connection->select('shop_transactions')->where('buyer_id', Auth::user('id'))->get();
   if(count($orders)):
?>


<!-- Shop Order Content -->
<section class="shop-order">
		<div class="container">
			<div class="row">
			<!-- order start -->
			    <?php foreach($orders as $order) :?>
				<div class="col-xl-8 offset-xl-2">
					<div class="shop_order_box">
						<h4 class="main_title">Order</h4>
						<div class="order_list_raw">
							<ul>
								<li class="list-inline-item">
									<h4>Order Number</h4>
									<p>3743</p>
								</li>
								<li class="list-inline-item">
									<h4>Date</h4>
									<p>December 21, 2019</p>
								</li>
								<li class="list-inline-item">
									<h4>Total</h4>
									<p>$76.70</p>
								</li>
								<li class="list-inline-item">
									<h4>Payment Method</h4>
									<p>Direct bank transfer</p>
								</li>
							</ul>
						</div>
						<div class="order_details">
							<h4 class="title text-center mb40">Order Details</h4>
							<?php $products = $connection->select('paid_products')->leftJoin('shop_products', 'paid_products.product_id', '=', 'shop_products.id')->where('paid_buyer_id', $order->buyer_id)->where('paid_reference', $order->reference)->get(); 
							 if(count($products)): 
							 $subtotal = 0;
							 ?>
							<div class="od_content">
								<ul>
									<?php foreach($products as $product): 
									$subtotal += $product->total_price;
									?>
									<li>
                                        <a href="#"><img src="<?= url($product->small_image) ?>" alt="<?= $product->product_name ?>"></a>
                                        <?= $product->product_name ?> × <?= $product->quantity ?> <span class="float-right"><?= money($product->total_price) ?></span>
                                    </li>
									<?php endforeach; ?>
							
									<li>Shipping <span class="float-right tamount"><?= money($order->amount - $subtotal) ?></span></li>
									<li>Subtotal <span class="float-right tamount"><?= money($subtotal) ?></span></li>
									<li>Total <span class="float-right tamount"><?= money($order->amount) ?></span></li>
									<li>Note <span class="float-right"><?= $order->message ?></span></li>
								</ul>
							</div>
							<?php endif; ?>
							<div class="od_details_contact text-center">
								<h4 class="title2">Billing Address</h4>
								<p class="mb0"><?= $order->address ?></p>
								<p class="mb0"><?= $order->city ?>/<?= $order->state ?></p>
								<p class="mb0"><?= $order->country ?></p>
								<p class="mb0"><p class="mb0"><?= $order->phone ?></p></p>
								<p><a href="#" class=""><p class="mb0"><?= $order->email ?></p></a></p>
							</div>
						</div>
					</div>
				</div>
                <?php endforeach; ?>
				<!-- order end -->
			</div>
		</div>
	</section>

<?php else: ?>
    <div class="empty-cart">
		<span class="flaticon-shopping-bag pr5 fz20 cart_icon"></span>
		<h4>You have no order yet!</h4>
		<a href="<?= url('/shop/index.php') ?>" class="app-btn">Continue shopping</a>
	</div>
<?php endif; ?>


    <!-- order end -->



        
<!-- footer -->
<?php include('includes/footer.php') ?>