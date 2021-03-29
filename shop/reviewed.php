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
$reviews = $connection->select('product_review')->leftJoin('shop_products', 'product_review.product_id', '=', 'shop_products.id')->where('p_user_id', Auth::user('id'))->get();
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
								<h4 class="title float-left">Product reviews</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="<?= url('/shop/index.php') ?>">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Reviews</li>
								</ol>
							</nav>
						</div>
						<div class="col-lg-12">
							<div class="my_course_content_container">
								<div class="my_setting_content">
									<div class="my_setting_content_header">
										<div class="my_sch_title">
											<h4 class="m0">Reviews</h4>
										</div>
									</div>
                                    <!-- order cancle start -->
                                    
									<?php if(count($reviews)):  ?>
									<div class="row">
									<?php	foreach($reviews as $review): ?>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="r-container">
                                                    <div class="cancled-order-container">
                                                        <div class="co-head">
                                                            <ul>
                                                                <li class="return-date">On <?= date('d M Y', strtotime($review->review_date_added)) ?></li>
                                                            </ul>
                                                        </div>

                                                        <div class="return-container">
                                                            <div class="c-product-img">
                                                                <a href="detail.php?pid=<?= $review->product_id ?>">
                                                                    <img src="<?= asset(explode(',', $review->big_image)[0]) ?>" class="cart_img" alt="<?= $review->product_name ?>">
                                                                </a>
                                                            </div>
                                                            <ul class="cancle-details">
                                                                <li><?= user_star($review->product_stars) ?></li>
                                                                <li><b>Title:</b> <?= $review->review_title ?></li>
                                                                <li><b>Review:</b> <?= $review->review_comment ?></li>
                                                            </ul>
                                                        </div>
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                    <?php endforeach; ?>
                                    </div>
									<?php else: ?>
									<div class="empty-cart">
										<span class="fa fa-thumb-up pr5 fz20 cart_icon"></span>
										<h4>You have no review yet!</h4>
										<?php if(!Auth::is_loggedin()): ?>
										<p class="">Have an account? <a href="<?= url('/shop/login.php') ?>" class="text-primary"> Login</a> or <a href="<?= url('/shop/register.php') ?>" class="text-primary"> Register</a> to view reviews.</p>
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
