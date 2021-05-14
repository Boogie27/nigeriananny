<?php include('../Connection.php');  ?>
<?php
if(!Auth_employer::is_loggedin())
{
    return view('/');
}





// ============================================
//  UPDATE EMPLOYER PROFILE
// ============================================
if(Input::post('update_profile'))
{
        $validate = new DB();
        $validation = $validate->validate([
            'email' => 'required|email',
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'phone' => 'required|min:11|max:11|number',
            'city' => 'required|min:3|max:50',
            'birth_date' => 'required',
            'state' => 'required|min:3|max:50',
            'country' => 'required|min:3|max:50',
            'address' => 'required|min:3|max:100',
        ]);

        if($validation->passed())
        {
            $my_email = $connection->select('employers')->where('email', Input::get('email'))->where('id', Auth_employer::employer('id'))->first();
            if(!$my_email)
            {
                $all_email = $connection->select('employers')->where('email', Input::get('email'))->get();           
                if(count($all_email))
                {
                    Session::errors('errors', ['email' => '*Email already exists']);
                    return back();
                }
            }

            $create = new DB();
            $create->update('employers', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'email' => Input::get('email'),
                    'e_phone' => Input::get('phone'),
                    'dob' => Input::get('birth_date'),
                    'city' => Input::get('city'),
                    'state' => Input::get('state'),
                    'country' => Input::get('country'),
                    'address' => Input::get('address'),
                ])->where('id', Auth_employer::employer('id'))->save();
    
            if($create->passed())
            {
                Auth_employer::login(Input::get('email'));
                Session::flash('success', 'Account updated successfully!');
                Session::flash('success-m', 'Account updated successfully!');
                return back();
            }
        }

}





// ======================================
// GET EMPLOYER DETAILS
// ======================================
$employer = $connection->select('employers')->where('id', Auth_employer::employer('id'))->where('email', Auth_employer::employer('email'))->where('e_deactivate', 0)->first();
if(!$employer)
{
    Session::put('old_url', '/employer/account');
    Session::delete('employer');
    return view('/employer/login');
}




// ********** GET SUBSCRIPTIONS ************//
$employer_subscriptions = $connection->select('employer_subscriptions')->where('s_employer_id', Auth_employer::employer('id'))->where('is_employer_delete', 0)->get();




// ************CHECK IF ACCOUNT INFO IS COMPLETED ***********//
$approve = false;
$alert = false;
if($employer->employer_approved == 0  && $employer->first_name && $employer->address && $employer->city && $employer->e_image && $employer->e_phone)
{
    $update = $connection->update('employers', [
                'employer_approved' => 1
            ])->where('id', $employer->id)->save();

            $approve = true;
            $alert = 'Account has been approved!';
}





// ************DISAPPROVE IF ACCOUNT INFO IS INCOMPLETED ***********//
$state = false;
if($employer->employer_approved)
{
    if($employer->first_name && $employer->e_image && $employer->e_phone && $employer->city && $employer->address)
    {
        $state = true;
    }
    
    if(!$state)
    {    
        $update = $connection->update('employers', [
                    'employer_approved' => 0
                ])->where('id', $employer->id)->save();
        if($update)
        {
            return back();
        }
    }
}

?>

<?php include('../includes/header.php');  ?>


<!--  navigation-->
<?php include('../includes/navigation.php');  ?>

<?php include('../includes/side-navigation.php');  ?>
    

<!-- jobs  start-->
<div class="page-content">
    <div class="items-container">
        <div class="account-container" id="account-container">
            
            <div class="desktop-alert">
                <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                <?php endif; ?>
                <?php if(Session::has('success')): ?>
                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                <?php endif; ?>
                <?php if($alert): ?>
                    <div class="alert alert-success text-center p-3 mb-2"><?= $alert ?></div>
                <?php endif; ?>
                <?php if(!$employer->employer_approved && !$approve): ?>
                    <div class="alert alert-danger account-verify-alert">
                        <i class="fa fa-times float-right text-danger"></i>
                        *Complete account information to be approved like <br>Full name, image and contact information!
                    </div>
                <?php endif; ?>
           </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3"> <!-- right nav start-->
                            <div class="account-x">
                                <div class="head-x flex-item"><i class="fa fa-user-o"></i><h4>Personal information </h4> </div>
                                <div class="account-x-body">
                                    <div class="img-conatiner-x">
                                        <div class="em-img">
                                            <?php $profile_image = $employer->e_image ? $employer->e_image : '/employee/images/demo.png' ?>
                                            <img src="<?= asset($profile_image) ?>" alt="<?= $employer->first_name ?>" class="acc-img" id="profile_image_img">
                                            <i class="fa fa-camera" id="profile_img_open"></i>
                                            <input type="file" class="profile_img_input" style="display: none;">
                                            <div class="text-danger alert_profile_img text-center"></div>
                                        </div>
                                        <!-- preloader -->
                                        <div class="e-loader-kamo">
                                            <div class="r">
                                                <div class="preload"></div>
                                            </div>
                                        </div>
                                        <div class="dob text-center text-success" style="font-size: 12px;"><span>Joined: </span><?= date('d M Y', strtotime($employer->e_date_joined)) ?></div>
                                        <ul class="anchor-acc">
                                            <li><a href="<?= url('/employer/account') ?>">Account</a></li>
                                            <li><a href="<?= url('/employer/job-offer') ?>">Job offeres</a></li>
                                            <li><a href="<?= url('/employer/accepted')?>">Accepted offers</a></li>
                                            <li><a href="<?= url('/employer/change-password')?>">Change password</a></li>
                                            <li><a href="<?= url('/employer/logout')?>">Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="account-x">
                                <?php if($alert): ?>
                                    <div class="text-success text-center"><?= $alert ?></div>
                                <?php endif; ?>
                                <div class="head-x flex-item"><i class="fa fa-key"></i><h4>Status information </h4> </div>
                                <div class="account-x-body">
                                    <ul class="ul-account-bar">
                                        <li>Full name <i id="check_full_name" class="fa fa-check <?= $employer->first_name ? 'text-warning' : 'text-danger'?>"></i></li>
                                        <li>Profile image <i id="check_image" class="fa fa-check <?= $employer->e_image ? 'text-warning' : 'text-danger'?>"></i></li>
                                        <li>City <i id="check_salary" class="fa fa-check <?= $employer->city ? 'text-warning' : 'text-danger'?>"></i></li>
                                        <li>Address <i id="check_education" class="fa fa-check <?= $employer->address ? 'text-warning' : 'text-danger'?>"></i></li>
                                        <li>State <i id="check_salary" class="fa fa-check <?= $employer->city ? 'text-warning' : 'text-danger'?>"></i></li>
                                        <li>Country <i id="check_salary" class="fa fa-check <?= $employer->city ? 'text-warning' : 'text-danger'?>"></i></li>
                                    </ul>
                                </div>
                            </div>

                        </div><!-- right nav end-->
                        <div class="col-lg-9"><!-- content start-->
                            <div class="mobile-alert">
                                <?php if(Session::has('error-m')): ?>
                                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error-m') ?></div>
                                <?php endif; ?>
                                <?php if(Session::has('success-m')): ?>
                                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success-m') ?></div>
                                <?php endif; ?>
                                <?php if(!$employer->employer_approved && !$approve): ?>
                                    <div class="alert alert-danger account-verify-alert">
                                        <i class="fa fa-times float-right text-danger"></i>
                                        *Complete account information to be approved like <br>Full name, image and contact information!
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body">
                                    <h3 class="rh-head">Account information</h3><br><br>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['first_name'])) : ?>
                                                            <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">First name:</label>
                                                    <input type="text" name="first_name" class="form-control h50" value="<?= $employer->first_name ?? old('first_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['last_name'])) : ?>
                                                            <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">Last name:</label>
                                                    <input type="text" name="last_name" class="form-control h50" value="<?= $employer->last_name ?? old('last_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['email'])) : ?>
                                                            <div class="text-danger"><?= $errors['email']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">Email:</label>
                                                    <input type="text" name="email" class="form-control h50" value="<?= $employer->email ?? old('email') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['phone'])) : ?>
                                                            <div class="text-danger"><?= $errors['phone']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">Phone:</label>
                                                    <input type="text" name="phone" class="form-control h50" value="<?= $employer->e_phone ?? old('phone') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['birth_date'])) : ?>
                                                            <div class="text-danger"><?= $errors['birth_date']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">Birth date:</label>
                                                    <input type="date" name="birth_date" class="form-control h50" value="<?= date('Y-m-d', strtotime($employer->dob)) ?? date('Y-m-d', strtotime(old('birth_date'))) ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['city'])) : ?>
                                                            <div class="text-danger"><?= $errors['city']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">City:</label>
                                                    <input type="text" name="city" class="form-control h50" value="<?= $employer->city ?? old('city') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['state'])) : ?>
                                                            <div class="text-danger"><?= $errors['state']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">State:</label>
                                                    <input type="text" name="state" class="form-control h50" value="<?= $employer->state ?? old('state') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['country'])) : ?>
                                                            <div class="text-danger"><?= $errors['country']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">Country:</label>
                                                    <input type="text" name="country" class="form-control h50" value="<?= $employer->country ?? old('country') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['address'])) : ?>
                                                            <div class="text-danger"><?= $errors['address']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label for="">Address:</label>
                                                <textarea name="address" cols="30" rows="3" class="form-control h50" placeholder="Write something..."><?= $employer->address ?? old('address') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button type="submit" name="update_profile" class="btn view-btn-fill float-right">Update...</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- inner content start -->
                            <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                            <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h4 class=""><i class="fa fa-money"></i> Subscription history</h4><br>
                                    </div>
                                   <?php if(count($employer_subscriptions)):?>
                                   <?php foreach($employer_subscriptions as $sub): ?>
                                        <ul class="inner_ul_2 subscription-history">
                                            <?php if($sub->is_expire && !$sub->is_employer_delete): ?>
                                            <li class="text-right">
                                                <div class="drop-down">
                                                    <i class="fa fa-ellipsis-h dot-icon"></i>
                                                    <ul class="drop-down-ul">
                                                        <li><a href="#" data-id="<?= $sub->subscription_id ?>" data-toggle="modal" data-target="#employer_delete_subscription" class="delete-employer-subscription">Delete</a></li>                                        
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php endif; ?>
                                            <li><b>Subscription type:</b> <?= ucfirst($sub->s_type)?> <span class="float-right <?= $sub->is_expire ? 'inactive-btn' : 'active-btn'?>"><?= $sub->is_expire ? 'expired' : 'active'?></span></li>
                                            <li><b>Duration:</b> <?= $sub->s_duration ?></li>
                                            <li><b>Monthly views:</b> <?= $sub->s_access ?></li>
                                            <li>
                                                <b>Start date:</b> <span class="text-success" style="font-size: 11px;"><?= date('d M Y', strtotime($sub->start_date)) ?></span>  
                                               <span class="float-right text-danger" style="font-size: 11px;"><b>End date:</b> <?= date('d M Y', strtotime($sub->end_date)) ?></span>  </li>   
                                        </ul>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center">No subscriptions yet!</div><br>
                                     <?php endif; ?>
                                    <div class="new-sub text-center pb-2">
                                        <a href="<?= url('/subscription') ?>" class="view-btn-fill"><?= count($employer_subscriptions) ? 'New subscription' : 'Subscribe' ?></a>
                                    </div>
                                </div>
                            </div>
                            <!-- inner content end -->
                        </div><!-- content end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>











<!-- Modal delete review -->
<div class="sign_up_modal modal fade" id="employer_delete_subscription" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_delete_modal_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST">
                            <div class="heading">
                                <div class="alert-delete-review text-danger text-center"></div>
                                <p class="text-center">Do you wish to delete this subscription?</p>
                            </div>
                            <input type="hidden" class="delete_subscription_input" value="">
                            <button type="submit" class="btn btn-log btn-block bg-danger" id="delete_subscription_btn" style="color: #fff">Delete subscription</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




<!-- Our Footer -->
<?php include('../includes/footer.php');  ?>











<script>
$(document).ready(function(){

// ********** REMOVE ACCOUNT VERIFY ALERT *******//
$(".account-verify-alert i").click(function(){
    $(".account-verify-alert").hide();
})




//********* OPEN PROFILE IMAGE *********//
$('.img-conatiner-x').on('click', '#profile_img_open', function(){
     $(".profile_img_input").click();
     $(".alert_profile_img").html('');
});


// ============================================
//  ADD EMPLOYER PROFILE IMAGE
// ============================================
$('.img-conatiner-x').on('change', '.profile_img_input', function(){
    var url = $(".ajax_url_page").attr('href');
    var image = $(".profile_img_input");
    $("#check_image").removeClass('text-warning')
    $(".e-loader-kamo").show();

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('upload_employer_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
            if(data.error){
                error_preloader(data.error.image);
            }else if(data.data){
                $(".nav-profile-img").attr('src', data.data)
                $("#profile_image_img").attr('src', data.data)
                $("#check_image").addClass('text-warning')
                img_preloader()
            }
        }
    });
});





// ========================================
//     GET ERROR PRELOADER
// ========================================
function img_preloader(string){
    setTimeout(function(){
        $(".e-loader-kamo").hide();
    }, 1000);
}





// ========================================
//     GET ERROR PRELOADER
// ========================================
function error_preloader(string){
    $(".e-loader-kamo").show();
    setTimeout(function(){
        $('.alert_profile_img').html(string);
        $(".e-loader-kamo").hide();
    }, 2000);
}




// ************* GET DELETE SUBSCRIPTION MODAL*********//
$(".delete-employer-subscription").click(function(e){
    e.preventDefault()
    var sub_id = $(this).attr('data-id')
    $(".delete_subscription_input").val(sub_id)
})






// ************* DELETE SUBSCRIPTION*********//
$("#delete_subscription_btn").click(function(e){
    e.preventDefault();
    $(this).html('Please wait...')
    $(".page_alert_danger").hide()
    var url = $(".ajax_url_page").attr('href');
    var sub_id = $(".delete_subscription_input").val();
    $(".close_delete_modal_btn").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			sub_id: sub_id,
			delete_subscription_btn: 'delete_subscription_btn'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }else{
                $('.page_alert_danger').show();
                $('.page_alert_danger').html('*Network error, try again later');
            }
            $("#delete_subscription_btn").html("Delete subscription")
		},
        error: function(){
            $('.page_alert_danger').show();
            $("#delete_subscription_btn").html("Delete subscription")
            $('.page_alert_danger').html('*Network error, try again later');
        }
	});
});


});
</script>










