<?php include('../Connection.php');  ?>


<?php
if(!Auth::is_loggedin())
{
	Session::put('old_url', current_url());
	return Redirect::to('login.php');
}

if(!Input::exists('get'))
{
    return Redirect::to('404.php');
}
?>

<?php 
$connection = new DB();
$trackHistory = $connection->select('shop_transactions')->where('transaction_id', Input::get('tid'))->where('buyer_id', Auth::user('id'))->first();
if(!$trackHistory)
{
     return Redirect::to('404.php');
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
								<h4 class="title float-left">Track orders</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="<?= url('/shop/index.php') ?>">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
								</ol>
							</nav>
						</div>
						<div class="col-lg-12">
							<div class="my_course_content_container">
								<div class="my_setting_content">
									<div class="my_setting_content_header">
										<div class="my_sch_title">
											<h4 class="m0">Track orders</h4>
										</div>
									</div>
                                    <!-- order cancle start -->
                                    <div class="r-container">
                                        <?php if($trackHistory->date_paid): ?>
									    <ul class="track-icon">
                                            <li>
                                                <li class="o-icon"><i class="fa fa-check"></i></li>
                                                <ul class="o-icon">
                                                    <li class="order-date">Order placed </li>
                                                    <li class="o-date"><?= date('d M Y', strtotime($trackHistory->date_paid)) ?> </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <?php endif ?>
                                         <ul class="track-icon">
                                            <li>
                                                <li class="o-icon"><i class="fa fa-check"></i></li>
                                                <ul class="o-icon">
                                                    <li class="order-date">Pending confirmation </li>
                                                    <li class="o-date"><?= date('d M Y', strtotime($trackHistory->date_paid)) ?> </li>
                                                </ul>
                                            </li>
                                        </ul>

                                        <ul class="track-icon">
                                            <li>
                                                <li class="o-icon"><i class="fa fa-check"></i></li>
                                                <ul class="o-icon">
                                                    <li class="order-date">Order shipped </li>
                                                    <li class="o-date"><?= date('d M Y', strtotime($trackHistory->date_paid)) ?> </li>
                                                </ul>
                                            </li>
                                        </ul>

                                         <ul class="track-icon success">
                                            <li>
                                                <li class="o-icon"><i class="fa fa-check"></i></li>
                                                <ul class="o-icon">
                                                    <li class="order-date">Order delivered </li>
                                                    <li class="o-date"><?= date('d M Y', strtotime($trackHistory->date_paid)) ?> </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    
                                    </div>
                                    <!-- order cancle end -->                                    
								</div>
                            </div>
                           
						</div>
					</div>
					<div class="row mt50 pb50">
						<div class="col-lg-12">
							<div class="copyright-widget text-center">
								<p class="color-black2">Copyright Edumy © 2019. All Rights Reserved.</p>
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
$(document).ready(function(){







});
</script>