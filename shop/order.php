<?php include('../Connection.php');  ?>


<?php
$connection = new DB();
$paid_products = $connection->select('paid_products')->leftJoin('shop_products', 'paid_products.product_id','=', 'shop_products.id')
                        ->where('paid_buyer_id', Auth::user('id'))->get();
if(!Auth::is_loggedin())
{
	$paid_products = [];
}
?>



<?php include('includes/header.php') ?>


<?php include('includes/dash-board-navigation.php'); ?>


<?php include('includes/account-mobile-navigation.php') ?>


<?php include('includes/side-bar.php'); ?>




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
								<h4 class="title float-left">Order</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="<?= url('/shop/index.php') ?>">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Order</li>
								</ol>
							</nav>
						</div>
						<div class="col-lg-12">
							<div class="my_course_content_container">
								<div class="my_setting_content">
									<div class="my_setting_content_header">
										<div class="my_sch_title">
											<h4 class="m0">My Orders</h4>
											<?php if(Session::has('success')):?>
												<div class="alert-success text-center p-3"><?= Session::flash('success')?></div>
											<?php endif; ?>
											<?php if(Session::has('error')):?>
												<div class="alert-danger text-center p-3"><?= Session::flash('error')?></div>
											<?php endif; ?>
										</div>
									</div>
                                    <!-- start -->
                                    <?php if(count($paid_products)): ?>
									<div class="my_setting_content_details pb0">
										<div class="cart_page_form style2">
											<form action="#">
												<table class="table table-responsive">
												  	<thead>
													    <tr class="carttable_row">
													    	<th class="cartm_title">Product</th>
													    	<th class="cartm_title">Price</th>
													    	<th class="cartm_title">Quantity</th>
													    	<th class="cartm_title">Total</th>
													    	<th class="cartm_title">Date</th>
															<th class="cartm_title"></th>
													    </tr>
												  	</thead>
                                        
												  	<tbody class="table_body">
														<?php 
														$total = 0;
														foreach($paid_products as $product):
															if($product->cancled_quantity != $product->quantity):
																$total += $product->total_price;
                                                        ?>
													    
                                                        <tr>
													    	<th scope="row">
													    		<ul class="cart_list">
													    			<li class="list-inline-item pr20"><a href="detail.php?pid=<?= $product->id ?>"><img src="<?= url(explode(',', $product->big_image)[0]) ?>" class="cart_img" alt="<?= $product->product_name ?>"></a></li>
													    			<li class="list-inline-item"><a class="cart_title" href="detail.php?pid=<?= $product->id ?>"><?= $product->product_name; ?></a></li>
													    		</ul>
													    	</th>
													    	<td><?= money($product->product_price); ?></td>
													    	<td><?= $product->quantity - $product->cancled_quantity; ?></td>
													    	<td class="cart_total"><?= money($product->price * ($product->quantity - $product->cancled_quantity)); ?></td>
													    	<td class="cart_total "><?= date('d M Y', strtotime($product->item_date_paid)) ?></td>
															<td>													       
																<div class="dropdown show">
																	<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
																	<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
																			<a href="<?= url('/shop/order-detail.php?pid='.$product->paid_product_id ) ?>" class="dropdown-item">View detail</a>	
																		<?php if(!$product->is_delivered && $product->cancled_quantity != $product->quantity): ?>																
																			<a href="#" data-toggle="modal" data-target="#cancle_order" data-reference="<?= $product->paid_reference ?>" id="<?= $product->paid_product_id  ?>" class="dropdown-item cancle_order_modal_btn text-danger">Cancle order</a>
																		<?php endif; ?>
																	</div>
																</div>
															</td>
														</tr>
														<?php endif; ?>
													    <?php endforeach; ?>
												  	</tbody>
												</table>
											</form>
										</div>
									</div>
									
                                   
									
                                    <!-- end -->
									<?php else: ?>
										<div class="empty-cart">
											<span class="flaticon-shopping-bag pr5 fz20 cart_icon"></span>
											<h4>You have no order</h4>
											<?php if(!Auth::is_loggedin()): ?>
												<p class="empty-cart-p">Have an account? <a href="<?= url('/shop/login.php') ?>" class="text-primary"> Login</a> or <a href="<?= url('/shop/register.php') ?>" class="text-primary"> Register</a> to view orders.</p>
											<?php endif; ?>
											<a href="<?= url('/shop/index.php') ?>" class="app-btn">Continue shopping</a>
										</div>
									<?php endif; ?>
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





<!-- Modal -->
<div class="sign_up_modal modal fade" id="cancle_order" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_cancle_order_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <h3 class="text-center">Cancle Order</h3>
                                <p class="text-center">Do you wish to cancle this order?</p>
                            </div>
                            <div class="form-group">
                                <div class="alert-change alert_0 text-danger"></div>
                                <input type="number" min="1" class="form-control" id="cancle_qty_input" placeholder="Quantity">
                            </div>
                            <div class="form-group">
                                 <div class="alert-change alert_1 text-danger"></div>
								 <input type="hidden" class="cancle_order_id_input" value="">
								 <input type="hidden" class="cancle_order_reference_input" value="">
                                 <textarea id="cancle_message_input" class="form-control"  cols="30" rows="10" placeholder="Reasons for cancelation"></textarea>
                            </div>
                            <button type="button" data-url="<?= url('/shop/ajax.php') ?>" id="cancle_product_order_btn" class="btn btn-log btn-block btn-thm2">Return order</button>
                        </form>
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
// ===================================
//  GET PRODUCT ID AND REFERENCE
// ===================================

$(".cancle_order_modal_btn").click(function(e){
    var paid_product_id = $(this).attr('id');
	var reference = $(this).attr('data-reference');
	$(".alert-change").html('');
	$("#cancle_qty_input").val('');
	$("#cancle_message_input").val('');
    
	$(".cancle_order_id_input").val(paid_product_id);
	$(".cancle_order_reference_input").val(reference);
});





// ================================================
//   CANCLE ORDER
// ================================================
$("#cancle_product_order_btn").click(function(e){
	e.preventDefault();
	var url = $(this).attr('data-url');
	$(".alert-change").html('');
	
	
	cancle_order(url);
});


function cancle_order(url){
	var quantity = $("#cancle_qty_input").val();
	var message = $("#cancle_message_input").val();
	var paid_product_id = $(".cancle_order_id_input").val();
	var reference = $(".cancle_order_reference_input").val();
	$(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
		method: 'post',
		data: {
            quantity: quantity,
			message: message,
			reference: reference,
			paid_product_id: paid_product_id,
			cancle_product: 'cancle_product'
		},
		success: function(response){
            var info = JSON.parse(response);
			if(info.error){
				show_error(info.error);
			}else if(info.data){
			    location.reload();	
			}
		}
	});
}


function show_error(error){
	$(".alert_0").html(error.quantity);
	$(".alert_1").html(error.message);
}







// ========================================
// REMOVE PRELOADER
// ========================================
function remove_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
        $(".alert-success").hide();
    }, 2000);
}



});
</script>