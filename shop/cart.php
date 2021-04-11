<?php include('../Connection.php');  ?>

<?php include('includes/header.php') ?>
	
<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php include('includes/search-bar.php') ?>

<?php
$setting =  $connection->select('settings')->where('id', 1)->first();   //    banner 
?>



	<!-- Inner Page Breadcrumb -->
	<section class="inner_page_breadcrumb" style="background-image: url('<?= asset($setting->cart_banner)?>');">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 offset-xl-3 text-center" >
					<div class="breadcrumb_content">
						<h4 class="breadcrumb_title">Shopping cart</h4>
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="<?= url('/shop') ?>">Home</a></li>
						    <li class="breadcrumb-item active" aria-current="page">Shopping cart</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>




<?php
if(Session::has('cart')):
?>
<!-- cart start -->
<section class="shop-checkouts">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-8 col-xl-8">
					<div class="cart_page_form style2">
						<form action="#">
							<table class="table table-responsive">
							  	<thead>
								    <tr class="carttable_row">
								    	<th class="cartm_title">Product</th>
								    	<th class="cartm_title">Price</th>
								    	<th class="cartm_title">Quantity</th>
								    	<th class="cartm_title">Total</th>
								    </tr>
							  	</thead>
							  	<tbody class="table_body">
								<?php foreach(Session::get('cart')->_items as $cart_item):
							    $product = $cart_item['product'];
							    ?>
								    <tr>
								    	<th scope="row">
								    		<ul class="cart_list">
								    			<li class="list-inline-item pr15"><a href="<?= url('/shop/ajax.php'); ?>" class="delete_cart_item_btn" id="<?= $product->id ?>"><img src="images/shop/close.png" alt="close.png"></a></li>
								    			<li class="list-inline-item pr20"><a href="detail.php?pid=<?= $product->id ?>"><img src="<?= url(explode(',', $product->big_image)[0]); ?>" class="cart_img" alt="<?= $product->product_image; ?>"></a></li>
								    			<li class="list-inline-item"><a class="cart_title" href="<?= url('/shop/detail.php?pid='.$product->id) ?>"><?= $product->product_name ?></a></li>
								    		</ul>
								    	</th>
								    	<td><?= money($cart_item['price']); ?></td>
								    	<td><input  type="number" data-url="<?= url('/shop/ajax.php') ?>" id="<?= $product->id ?>" class="cart_count text-center cart_count_ajax_btn" min="1" value="<?= $cart_item['quantity']?>"></td>
								    	<td class="cart_total"><?= money($cart_item['total']); ?></td>
								    </tr>
                                <?php endforeach; ?>
							  	</tbody>
							</table>
						</form>
					</div>
				</div>
				<div class="col-lg-4 col-xl-4">
					<div class="order_sidebar_widget mb30">
						<h4 class="title">Cart Totals</h4>
						<ul>
							<li class="subtitle"><p>Subtotal <span class="float-right"><?= money(Session::get('cart')->_totalPrice); ?></span></p></li>
							<li class="subtitle"><p>Shipping fee <span class="float-right"><?= money(Session::get('cart')->_totalShipping); ?></span></p></li>
							<li class="subtitle"><p>Total <span class="float-right totals color-orose"><?= money(Session::get('cart')->_totalPrice + Session::get('cart')->_totalShipping); ?></span></p></li>
						</ul>
					</div>
					<div class="ui_kit_button payment_widget_btn">
						<a href="<?= url('/shop/ajax.php')?>" class="btn dbxshad btn-lg btn-thm3 circle btn-block" id="check_out_btn_ajax">Proceed To Checkout</a>
					</div>
				</div>
			</div>
		</div>
	</section>
<!-- cart end-->

<?php else: ?>
	<div class="empty-cart-container">
		<div class="empty-cart">
			<!-- <span class="flaticon-shopping-bag pr5 fz20 cart_icon"></span> -->
			<img src="<?= asset('/shop/images/cart.jpg') ?>" alt="cart" class="shopping-cart-img">
			<h4>Empty shopping cart</h4>
			<a href="<?= url('/shop/index.php') ?>" class="app-btn">Continue shopping</a>
		</div>
	</div>
<?php endif; ?>



<!-- footer -->
<?php include('includes/footer.php') ?>





<script>
$(document).ready(function(){
	
// ============================================
// // INCREASE OR DECREASE CART QUANTITY
// ============================================

$(".cart_count_ajax_btn").on('change', function(){
    var url = $(this).attr('data-url');
	var id = $(this).attr('id');
	var qty = $(this).val();

	$.ajax({
		url: url,
		method: 'post',
		data: {
			product_id: id,
			quantity: qty,
			cart_quantity: 'cart_quantity'
		},
		success: function(response){
			if(response.trim() == 1)
			{
                location.reload();
			}
		}
	});
});




// ===========================================
// DELETE CART ITEM
// ===========================================
$(".delete_cart_item_btn").click(function(e){
    e.preventDefault();
	var url = $(this).attr('href');
	var id = $(this).attr('id');

	$.ajax({
		url: url,
		method: 'post',
		data: {
			product_id: id,
			delete_cart_item: 'delete_cart_item'
		},
		success: function(response){
			if(response.trim() == 1){
				location.reload();
			}
		}
	});

});





// ================================================
// CHECKOUT 
// ================================================
$("#check_out_btn_ajax").click(function(e){
    e.preventDefault();
	var url = $(this).attr('href');

	$.ajax({
		url: url,
		method: 'post',
		data: {
			check_out_check: 'check_out_check'
		},
		success: function(response){
			location.assign(response.trim());
		}
	})


});



});
</script>