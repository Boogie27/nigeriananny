<?php include('../Connection.php');  ?>

<?php


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
			store_paid_products($reference);
			
			return view('/shop/success');
		}
	}
	
}




function store_paid_products($reference)
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
		
		Session::delete('cart');
    }
}


// app banner settings
$banner =  $connection->select('settings')->where('id', 1)->first();

?>
<?php include('includes/header.php') ?>


	<!-- Our Error Page -->
	<section class="">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 offset-sm-4 col-lg-6 offset-lg-5 text-center">
					<div class="logo-widget error_paged">
				        <a href="<?=  url('/shop/index.php') ?>" class="navbar_brand float-left">
				            <img class="logo1 img-fluid" src="<?= asset($banner->logo) ?>" alt="<?= $banner->app_name ?>">
				            <span style="color: #555;"><?= $banner->app_name ?></span>
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