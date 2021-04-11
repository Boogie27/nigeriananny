<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny');
  return view('/admin/login');
}




// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$employees = $connection->select('employee')->get();

// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$flagged_employees = $connection->select('employee')->where('is_flagged', 1)->get();


// ===========================================
// GET EMPLOYERS COUNT
// ===========================================
$employers = $connection->select('employers')->get();


// ===========================================
// GET ALL EMPLOYERS 
// ===========================================
$my_employers =  $connection->select('employers')->orderBy('id', 'DESC')->get();

// ===========================================
// GET EMPLOYERS COUNT
// ===========================================
$deactivated_employers =  $connection->select('employers')->where('e_deactivate', 1)->get();

// ===========================================
// GET ALL SUBSCRITPION AMOUNT MADE
// ===========================================
$total_amount = 0;
$employer_subscriptions = $connection->select('employer_subscriptions')->get();
if(count($employer_subscriptions))
{
	foreach($employer_subscriptions as $subscriptions)
	{
		$total_amount += $subscriptions->s_amount;
	}
}





// ===========================================
// GET EMPLOYERS SUBSCRIPTIONS
// ===========================================
$e_subscriptions = $connection->select('employer_subscriptions')->leftJoin('employers', 'employer_subscriptions.s_employer_id', '=', 'employers.id')->where('is_expire', 0)->orderBy('subscription_id ', 'DESC')->limit(5)->get();



// ===========================================
// GET ALL ONLINE USERS
// ===========================================
$active_employers = $connection->select('employers')->where('e_active', 1)->get();
$active_employee = $connection->select('employee')->where('is_active', 1)->get();
$total_active = count($active_employee) + count($active_employers);





// ===========================================
// GET SUBSCRIPTION NOTIFICATION
// ===========================================
$employer_subs = $connection->select('employer_subscriptions')->where('is_expire', 0)->limit(5)->get();



// ===========================================
// GET TODAY AMOUNT
// ===========================================
$today_amount = 0;
$today = date('Y-m-d');
$today_subs = $connection->select('employer_subscriptions')->where('start_date', $today)->where('is_expire', 0)->get();
if(count($today_subs))
{
	foreach($today_subs as $today_sub)
	{
		$today_amount += $today_sub->s_amount;
	}	
}

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
								<div class="icon"><span class="fa fa-briefcase"></span></div>
								<div class="detais">
									<p>Employees</p>
									<div class="timer"><?= count($employees)?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style2">
								<div class="icon"><span class="fa fa-users"></span></div>
								<div class="detais">
									<p>Employers</p>
									<div class="timer"><?= count($employers)?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style3">
								<div class="icon"><span class="flaticon-online-learning"></span></div>
								<div class="detais">
									<p>Active users</p>
									<div class="timer"><?= $total_active ?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style4">
								<div class="icon"><span class="fa fa-money"></span></div>
								<div class="detais">
									<p>Income</p>
									<div class="dash-amount"><h3><?= money($total_amount) ?></h3></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one"  title="Flagged employees">
								<div class="icon bg-danger"><span class="fa fa-flag"></span></div>
								<div class="detais">
									<p>Employee</p>
									<div class="timer"><?= count($flagged_employees)?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style2" title="Deactivated employers">
								<div class="icon"><span class="fa fa-power-off"></span></div>
								<div class="detais">
									<p>Employer</p>
									<div class="timer"><?= count($deactivated_employers)?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style3">
								<div class="icon"><span class="fa fa-money"></span></div>
								<div class="detais">
									<p>Today</p>
									<div class="dash-amount"><h3><?= money($today_amount) ?></h3></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style4" id="dashboard_calculator" data-toggle="modal"  data-target="#exampleModal_calculator_btn"  title="Calculate income">
								<div class="icon"><span class="fa fa-circle"></span></div>
								<div class="detais">
									<p>Calculator</p>
									<div class="dash-amount"><h3></h3></div>
								</div>
							</div>
						</div>
						<div class="col-xl-8">
							<div class="application_statics">
								<h4>Employers <span class="float-right"><a href="<?= url('/admin-nanny/employers') ?>" class="text-primary" style="font-size: 16px;">view more</a></span></h4>
							    <div class="col-lg-12">
									<div class="table-responsive"> <!-- table start-->
										<table class="table table-striped">
											<thead>
												<tr>
												<th scope="col">Image</th>
												<th scope="col">Employer name</th>
												<th scope="col">Email</th>
												<th scope="col">Date registered</th>
												</tr>
											</thead>
											<tbody class="item-table-t">
											<?php if($my_employers): 
											foreach($my_employers as $employer):    
											?>
												<tr>
													<td>
													<?php if($employer->e_image): ?>
														<img src="<?= asset($employer->e_image) ?>" alt="" class="table-img <?= $employer->e_active ? 'online' : 'offline' ?>">
														<?php else: ?>
														<img src="<?= asset('/employer/images/employer/demo.png') ?>" alt="" class="table-img <?= $employer->e_active ? 'online' : 'offline' ?>">
														<?php endif; ?>
													</td>
													<td><?= ucfirst($employer->last_name).' '.ucfirst($employer->first_name)?></td>
													<td><?= $employer->email ?></td>
													<td><?= date('d M Y', strtotime($employer->e_date_joined)) ?></td>
												</tr>
											<?php endforeach; ?>
									
											<?php endif; ?>
											</tbody>
										</table>
										<div class="col-lg-12">
											<?php if(!$my_employers): ?>
												<div class="text-center">There are no employers yet!</div>
											<?php endif; ?>
										</div>
									</div><!-- table end-->
								</div>
							</div>
						</div>
						<div class="col-xl-4">
							<div class="recent_job_activity">
								<h4 class="title">Subscriptions <span class="float-right"><a href="<?= url('/admin-nanny/subscriptions') ?>" class="text-primary" style="font-size: 16px;">view more</a></span></h4>
								<?php if($e_subscriptions):
									foreach($e_subscriptions as $sub):
									?>
									<div class="grid">
										<ul>
											<li><div class="title"><?= ucfirst($sub->first_name.' '.$sub->last_name)?></div></li>
											<li><p>Type: <?= $sub->s_type ?></p></li>
											<li><p>Duration: <?= $sub->s_duration ?></p></li>
											<li><p class="text-success">End date: <?= date('d M Y', strtotime($sub->end_date)) ?></p></li>
										</ul>
									</div>
									<?php endforeach; ?>
								<?php else: ?>
                                 <div class="alert alert-warning text-center">There are no subscriptions yet</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="row mt50 mb50">
						<div class="col-lg-6 offset-lg-3">
							<div class="copyright-widget text-center">
								<p class="color-black2">Copyright Edumy © 2019. All Rights Reserved.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>








<!-- Modal Calculator-->
<div class="sign_up_modal modal fade" id="exampleModal_calculator_btn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_delete_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
				        <div class="alert alert-danger text-center p-3 mb-2 none income-calculator-error">error</div>
						<div class="calculator-screen text-right">
						    <div class="inner-cal-content">
								<h2>₦0,00</h2>
							</div>
						</div>
                        <form action="#">
                             <div class="row">
								<div class="col-lg-12"><label for=""><b>From:</b></label></div>
								<div class="col-lg-4">
									<div class="form-group">
										<input type="number" min="1" max="31" id="from_daily_income_day" class="form-control h50" value="" placeholder="Day">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<input type="number" min="1" max="12" id="from_daily_income_month" class="form-control h50" value="" placeholder="Month">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<input type="number" min="2021" max="<?= date('Y') ?>" id="from_daily_income_year" class="form-control h50" value="" placeholder="Year" required>
									</div>
								</div>

								<div class="col-lg-12"><label for=""><b>To:</b></label></div>
								<div class="col-lg-4">
									<div class="form-group">
										<input type="number" min="1" max="31" id="to_daily_income_day" class="form-control h50" value="" placeholder="Day">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<input type="number" min="1" max="12" id="to_daily_income_month" class="form-control h50" value="" placeholder="Month">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<input type="number" min="2021" max="<?= date('Y') ?>" id="to_daily_income_year" class="form-control h50" value="" placeholder="Year" required>
									</div>
								</div>
							 </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_income_calculator_btn" class="btn bg-primary btn-log btn-block" style="color: #fff;">
							    <span id="calculate_btn_font">Calculate</span>
								<span id="calculate_preloader">Please wait...</span>
							</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>












<!-- footer -->
<?php  include('includes/footer.php') ?>










<script>
$(document).ready(function(){
// ===========================================
// INCOME CALCULATOR
// ===========================================
$("#submit_income_calculator_btn").click(function(e){
    e.preventDefault();
	var url = $(this).attr('data-url');
    $(".income-calculator-error").hide();
    var from_day = $("#from_daily_income_day").val();
	var from_month = $("#from_daily_income_month").val();
	var from_year = $("#from_daily_income_year").val();
	var to_day = $("#to_daily_income_day").val();
	var to_month = $("#to_daily_income_month").val();
	var to_year = $("#to_daily_income_year").val();
   
   
    var fields = get_date_fields(from_month, from_year, to_month, to_year);
	if(fields){
		return;
	}

    $.ajax({
		url: url,
		method: 'post',
		data: {
			from_day: from_day,
			from_month: from_month,
			from_year: from_year,
			to_day: to_day,
			to_month: to_month,
			to_year: to_year,
			calculate_subscription_income: 'calculate_subscription_income'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.error){
				get_error(data.error)
            }else if(data.amount){
				$("#calculate_preloader").hide();
				$("#calculate_btn_font").show();
				$(".inner-cal-content h2").html('₦'+data.amount);
			}else{
				$(".income-calculator-error").show();
				$(".income-calculator-error").html('*network error, try again later!');
			}
			console.log(response)
		}
	});
	
});




function get_error(error){
	$("#calculate_preloader").hide();
	$("#calculate_btn_font").show();

	$(".income-calculator-error").show();
	$(".income-calculator-error").html(error.from_year);
	$(".income-calculator-error").html(error.from_month);
	$(".income-calculator-error").html(error.to_year);
	$(".income-calculator-error").html(error.to_month);
}




function get_date_fields(from_month, from_year, to_month, to_year){
	var error = false;
	if(from_month == ''){
		error =
		$(".income-calculator-error").show();
		$(".income-calculator-error").html('*From month is required');
	}else if(from_year == ''){
		error = true;
		$(".income-calculator-error").show();
		$(".income-calculator-error").html('*From year is required');
	}else if(to_month == ''){
		error = true;
		$(".income-calculator-error").show();
		$(".income-calculator-error").html('*To month is required');
	}else if(to_year == ''){
		error = true;
		$(".income-calculator-error").show();
		$(".income-calculator-error").html('*To year is required');
	}else if(from_month > to_month){
		error = true;
		$(".income-calculator-error").show();
		$(".income-calculator-error").html('*To month must be greater than from month');
	}else{
	   $("#calculate_preloader").show();
	   $("#calculate_btn_font").hide();
   }

   if(error){
	   return true;
   }
   return false;
}


// end
});
</script>