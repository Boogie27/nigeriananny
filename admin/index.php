<?php include('../Connection.php');  ?>

<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', current_url());
  return view('/admin/login');
}


$products = $connection->select('shop_products')->get();

$reviews = $connection->select('product_review')->get();

$users = $connection->select('users')->get();

$customers = $connection->select('users')->orderBy('join_date', 'ASC')->limit(5)->get();

$messages = $connection->select('cancled_product')->leftJoin('users', 'cancled_product.cancled_user_id', '=', 'users.id')
                        ->orderBy('cancled_date', 'ASC')->limit(5)->get();




$subAmount = 0;
$cancleAmount = 0;

$amounts = $connection->select('paid_products')->get();
if($amounts)
{
    foreach($amounts as $amount)
    {
        $subAmount += $amount->total_price;
        $cancleAmount += $amount->price * $amount->cancled_quantity;
    }
}

$totalEarnings = $subAmount - $cancleAmount;





// app banner settings
$banner =  $connection->select('settings')->where('id', 1)->first();



?>



	<?php include('includes/header.php'); ?>

	<!-- Main Header Nav -->
    <?php include('includes/navigation.php') ?>

	<!-- Main Header Nav For Mobile -->
	<?php include('includes/mobile-navigation.php') ?>


	<!-- Our Dashbord Sidebar -->
	<?php include('includes/side-navigation.php') ?>
	

	<!-- Our Dashbord -->
	<div class="our-dashbord dashbord">
		<div class="dashboard_main_content">
			<div class="container-fluid">
				<div class="main_content_container">
					<div class="row">
						<div class="col-lg-12">
							<?php include('includes/mobile-drop-nav.php') ?><!-- mobile-navigation -->
						</div>
						<div class="col-lg-12">
							<?php if(Session::has('success')): ?>
								<div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
							<?php endif;?>
							<nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
								<h4 class="title float-left">Dashboard</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="#">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
								</ol>
							</nav>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one">
								<div class="icon"><span class="fa fa-cubes"></span></div>
								<div class="detais">
									<p>Products</p>
									<div class="timer"><?= $products ? count($products) : 0; ?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style2">
								<div class="icon"><span class="flaticon-rating"></span></div>
								<div class="detais">
									<p>Reviews</p>
									<div class="timer"><?= $reviews ? count($reviews) : 0; ?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style3">
								<div class="icon"><span class="fa fa-users"></span></div>
								<div class="detais">
									<p>Users</p>
									<div class="timer"><?= $users ? count($users) : 0; ?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one stle4">
								<div class="icon"><span class="fa fa-money"></span></div>
								<div class="detais">
									<p>Income</p>
									<h3 class="total-earnings"><?= money($totalEarnings) ?></h3>
								</div>
							</div>
						</div>
						<div class="col-xl-8">
							<div class="application_statics">
								<h4>New customers</h4>
								<div class="col-lg-12">
									<div class="ite-table table-responsive"> <!-- table start-->
										<table class="table table-striped">
											<thead>
												<tr>
												<th scope="col">Image</th>
												<th scope="col">Name</th>
												<th scope="col">Email</th>
												<th scope="col">Last login</th>
												<th scope="col">Date</th>
												</tr>
											</thead>
											<tbody class="item-table-t">
											<?php if($customers): 
											foreach($customers as $customer):    
											?>
												<tr>
													<td>
													<?php if($customer->user_image): ?>
													       <a href="<?= url('/admin/edit-customer.php?cid='.$customer->id) ?>"><img src="<?= asset($customer->user_image) ?>" alt="" class="table-img <?= $customer->is_active ? 'online' : 'offline' ?>"></a>
														<?php else: ?>
														    <a href="<?= url('/admin/edit-customer.php?cid='.$customer->id) ?>"><img src="<?= asset('/shop/images/users/demo.png') ?>" alt="" class="table-img <?= $customer->is_active ? 'online' : 'offline' ?>"></a>
														<?php endif; ?>
													</td>
													<td>
														<a href="<?= url('/admin/edit-customer.php?cid='.$customer->id) ?>">
															<?= ucfirst($customer->last_name).' '.ucfirst($customer->first_name)?>
														</a>
												    </td>
													<td><?= $customer->email ?></td>
													<td><?= date('d M Y', strtotime($customer->last_login)) ?></td>
													<td><?= date('d M Y', strtotime($customer->join_date)) ?></td>
												</tr>
											<?php endforeach; ?>
									
											<?php endif; ?>
											</tbody>
										</table>
										<div class="col-lg-12">
											<?php if(!$customers): ?>
												<div class="empty-table">There are no customers yet!</div>
											<?php endif; ?>
										</div>
									</div><!-- table end-->
								</div>
							</div>
						</div>
						<div class="col-xl-4">
							<div class="recent_job_activity">
								<h4 class="title">Order Canclation Messages</h4>
								<?php if($messages): 
								    foreach($messages as $message):?>
										<div class="grid">
											<ul class="grid_msg">
												<img src="<?= asset($message->user_image) ?>" alt="<?= $message->first_name ?>" class="msg_img">
												<div class="msg_msg">
													<li>
														<div class="title">
															<a href="<?= url('/admin/cancled-order-detail.php?cod='.$message->cancled_id) ?>"><?= ucfirst($message->first_name)?></a>
															<span><?= date('d M Y', strtotime($message->cancled_date)) ?></span>
														</div>
												    </li>
													<li><p><?= $message->message?></p></li>
												</div>
											</ul>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
								<div class="alert text-center p-3">There are no messages yet!</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="row mt50 mb50">
						<div class="col-lg-6 offset-lg-3">
							<div class="copyright-widget text-center">
								<p class="color-black2"><?= $banner->alrights ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>


<?php  include('includes/footer.php') ?>