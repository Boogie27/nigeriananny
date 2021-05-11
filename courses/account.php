<?php include('../Connection.php');  ?>


<?php 
if(!Auth_course::is_loggedIn())
{
    return view('/courses');
}




// ******** REGISTER COURSE USER ***********//
if(Input::post('update_account'))
{
    $validate = new DB();
       
    $validation = $validate->validate([
        'first_name' => 'required|min:3|max:50',
        'last_name' => 'required|min:3|max:50',
        'email' => 'required|email',
        'phone' => 'required|min:11|max:11|number:phone',
        'city' => 'required|min:3|max:50',
        'state' => 'required|min:3|max:50',
        'country' => 'required|min:3|max:50',
    ]);

    $verify_email = $connection->select('course_users')->where('email', Input::get('email'))->where('id', Auth_course::user('id'))->first();
    if(!$verify_email)
    {
        $all_email = $connection->select('course_users')->where('email', Input::get('email'))->get();           
        if(count($all_email))
        {
            Session::errors('errors', ['email' => '*Email already exists']);
            return back();
        }
    }
    

    if($validation->passed())
    {
        $connection->update('course_users', [
            'first_name' => Input::get('first_name'),
            'last_name' => Input::get('last_name'),
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'city' => Input::get('city'),
            'state' => Input::get('state'),
            'country' => Input::get('country'),
        ])->where('id', Auth_course::user('id'))->save();

        if(Auth_course::login(Input::get('email'), $remember_me))
        {
            Session::flash('success', 'Account updated successfully');
            return view('/courses/account');
        }
    }
   
}



// ********** GET ACCOUNT INFO ********//
$profile = $connection->select('course_users')->where('email', Auth_course::user('email'))->where('id', Auth_course::user('id'))->first();
if(!$profile)
{
    return view('/courses');
}
?>




<?php include('includes/header.php');  ?>


<?php include('includes/navigation.php');  ?>


<?php
// ************* MORE COURSES *****************//
$others = $connection->select('courses')->where('is_feature', 1)->random()->limit(8)->get();



?>

<div class="page-content-x">
    <div class="row" id="page-expand">
        <div class="col-lg-3" id="side-navigation-container">
            <?php include('includes/side-navigation.php');  ?>
        </div>
        <div class="col-lg-9 body-expand">
            <div class="body-content home-body-content">
                <div class="parent-container">
                     <?php if(Session::has('error')): ?>
                        <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                    <?php endif; ?>
                    <?php if(Session::has('success')): ?>
                        <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="sign_up_form account-course-page">
						<div class="heading">
                            <h3 class="text-center">Account page</h3>
                            <div class="text-center text-danger alert_no_label alert_0"></div>                            
						</div>
						<div class="details">
                            <div class="course-account-img-con">
                                <?php $profile_img = $profile->image ? $profile->image : '/courses/images/user/demo.png'?>
                                <img src="<?= asset($profile_img) ?>" alt="profile image" id="course_profile_img">
                               <a href="#" id="course_profile_img_open"> <i class="fa fa-camera"></i></a>
                                <input type="file" id="course_profile_img_input" style="display: none;">
                            </div>
                           
							<form action="<?= current_url()?>" method="POST">
                                <div class="row">
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['first_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" name="first_name" class="form-control" placeholder="First name" value="<?= $profile->first_name ?? old('first_name')?>">
                                        </div>
                                     </div>
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['last_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" name="last_name" class="form-control" placeholder="Last name" value="<?= $profile->last_name ?? old('last_name')?>">
                                        </div>
                                     </div>
                                     <div class="col-lg-8">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['email'])) : ?>
                                                    <div class="text-danger"><?= $errors['email']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $profile->email ?? old('email')?>">
                                        </div>
                                     </div>
                                     <div class="col-lg-4">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['phone'])) : ?>
                                                    <div class="text-danger"><?= $errors['phone']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" name="phone" class="form-control" placeholder="Phone" value="<?= $profile->phone ?? old('phone')?>">
                                        </div>
                                     </div>
                                     <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['city'])) : ?>
                                                    <div class="text-danger"><?= $errors['city']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" name="city" class="form-control" placeholder="City" value="<?= $profile->city ?? old('city')?>">
                                        </div>
                                     </div>
                                     <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['state'])) : ?>
                                                    <div class="text-danger"><?= $errors['state']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" name="state" class="form-control" placeholder="State" value="<?= $profile->state ?? old('state')?>">
                                        </div>
                                     </div>
                                     <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['country'])) : ?>
                                                    <div class="text-danger"><?= $errors['country']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" name="country" class="form-control" placeholder="Country" value="<?= $profile->country ?? old('country')?>">
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                        <div class="form-group text-right">
                                            <button type="submit" name="update_account" class="btn btn-log button">Update account</button>
                                        </div>
                                     </div>
                                </div>
							</form>
						</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12"><!-- more courses container end -->
                           <br><br>
                           <div class="more-course">
                                <div class="title"><h4>More courses</h4></div>
                                <div class="more-course-body">
                                    <div class="row">
                                        <?php if(count($others)): 
                                        foreach($others as $other):    
                                        ?>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="<?= url('/courses/detail.php?cid='.$other->course_id) ?>"><img src="<?= asset($other->course_poster) ?>" alt="<?= $other->title ?>"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4><?= substr(ucfirst($other->title), 0, 30)?></h4></a>
                                                    </li>
                                                    <li>
                                                        <p><?= substr($other->description, 0, 50)?></p>
                                                    </li>
                                                    <li><?= stars($other->ratings, $other->rating_count)?></li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                           </div>
                        </div><!-- morder course container end -->
                    </div>
                </div>
            </div>
            <!-- footer -->
            <?php include('includes/footer.php') ?>
        </div>
    </div>
</div>









<a href="<?= url('/courses/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




<script>
$(document).ready(function(){
    
// ===========================================
//      OPEN PROFILE IMAGE
// ===========================================
$('#course_profile_img_open').click(function(e){
    e.preventDefault()
    $("#course_profile_img_input").click();
    $('.alert_0').html('')
});


//  ********** ADD PROFILE IMAGE ***********//
$('.course-account-img-con').on('change', '#course_profile_img_input', function(){
    var url = $(".ajax_url_page").attr('href');
    var image = $("#course_profile_img_input");
    $(".little-preloader-container").show();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('upload_course_user_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
            if(data.error){
                $('.alert_0').html(data.error.image);
            }else if(data.data){
                $("#course_profile_img").attr('src', data.data)
                $(".nav-profile-img").attr('src', data.data)
            }else{
                $('.alert_0').html('Network error, try again later!')
            }
            $(".little-preloader-container").hide();

           
        }, 
        error: function(){
            $(".little-preloader-container").hide();
            $('.alert_0').html('Network error, try again later!')
        }
    });
});


})
</script>