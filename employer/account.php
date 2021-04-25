<?php include('../Connection.php');  ?>
<?php
if(!Auth_employer::is_loggedin())
{
    Session::put('old_url', '/employer/account');
    Session::put('error', '*Signup or Login to access that page!');
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

        if($validation->passed())
        {
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


$subscription = $connection->select('employer_subscriptions')->where('s_employer_id', Auth_employer::employer('id'))->where('is_expire', 0)->first();

$date1 = new DateTime($subscription->start_date);
$date2 = new DateTime($subscription->end_date);

$diff = $date1->diff($date2, true);

echo $diff->format('%a') . ' days';


?>


<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<!-- images/home/4.jpg -->
	

<!-- mobile navigation-->
<?php include('includes/mobile-navigation.php');  ?>


    

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
                                            <?php $profile_image = $employer->e_image ? $employer->e_image : '/images/employer/demo.png' ?>
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
                        </div><!-- right nav end-->
                        <div class="col-lg-9"><!-- content start-->
                            <div class="mobile-alert">
                                <?php if(Session::has('error-m')): ?>
                                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error-m') ?></div>
                                <?php endif; ?>
                                <?php if(Session::has('success-m')): ?>
                                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success-m') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body">
                                    <h3 class="rh-head">Account information</h3><br><br>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['first_name'])) : ?>
                                                        <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">First name:</label>
                                                    <input type="text" name="first_name" class="form-control h50" value="<?= $employer->first_name ?? old('first_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['last_name'])) : ?>
                                                        <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Last name:</label>
                                                    <input type="text" name="last_name" class="form-control h50" value="<?= $employer->last_name ?? old('last_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['email'])) : ?>
                                                        <div class="text-danger"><?= $errors['email']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Email:</label>
                                                    <input type="text" name="email" class="form-control h50" value="<?= $employer->email ?? old('email') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['phone'])) : ?>
                                                        <div class="text-danger"><?= $errors['phone']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Phone:</label>
                                                    <input type="text" name="phone" class="form-control h50" value="<?= $employer->e_phone ?? old('phone') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['birth_date'])) : ?>
                                                        <div class="text-danger"><?= $errors['birth_date']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Birth date:</label>
                                                    <input type="date" name="birth_date" class="form-control h50" value="<?= date('Y-m-d', strtotime($employer->dob)) ?? date('Y-m-d', strtotime(old('birth_date'))) ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['city'])) : ?>
                                                        <div class="text-danger"><?= $errors['city']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">City:</label>
                                                    <input type="text" name="city" class="form-control h50" value="<?= $employer->city ?? old('city') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['state'])) : ?>
                                                        <div class="text-danger"><?= $errors['state']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">State:</label>
                                                    <input type="text" name="state" class="form-control h50" value="<?= $employer->state ?? old('state') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['country'])) : ?>
                                                        <div class="text-danger"><?= $errors['country']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Country:</label>
                                                    <input type="text" name="country" class="form-control h50" value="<?= $employer->country ?? old('country') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['address'])) : ?>
                                                        <div class="text-danger"><?= $errors['address']; ?></div>
                                                    <?php endif; ?>
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
                        </div><!-- content end-->
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

// ===========================================
//      OPEN PROFILE IMAGE
// ===========================================
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
                img_preloader();
            }
        }
    });
});







// ========================================
//     GET EMPLOYER IMAGE
// ========================================
function get_employer_img(){
    var url = $(".ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
            get_employer_img: 'get_employer_img'
        },
        success: function (response){
            $(".img-conatiner-x .em-img").html(response)
        }
    });
}





// ========================================
//     GET IMAGE PRELOADER
// ========================================
function img_preloader(string){
    $(".e-loader-kamo").show();
    setTimeout(function(){
        get_employer_img()
        $(".e-loader-kamo").hide();
    }, 5000);
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


});
</script>










