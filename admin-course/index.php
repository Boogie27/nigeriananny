<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-course');
  return view('/admin/login');
}





// *************** GET ALL COURSE USERS *************//
$userCount = $connection->select('course_users')->get();


//************ GET ALL EMPLOYEES *************/
$flagged_employees = $connection->select('employee')->where('is_flagged', 1)->get();



// ******** GET EMPLOYERS COUNT *********//
$employers = $connection->select('employers')->get();



// *********** GET ALL EMPLOYERS **********//
$users =  $connection->select('course_users')->orderBy('id', 'DESC')->get();

// ===========================================
// GET EMPLOYERS COUNT
// ===========================================
$deactivated_users =  $connection->select('course_users')->where('is_deactivate', 1)->get();



// *********** ACTIVE USERS ********* //
$usersActive =  $connection->select('course_users')->where('is_active', 1)->get();


// *********** ALL COURSES ********* //
$courseCount = $connection->select('courses')->get();


// ********** GET HIGHEST RATED COURSE **********//
$courses =  $connection->select('courses')->orderBy('ratings', 'DESC')->limit(5)->get();

// ********** app banner settings ***********//
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
							<div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
						<?php endif; ?>
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
								<div class="icon"><span class="fa fa-users"></span></div>
								<div class="detais">
									<p>Users</p>
									<div class="timer"><?= count($userCount)?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style2">
								<div class="icon"><span class="fa fa-power-off"></span></div>
								<div class="detais">
									<p>Deative users</p>
									<div class="timer"><?= count($deactivated_users)?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style3">
								<div class="icon"><span class="flaticon-online-learning"></span></div>
								<div class="detais">
									<p>Active users</p>
									<div class="timer"><?= count($usersActive) ?></div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
							<div class="ff_one style3">
								<div class="icon bg-warning"><span class="fa fa-video-camera"></span></div>
								<div class="detais">
									<p>Courses</p>
									<div class="timer"><?= count($courseCount) ?></div>
								</div>
							</div>
						</div>
						<div class="col-xl-8">
							<div class="application_statics">
								<h4>Course users <span class="float-right"><a href="<?= url('/admin-course/users') ?>" class="text-primary" style="font-size: 16px;">view more</a></span></h4>
							    <div class="col-lg-12">
									<div class="table-responsive"> <!-- table start-->
										<table class="table table-striped">
											<thead>
												<tr>
												<th scope="col">Image</th>
												<th scope="col">Full name</th>
												<th scope="col">Email</th>
												<th scope="col">Date</th>
												</tr>
											</thead>
											<tbody class="item-table-t">
											<?php if($users): 
											foreach($users as $user):    
											?>
												<tr>
													<td>
													<?php if($user->image): ?>
														<img src="<?= asset($user->image) ?>" alt="" class="table-img <?= $user->is_active ? 'online' : 'offline' ?>">
														<?php else: ?>
														<img src="<?= asset('/courses/images/user/demo.png') ?>" alt="" class="table-img <?= $user->is_active ? 'online' : 'offline' ?>">
														<?php endif; ?>
													</td>
													<td><?= ucfirst($user->last_name).' '.ucfirst($user->first_name)?></td>
													<td><?= $user->email ?></td>
													<td><?= date('d M Y', strtotime($user->date)) ?></td>
												</tr>
											<?php endforeach; ?>
											<?php endif; ?>
											</tbody>
										</table>
										<div class="col-lg-12">
											<?php if(!$users): ?>
												<div class="text-center">There are no users yet!</div>
											<?php endif; ?>
										</div>
									</div><!-- table end-->
								</div>
							</div>
						</div>
						<div class="col-xl-4">
							<div class="recent_job_activity">
								<h4 class="title">Highest rated Courses <span class="float-right"><a href="<?= url('/admin-course/courses') ?>" class="text-primary" style="font-size: 16px;">view more</a></span></h4>
								<?php if($courses):
									foreach($courses as $course):
									?>
									<div class="grid">
										<ul class="course-img-dashboard">
											<li class="course-img"><img src="<?= asset($course->course_poster)?>" alt="<?= $course->title?>"></li>
											<li>
												<div class="">
													<span><?= stars($course->ratings, $course->rating_count) ?></span>
													<?= ucfirst($course->title)?>
												</div>
											</li>
										</ul>
									</div>
									<?php endforeach; ?>
								<?php else: ?>
                                 <div class="alert alert-warning text-center">There are no courses yet</div>
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