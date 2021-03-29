<?php include('../Connection.php');  ?>


<?php
if(!Auth::is_loggedin())
{
	Session::put('old_url', current_url());
	return Redirect::to('login.php');
}
?>



<?php include('includes/header.php') ?>


<?php include('includes/dash-board-navigation.php'); ?>


<?php include('includes/account-mobile-navigation.php') ?>


<?php include('includes/side-bar.php'); ?>


<?php 
$connection = new DB();
$cancled_products = $connection->select('cancled_product')->leftJoin('shop_products', 'cancled_product.cancled_product_id', '=', 'shop_products.id')->where('cancled_user_id', Auth::user('id'))->get();
?>

<br>



	<!-- Our Dashbord -->
	<div class="our-dashbord dashbord">
		<div class="dashboard_main_content">
			<div class="container-fluid">
				<div class="main_content_container p-3">
					<div class="row">

					<div class="col-lg-12">
                    <!-- mobile side bar -->
						<?php include('includes/mobile-side-bar.php'); ?>
					<!-- mobile side bar end -->
                    </div>

						<div class="col-lg-12">
							<nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
								<h4 class="title float-left">Cancled orders</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="<?= url('/shop/index.php') ?>">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
								</ol>
							</nav>
						</div>
						<div class="col-lg-12">
							<div class="my_course_content_container">
								<div class="my_setting_content">
									<div class="my_setting_content_header">
										<div class="my_sch_title">
											<h4 class="m0">Cancled orders</h4>
										</div>
									</div>
									<!-- order cancle start -->
									<?php if(count($cancled_products)): 
										foreach($cancled_products as $cancled_product): ?>
                                    <div class="r-container">
                                        <div class="cancled-order-container">
                                            <div class="co-head">
                                                <ul>
                                                    <li class="refund-status">Cancled </li>
                                                    <li class="return-date">On <?= date('d M Y', strtotime($cancled_product->cancled_date)) ?></li>
                                                </ul>
                                            </div>

                                            <div class="return-container">
                                                <div class="c-product-img">
											    	<a href="detail.php?pid=<?= $cancled_product->id ?>">
														<img src="<?= asset(image($cancled_product->big_image, 0)) ?>" class="cart_img" alt="<?= $cancled_product->product_name ?>">
													</a>
												</div>
                                                <ul class="cancle-details">
													<a href="detail.php?pid=<?= $cancled_product->id ?>">
														<li><b>Product name:</b> <?= $cancled_product->product_name ?></li>
													</a>
                                                    <li><b>Product price:</b> <?= money($cancled_product->product_price) ?></li>
                                                    <li><b>Product Qty:</b> <?= $cancled_product->cancled_product_quantity ?></li>
                                                    <li><b>Total:</b> <?= money($cancled_product->cancled_total) ?></li>
                                                </ul>

                                                <ul class="r-product-btn">
                                                    <li><a href="<?= url('/shop/cancle-order-detail.php?ocid='.$cancled_product->cancled_id) ?>">See status history</a></li>
                                                </ul>
											</div>
										
										</div>
									</div>
									<?php endforeach; ?>
									<?php else: ?>
									<div class="empty-cart">
										<span class="flaticon-shopping-bag pr5 fz20 cart_icon"></span>
										<h4>No cancled order yet!</h4>
										<?php if(!Auth::is_loggedin()): ?>
											<p class="">Have an account? <a href="<?= url('/shop/login.php') ?>" class="text-primary"> Login</a> or <a href="<?= url('/shop/register.php') ?>" class="text-primary"> Register</a> to view orders.</p>
										<?php endif;?>
										<a href="<?= url('/shop/index.php') ?>" class="app-btn">Continue shopping</a>
									</div>
									<?php endif;?>
                                    <!-- order cancle end -->                                    
								</div>
                            </div>
                           
						</div>
					</div>
					<div class="row mt50 pb50">
						<div class="col-lg-12">
							<div class="copyright-widget text-center">
								<p class="color-black2"></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>







    <!-- footer -->
<div style="position: relative; z-index: 1000;">
	<?php include('includes/footer.php') ?>
</div>






<script>
$(document).ready(function(){







});
</script>