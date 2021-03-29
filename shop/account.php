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
$orders = $connection->select('paid_products')->where('paid_buyer_id', Auth::user('id'))->get();
$reviews = $connection->select('product_review')->where('p_user_id', Auth::user('id'))->get();
$user = $connection->select('users')->where('id', Auth::user('id'))->first();


?>





<!-- Our Dashbord -->
<div class="our-dashbord dashbord">
		<div class="dashboard_main_content">
			<div class="container-fluid">
				<div class="main_content_container">
					<div class="row">
						<div class="col-lg-12">
								<!-- mobile side bar -->
									<?php include('includes/mobile-side-bar.php'); ?>
								<!-- mobile side bar end -->
						</div>
						<div class="col-lg-12">
							<div class="page_alert_success alert-success text-center p-3 mb-2" style="display: none;"></div>
						</div>
						<div class="col-lg-12">
							<nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
								<h4 class="title float-left">Account</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="#">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Account</li>
								</ol>
							</nav>
						</div>
					
						<div class="col-xl-12">
							<div class="recent_job_activity">
								<h4 class="title">Account over view</h4>

								 <div class="account-body">
									<div class="row">
										<div class="col-lg-6">
											<div class="detail-x">
											    <div class="ac-head">ACCOUNT DETAILS <a href="<?= url('/shop/user-detail.php') ?>"><i class="fa fa-pencil text-success float-right"></i></a></div>
											    <ul>
													<li class="user_name"><b>Name:</b> <?= $user->first_name ? ucfirst($user->first_name.' '.$user->last_name) : '';?></li>
													<li class="user_email"><b>Email:</b> <?= $user->email ?></li>
													<li class="user_phone"><b>Phone:</b> <?= $user->phone ? $user->phone : '' ;?></li>
													<li class="user_gender"><b>Gender:</b> <?= $user->gender ? $user->gender : ''?></li>
												</ul>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="detail-x">
											    <div class="ac-head">ADDRESS BOOK <a href="<?= url('/shop/address-book.php') ?>"><i class="fa fa-pencil text-success float-right"></i></a></div>
											    <ul>
													<li class=""><b>City:</b> <?= $user->city ? $user->city : '' ;?> </li>
													<li class="user_email"><b>State:</b> <?= $user->state ? $user->state : '' ;?> </li>
													<li class="user_phone"><b>Country:</b> <?= $user->country ? $user->country : '' ;?> </li>
													<li class="user_gender"><b>Address:</b> <?= $user->address ? $user->address : '' ;?> </li>
												</ul>
											</div>
										</div>

										<div class="col-lg-6">
											<div class="detail-x">
											    <div class="ac-head">Reviews <a href="<?= url('/shop/reviewed.php') ?>"><i class="fa fa-eye text-success float-right"></i></a></div>
											    <ul>
													<li class="user_name">Number of reviews</li>
													<li class="user_email">You have reviewed: <i class="badge bg-warning"><?= count($reviews)?></i></li>
												</ul>
											</div>
										</div>

										<div class="col-lg-6">
											<div class="detail-x">
											    <div class="ac-head">Orders <a href="<?= url('/shop/order.php') ?>"><i class="fa fa-eye text-success float-right"></i></a></div>
											    <ul>
													<li class="user_order">
														<a href="<?= url('/shop/order.php') ?>"><img src="<?= asset('/shop/images/cart.jpg') ?>" alt="order"></a>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt50 mb50">
						<div class="col-lg-6 offset-lg-3">
							<div class="copyright-widget text-center">
								<p class="color-black2"></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>







<!-- footer -->
<div style="position: relative; z-index: 1000;">
	<?php include('includes/footer.php') ?>
</div>