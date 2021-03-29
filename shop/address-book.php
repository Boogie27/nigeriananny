<?php include('../Connection.php');  ?>

<?php

if(!Auth::is_loggedin())
{
	Session::put('old_url', current_url());
   return Redirect::to('login.php');
}

if(Input::post('update_address'))
{
    $validate = new DB();
    $message = Input::get('message');
       
    $validation = $validate->validate([
        'first_name' => 'required|min:3|max:50',
        'last_name' => 'required|min:3|max:50',
        'message' => 'min:3|max:4000',
        'address' => 'required:3|max:1000',
        'state' => 'required|min:3|max:50',
        'city' => 'required|min:3|max:50',
        'country' => 'required|min:3|max:50'
    ]);

    $user = $connection->select('users')->where('email', Auth::user('email'))->where('id', Auth::user('id'))->first();
    if(empty($message))
    {
        $message = $user->message ? $user->message : null;
    }

    $myCity =  $connection->select('tbl_city')->where('id', Input::get('city'))->first(); 
    $city = $myCity ? $myCity->city : null;

    $myState = $connection->select('tbl_state')->where('id', Input::get('state'))->first(); 
    $state = $myState ? $myState->state : null;

    $myCountry = $connection->select('tbl_country')->where('id', Input::get('country'))->first();
    $country = $myCountry ? $myCountry->country : null;

    if($validation->passed())
    {
        $update = $connection->update('users', [
            'first_name' => Input::get('first_name'),
            'last_name' => Input::get('last_name'),
            'address' => Input::get('address'),
            'message' => $message,
            'city' => $city,
            'state' => $state,
            'country' => $country,
        ])->where('id', Auth::user('id'))->save();
        
        Session::put('success', 'Address has been updated sucessfully!');
        return back();
    }
}
?>
<?php include('includes/header.php') ?>

<?php include('includes/dash-board-navigation.php'); ?>


<?php include('includes/account-mobile-navigation.php') ?>


<?php include('includes/side-bar.php'); ?>





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
                            <?php if(Session::has('success')): ?>
                            <div class="page_alert_success alert-success text-center p-3 mb-2"><?= Session::flash('success')?></div>
                            <?php endif; ?>
							<div class="page_alert_success alert-success text-center p-3 mb-2" style="display: none;"></div>
						</div>
						<div class="col-lg-12">
							<nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
								<h4 class="title float-left">Address book</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="#">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Address</li>
								</ol>
							</nav>
						</div>
					
						<div class="col-xl-12">
							<div class="recent_job_activity">
                                <h4 class="title">Address</h4>
                            
							    <form action="<?= current_url() ?>" method="post" enctype="multipart/form-data" class="user_detail_form">
								    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="user_profile_img">
											<?php if($user->user_image): ?>
												<img src="<?= asset(Auth::user('user_image')); ?>" alt="<?= $user->first_name?>">
											<?php else: ?>	
												<img src="<?= asset('/shop/images/users/demo.png') ?>" alt="<?= $user->first_name ?>">
											<?php endif; ?>										
                                            </div>
										</div>
                                        <div class="col-lg-6">
											<div class="form-group">
                                                <?php  if(isset($errors['first_name'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['first_name'] ?></div>
                                                <?php endif; ?>
												<label for="">First name</label>
												<input type="text" name="first_name" class="form-control" value="<?= $user->first_name ?? old('first_name') ?>">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
											    <?php  if(isset($errors['last_name'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['last_name'] ?></div>
                                                <?php endif; ?>
												<label for="">Last name</label>
												<input type="text" name="last_name" class="form-control" value="<?= $user->last_name ?>">
											</div>
										</div>
										<div class="col-lg-12">
											<div class="form-group">
											    <?php  if(isset($errors['address'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['address'] ?></div>
                                                <?php endif; ?>
												<label for="">Address</label>
												<input type="text" name="address" class="form-control" value="<?= $user->address ?? old('address') ?>">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
											    <?php  if(isset($errors['state'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['state'] ?></div>
                                                <?php endif; ?>
												<label for="">State</label>
												<input type="text" name="state" id="select_state_container" class="form-control" value="<?= $user->state ?? old('state') ?>">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
											    <?php  if(isset($errors['city'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['city'] ?></div>
                                                <?php endif; ?>
												<label for="">City</label>
												<input type="text" name="city"  id="select_city_container" class="form-control" value="<?= $user->city ?? old('city') ?>">
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
											    <?php  if(isset($errors['country'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['country'] ?></div>
                                                <?php endif; ?>
												<label for="">Country</label>
												<input type="text" name="country" id="select_country_btn" class="form-control" value="<?= $user->country ?? old('country') ?>">
											</div>
										</div>
                                        <div class="col-lg-12">
											<div class="form-group">
											    <?php  if(isset($errors['phone'])) : ?>
                                                    <div class="alert-change text-danger">  <?= $errors['phone'] ?></div>
                                                <?php endif; ?>
												<label for="">Additional Information</label>
											    <textarea name="message" class="form-control" cols="30" rows="5"><?= $user->message ?? old('message') ?></textarea>
											</div>
										</div>
                                        <div class="col-lg-12">
											<div class="form-group">
												<button type="submit" name="update_address" class="btn-fill  float-right">Submit</button>
											</div>
										</div>
									</div>
								</form>
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




<a href="<?= url('/shop/ajax.php') ?>" id="ajax_url" style="display: none;"></a>


<!-- footer -->
<div style="position: relative; z-index: 1000;">
	<?php include('includes/footer.php') ?>
</div>






<script>

$(document).ready(function(){
		

// ==============================================
// GET STATE
// ===============================================
var url = $("#ajax_url").attr('href');
$(".select_country_btn").on('change', function(e){
	var country_id = $(this).val();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			country_id: country_id,
			get_state: 'get_state'
		},
		success: function(response){
			var states = JSON.parse(response);
			get_cities(states.first_state_id);
			$('#select_state_container').html(states.states).selectpicker('refresh');
		}
	});
});




// GET CITIES AFTER SELECTING COUNTRY
function get_cities(state_id){
	$.ajax({
		url: url,
		method: 'post',
		data: {
			state_id: state_id,
			get_city: 'get_city'
		},
		success: function(response){
			var cities = JSON.parse(response);
			$('#select_city_container').html(cities.cities).selectpicker('refresh');
		}
	});
}



// ==============================================
// GET CITY
// ===============================================
$("#select_state_container").on('change', function(){
	var state_id = $(this).val();
	get_cities(state_id)
});


});

</script>