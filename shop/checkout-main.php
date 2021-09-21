<?php include('../Connection.php');  ?>



<?php
if(!Auth::is_loggedin())
{
	return Redirect::to('login.php');
}

if(!Session::has('cart'))
{
	return Redirect::to('index.php');
}



if(Input::post('pay'))
{
	$url = url('/shop/success.php');
	$email = Session::get('buyer_details')['email'];
	$shipping_fee = isset(Session::get('buyer_details')['shipping']) ? Session::get('buyer_details')['shipping'] : 0;
	$total = Session::get('buyer_details')['total'] + $shipping_fee;

	$paystack = new Paystack();
	$paystack->initialize($email, $total, $url);
}

?>

<?php
$setting =  $connection->select('settings')->where('id', 1)->first();   //    banner 
?>

<?php include('includes/header.php') ?>

	
<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php include('includes/search-bar.php') ?>




	<!-- Inner Page Breadcrumb -->
	<section class="inner_page_breadcrumb" style="background-image: url('<?= asset($setting->checkout_banner)?>');">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 offset-xl-3 text-center" >
					<div class="breadcrumb_content">
						<h4 class="page_title">Checkout</h4>
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="<?= url('/shop') ?>">Home</a></li>
						    <li class="breadcrumb-item active" aria-current="page">checkout</li>
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




<!-- Shop Checkouts Content -->
<section class="shop-checkouts">
   
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-8 col-xl-8">
					<div class="checkout_form">
						
						<div class="checkout_coupon ui_kit_button">
						    <h4 class="mb15">Billing Details</h4>
							<form action="<?= current_url() ?>" method="post" class="form2" id="coupon_form" name="contact_form" novalidate="novalidate">
								<div class="row">
					                <div class="col-sm-6">
					                    <div class="form-group">
											<div class="form-alert form_alert_0 text-danger"></div>
					                    	<label for="exampleInputName">First name *</label>
											<input id="first_name_input" class="form-control" required="required" type="text">
										</div>
					                </div>
					                <div class="col-sm-6">
					                    <div class="form-group">
										    <div class="form-alert  form_alert_1 text-danger"></div>
					                    	<label for="exampleInputName2">Last name *</label>
											<input id="last_name_input"  class="form-control" required="required" type="text">
										</div>
					                </div>
                                    <div class="col-sm-6">
					                    <div class="form-group">
										    <div class="form-alert  form_alert_2 text-danger"></div>
					                    	<label for="exampleInputEmail">Your Email</label>
					                    	<input id="email_input" class="form-control required email" required="required" type="email">
					                    </div>
					                </div>
                                    <div class="col-sm-6">
					                    <div class="form-group">
										    <div class="form-alert  form_alert_3 text-danger"></div>
					                    	<label for="exampleInputPhone">Phone *</label>
											<input id="phone_input"  class="form-control" required="required" type="number">
										</div>
					                </div>
					               
					                <div class="col-sm-12">
					                    <div class="form-group">
										    <div class="form-alert  form_alert_4 text-danger"></div>
					                    	<label for="exampleInputName3">Street address *</label>
											<input id="address_input"  class="form-control mb10" placeholder="House number and street name" required="required" type="text">
										</div>
					                </div>
                                    <div class="col-lg-6" >
									    <div class="form-group">
										    <div class="form-alert  form_alert_5 text-danger"></div>
					                    	<label for="exampleInputName3">City *</label>
											<input id="city_input"  class="form-control mb10" placeholder="City" required="required" type="text">
										</div>
									</div>
                                    <div class="col-lg-6" >
									<?php $states = $connection->select('states')->where('is_active', 1)->get(); ?>
										<div class="my_profile_select_box form-group">
									    	<label for="exampleFormControlInput9">State *</label><br>
									    	<select class="selectpicker" id="select_state_container">
											    <?php foreach($states as $state): ?>
												<option value="<?= $state->state ?>" <?= $state->state == 'LAGOS' ? 'selected' : ''; ?>><?= $state->state ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
                                   
					                <div class="col-sm-6">
					                    <div class="form-group">
										    <div class="form-alert  form_alert_6 text-danger"></div>
					                    	<label for="exampleInputName4">Postcode / ZIP *</label>
											<input id="zip_code_input"  class="form-control" required="required" type="number">
										</div>
					                </div>
					              
					                <div class="col-sm-12">
			                            <div class="form-group mb0">
										    <div class="form-alert  form_alert_7 text-danger"></div>
			                            	<label class="ai_title" for="exampleInputTextArea">Additional Information</label>
			                            	<p>Order notes (optional)</p>
			                                <textarea id="message_input"  class="form-control required" rows="7" placeholder="Notes about your order, e.g. special notes for delivery." required="required"></textarea>
			                                 <input type="hidden" id="checkout_total_input" value="<?= Session::get('cart')->_totalPrice ?>" class="total_input">
									    </div>
					                </div>
				                </div>
								<input type="hidden" id="shipping_fee_input" value="<?= Session::get('cart')->_totalShipping ?>">
								<input type="hidden" id="select_country_btn" value="NIGERIA">
								<button type="submit" name="pay" id="pay_btn" style="display: none;">paynow</button>
							</form>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-xl-4">
					<div class="order_sidebar_widget mb30">
						<h4 class="title">Your Order</h4>
						<ul>
						<li class="subtitle"><p>Product <span class="float-right">Total</span></p></li>
						<?php if(Session::has('cart')): 
							foreach(Session::get('cart')->_items as $cart_item):
							$product = $cart_item['product'];
							?>
							<li><p><?= $product->product_name ?> × <?= $cart_item['quantity']; ?> <span class="float-right"><?= money($cart_item['total']) ?></span></p></li>
							<?php endforeach; ?>
							<li class="subtitle"><p>Subtotal <span class="float-right"><?= money(Session::get('cart')->_totalPrice)?></span></p></li>
							<li class="subtitle"><p>Shipping fee <span class="float-right text-success" id="shipping_fee_container" style="padding: 0px 15px;"><?= money(Session::get('cart')->_totalShipping); ?></span></p></li>
							<li class="subtitle"><p>Total <span class="float-right totals color-orose checkout_total_container" id=""><?= money(Session::get('cart')->_totalPrice + Session::get('cart')->_totalShipping)?></span></p></li>
					    <?php  else: ?>
					     	<div class="alert-danger alert text-center">There are no items in cart!</div>
						<?php endif; ?>
						</ul>
					</div>
					
					<div class="ui_kit_button payment_widget_btn">
						<a href="<?= url('/shop/ajax.php') ?>"  id="checkout_payment_btn" class="btn dbxshad btn-lg btn-thm2 circle btn-block">Place Order</a>
					</div>
				</div> 
			</div>
		</div>
		
	</section>
    <!-- checkout end -->

<a href="<?= url('/shop/ajax.php') ?>" id="ajax_url" style="display: none;"></a>
<a href="<?= url('/shop/logout.php') ?>" id="ajax_logout_url" style="display: none;"></a>
    
<!-- footer -->
<?php include('includes/footer.php') ?>












<script>

$(document).ready(function(){






// =================================================
// PAY NOW
// =================================================
$("#checkout_payment_btn").on('click', function(e){
	e.preventDefault();
	$(".form-alert").html('');
	var url = $(this).attr('href');
	var logout_url = $("#ajax_logout_url").attr('href');

    var shipping = $("#shipping_fee_input").val();
	var total = $("#checkout_total_input").val();
	var first_name = $("#first_name_input").val();
	var last_name = $("#last_name_input").val();
	var email = $("#email_input").val();
	var phone = $("#phone_input").val();
	var address = $("#address_input").val();
	var city = $("#city_input").val();
	var state = $("#select_state_container").val();
	var country = $("#select_country_btn").val();
	var zip_code = $("#zip_code_input").val();
	var message = $("#message_input").val();



	$.ajax({
		url: url,
		method: 'post',
		data: {
			total: total,
			shipping: shipping,
			first_name: first_name,
			last_name: last_name,
			email: email,
			phone: phone,
			address: address,
			city: city,
			state: state,
			country: country,
			zip_code: zip_code,
			message:  message,
			pay_now: 'pay_now'
		},
		success: function(response){
			var data = JSON.parse(response);
			if(data.error){
				error_alert(data.error);
			}else if(data.data){
				$("#pay_btn").click();
			}else{
				location.assign(logout_url);
			}
		}
	});
});




function error_alert(error)
{
    $(".form_alert_0").html(error.first_name);
	$(".form_alert_1").html(error.last_name);
	$(".form_alert_2").html(error.email);
	$(".form_alert_3").html(error.phone);
	$(".form_alert_4").html(error.address);
	$(".form_alert_5").html(error.city);
	$(".form_alert_6").html(error.zip_code);
	$(".form_alert_7").html(error.message);
}















// end 
});
</script>