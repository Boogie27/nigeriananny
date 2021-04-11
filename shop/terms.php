<?php include('../Connection.php');  ?>
<?php

// =======================================
// GET PRIVACY POLICY
// =======================================
$settings = $connection->select('settings')->where('id', 1)->first();

?>
<?php include('includes/header.php') ?>


<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php include('includes/search-bar.php') ?>

    

    	<!-- Inner Page Breadcrumb -->
	<section class="inner_page_breadcrumb" style="background-image: url('<?= asset($settings->home_banner); ?>');">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 offset-xl-3 text-center breadcrumb_content_x">
					<div class="breadcrumb_content">
						<h4 class="page_title">Terms & Condition</h4>
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="#">Home</a></li>
						    <li class="breadcrumb-item active" aria-current="page">Privacy</li>
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


   <!-- jobs  start-->
   <div class="page-content">
        <div class="privacy-container">
            <div class="privacy-policy">
            <h3 class="ph"><b>Terms & Condition</b></h3>
            <br>
            <?php if($settings->terms):?>
             <?= $settings->terms ?>
            <?php endif;?>
            </div>
        </div>
   </div>





    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>

