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
						<h4 class="page_title">Privacy policy</h4>
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="<?= url('/shop')?>">Home</a></li>
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
            <h3 class="ph"><b>Privacy policy</b></h3>
            <br>
            <?php if($settings->privacy_policy):?>
             <?= $settings->privacy_policy ?>
            <?php endif;?>
            </div>
        </div>
   </div>












<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>






<!-- industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
 printer took a galley of type and scrambled it to make a type specimen book. It has survived not only
  five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
  It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more
 recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
 Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin 
 literature from 45 BC, making it over 2000 years old. Richard McClintock, 
 a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, 
 consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, 
 discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum 
 et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory 
 of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes 
 from a line in section 1.10.32.

The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 
1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by 
English versions from the 1914 translation by H. Rackham. -->