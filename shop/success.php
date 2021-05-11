<?php include('../Connection.php');  ?>

<?php
// ********** app banner settings **********//
$app =  $connection->select('settings')->where('id', 1)->first();


if(!Session::has('buyer_details'))
{
	return view('/shop');
}

if(Session::has('buyer_details') && !Input::get('reference'))
{
	Session::delete('buyer_details');
}


if(Input::exists('get') && Input::get('reference'))
{
    if(Session::has('buyer_details'))
    {
	   $buyer_detail = Session::get('buyer_details');

	   $connection = new DB();
	   $shipping_fee = isset($buyer_detail['shipping']) ? $buyer_detail['shipping'] : 0;

	   $callBack = new Paystack();
	   $reference = $callBack->call_back();
	
	   
	   $create = $connection->create('shop_transactions', [
				'buyer_id' => Auth::user('id'),
				'reference' => $reference,
				'first_name' => $buyer_detail['first_name'],
				'last_name' => $buyer_detail['last_name'],
				'email' => $buyer_detail['email'],
				'phone' => $buyer_detail['phone'],
				'address' => $buyer_detail['address'],
				'city' => $buyer_detail['city'],
				'state' =>  $buyer_detail['state'],
				'country' => $buyer_detail['country'],
				'postal_code' => $buyer_detail['zip_code'],
				'message' => $buyer_detail['message'],
				'amount' => $buyer_detail['total'],
				'shipping_fee' => $shipping_fee
		]);
		if($create)
		{
			store_paid_products($app, $reference, $buyer_detail['email']);
			
			return view('/shop/success');
		}
	}
	
}




function store_paid_products($app, $reference, $email)
{
    if(Session::has('cart'))
    {
        $cart = Session::get('cart');
        $items = $cart->_items;
        $connection = new DB();
        foreach($items as $item)
        {
            $create = $connection->create('paid_products', [
                'paid_buyer_id' => Auth::user('id'),
                'product_id' => $item['id'],
                'paid_reference' => $reference,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['total'],
            ]);
        }

        foreach($items as $item)
        {
            $product  = $connection->select('shop_products')->where('id', $item['id'])->first();
            $productQty = $product->product_quantity;
            $soldQty = $product->product_quantity_sold;

            $update = $connection->update('shop_products', [
                'product_quantity' => $productQty -= $item['quantity'],
                'product_quantity_sold' => $soldQty += $item['quantity']
            ])->where('id', $item['id'])->save();
		}
		

		send_mail_to_buyer($app, $reference, $email); //send mail to buyer
		Session::delete('cart');
    }
}







// ******** SEND MAIL TO BUYER *************//
function send_mail_to_buyer($app, $reference, $email)
{
	$body = buyer_email($app, $reference);

	$mail = new Mail();
    $send = $mail->mail([
				'to' => $email,
				'subject' => 'Transaction success',
				'body' => $body,
			]);
	
	if($send->passed())
	{
		$send->send_email();
	}
}



//******* BUYER MAIL ********** */
function buyer_email($app, $reference){
	$mail = '';
	$mail .= '
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta http-equiv="X-UA-Compatible" content="ie=edge">
				<style>
					body{
						font-family: "poppins", sans-serif;
						color: #555;
					}
					body a{
						color: #555; 
						text-decoration: none;
					}
					.container{
						width: 60%;
						margin: 0 auto;
						padding: 50px 0px;
					}
					.msg-header{
						width: 100%;
						margin-bottom: 50px;
						text-align: center;
					}
					.msg-header img{
						width: 50px;
						height: 50px;
						border-radius: 3px;
					}
					.msg-header h3{
						margin: 0px;
						font-size: 25px;
						letter-spacing: 2px;
					}
					.mgs-body p{
					text-align: center;
					}
					/* ********* FOOTER *********** */
					.bottom-footer{
						width: 100%;
						margin-top: 150px;
						padding: 60px 0px 10px 0px;
						background-color: rgb(246, 246, 246);
					}
					.bottom-footer .rights{
						font-size: 13px;
						text-align: center;
			
					}
					ul.ul-footer{
						padding-left: 0px;
						text-align: center;
					}
					ul.ul-footer li{
						margin: 0px 5px;
						font-size: 12px;
						display: inline-block;
						padding: 1px 10px;
						border-radius: 2px;
						margin-bottom: 5px;
						border: 1px solid #ccc;
					}
					.bottom-footer .rights{
						font-size: 10px;
			
					}
					.text-center{
						text-align: center;
					}
					p.info{
						color: #555;
						font-size: 12px;
					}
					@media only screen and (max-width: 992px){
						.container{
							width: 80%;
						}
					}
					@media only screen and (max-width: 767px){
						.container{
							width: 90%;
						}
					}
					@media only screen and (max-width: 567px){
						ul.ul-footer li{
							font-size: 9px;
							padding: 5px;
						}
						.bottom-footer .rights{
							font-size: 9px;
						}
						.container{
							width: 100%;
						}
						.msg-header img{
							width: 40px;
							height: 40px;
						}
						.mgs-body p{
							font-size: 12px;
						}
					}
					
				</style>
			</head>
			<body>
				<div class="container">
					<div class="msg-header">
						<img src='.asset($app->logo).' alt='.$app->app_name.'>
						<h3>'.$app->app_name.'</h3>
					</div>
					<div class="mgs-body">
						<p>
							Thank you for shopping with nigeria nanny. <br>
							We have recieved Your order and it would be attended to shortly. <br>This is your 
							reference ID: <b>'.$reference.'</b>
						</p>
					</div>
					
					<div class="bottom-footer">
						<div class="text-center">
						<p class="info">Contact: '.$app->phone.'</p>
						<p class="info">Customer care: '.$app->info_email.'</p>
						</div>
						<ul class="ul-footer">
							<li><a href='.url("/") .'>Find a worker</a></li>
							<li><a href='.url("/privacy") .'>Privacy Policy</a></li>
							<li><a href='.url("/terms") .'>Terms & Conditions</a></li>
							<li><a href='.url("/about") .'>About us</a></li>
							<li><a href='.url("/shop") .'>Market place</a></li>
							<li><a href='.url("/courses") .'>Download courses</a></li>
							<li><a href='.url("/contact") .'>Contact</a></li>
						</ul>
						<div class="rights">'.$app->alrights.'</div>
					</div>
				</div>
			</body>
			</html>
			';

    return $mail;
}



?>
<?php include('includes/header.php') ?>


	<!-- Our Error Page -->
	<section class="">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 offset-sm-4 col-lg-6 offset-lg-5 text-center">
					<div class="logo-widget error_paged">
				        <a href="<?=  url('/shop/index.php') ?>" class="navbar_brand float-left">
				            <img class="logo1 img-fluid" src="<?= asset($app->logo) ?>" alt="<?= $app->app_name ?>" style="width: 50px; height: 50px;">
				            <span style="color: #555;"><?= $app->app_name ?></span>
				        </a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-10 offset-lg-1 text-center">
					<div class="error_page footer_apps_widget">
						<h4 class="text-success">Success</h4>
						<p style="color: #555;">Transaction successful, thank you for shopping with us!</p>
					</div>
					<a class="mt25 fill-pink" href="<?= url('/shop') ?>">Back to Homepage <span class="flaticon-right-arrow-1"></span></a>
				</div>
			</div>
		</div>
	</section>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>
<!-- Wrapper End -->
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery-migrate-3.0.0.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/popper.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery.mmenu.all.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/ace-responsive-menu.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/snackbar.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/simplebar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/parallax.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/scrollto.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery-scrolltofixed-min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/jquery.counterup.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/wow.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/progressbar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/slider.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/timepicker.js"></script>
<!-- Custom script for all pages --> 
<script type="text/javascript" src="<?= SITE_URL ?>/shop/js/script.js"></script>
</body>

<!-- Mirrored from grandetest.com/theme/edumy-html/page-error.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:06:58 GMT -->
</html>