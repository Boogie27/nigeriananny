






<?php

// https://www.facebook.com/sharer.php?u=[post-url]
// https://twitter.com/share?url=[post-url]&text=[post-title]&via=[via]&hashtags=[hashtags]
// https://www.linkedin.com/shareArticle?url=[post-url]&title=[post-title]
// https://api.whatsapp.com/send?text=[post-title] [post-url]



?>





<?php include('../Connection.php');  ?>
<?php
if(!Auth_employer::is_loggedin())
{
    Session::put('old_url', '/employer/account');
    Session::put('error', '*Signup or Login to access that page!');
    return view('/');
}




if(Input::post('update_password'))
{
    $validate = new DB();
    $validation = $validate->validate([
        'old_password' => 'required|min:6|max:12',
        'new_password' => 'required|min:6|max:12',
        'confirm_password' => 'required|min:6|max:12|match:new_password',
    ]);

    if($validation->passed())
    {
        $old_password = $connection->select('employers')->where('id', Auth_employer::employer('id'))->first();
        if(!password_verify(Input::get('old_password'), $old_password->password))
        {
            Session::errors('errors', ['old_password' => '*Wrong old password, try again!']);
            return back();
        }

        $update = $connection->update('employers', [
                    'password' =>  password_hash(Input::get('old_password'), PASSWORD_DEFAULT),
                ])->where('id', Auth_employer::employer('id'))->save();

        if($update->passed())
        {
            Session::flash('success', 'Password updated successfully!');
            return back();
        }
    }
}















// =====================================
// HIRED WORKERS
// =====================================
$workers = $connection->select('request_workers')->leftJoin('workers', 'request_workers.r_worker_id', '=', 'workers.worker_id')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('j_employer_id', Auth_employer::employer('id'))->where('is_accept', 1)->get();


// =====================================
// REQUESTED WORKERS
// =====================================
$requested = $connection->select('request_workers')->leftJoin('workers', 'request_workers.r_worker_id', '=', 'workers.worker_id')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('j_employer_id', Auth_employer::employer('id'))->where('is_accept', 0)->get();



// =====================================
// EMPLOYER SUBSCRIPTION
// =====================================
$subscription = $connection->select('employer_subscriptions')->where('s_employer_id	', Auth_employer::employer('id'))->where('is_expire', 0)->first();




// =====================================
// EMPLOYER VIEWS
// =====================================
$daily_views = $connection->select('worker_daily_view')->where('wdv_employer_id	', Auth_employer::employer('id'))->first();

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
        <div class="account-container">
            <?php if(Session::has('success')): ?>
                <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
            <?php endif; ?>
            <?php if(Session::has('error')): ?>
                <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="account-x">
                        <div class="head-x flex-item"><i class="fa fa-user-o"></i><h4>Personal information</h4></div>
                        <div class="account-x-body">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3">
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
                                   </div>
                                </div>
                                <div class="col-xl-9 col-lg-9">
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['first_name'])) : ?>
                                                        <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">First name:</label>
                                                    <input type="text" name="first_name" class="form-control" value="<?= $employer->first_name ?? old('first_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['last_name'])) : ?>
                                                        <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Last name:</label>
                                                    <input type="text" name="last_name" class="form-control" value="<?= $employer->last_name ?? old('last_name') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['email'])) : ?>
                                                        <div class="text-danger"><?= $errors['email']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Email:</label>
                                                    <input type="text" name="email" class="form-control" value="<?= $employer->email ?? old('email') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['phone'])) : ?>
                                                        <div class="text-danger"><?= $errors['phone']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Phone:</label>
                                                    <input type="text" name="phone" class="form-control" value="<?= $employer->e_phone ?? old('phone') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['city'])) : ?>
                                                        <div class="text-danger"><?= $errors['city']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">City:</label>
                                                    <input type="text" name="city" class="form-control" value="<?= $employer->city ?? old('city') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['state'])) : ?>
                                                        <div class="text-danger"><?= $errors['state']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">State:</label>
                                                    <input type="text" name="state" class="form-control" value="<?= $employer->state ?? old('state') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['country'])) : ?>
                                                        <div class="text-danger"><?= $errors['country']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Country:</label>
                                                    <input type="text" name="country" class="form-control" value="<?= $employer->country ?? old('country') ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['address'])) : ?>
                                                        <div class="text-danger"><?= $errors['address']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">Address:</label>
                                                <textarea name="address" cols="30" rows="3" class="form-control" placeholder="Write something..."><?= $employer->address ?? old('address') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <button type="submit" name="update_profile" class="btn btn-primary float-right">Update...</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- subscribed start -->
                <div class="col-lg-4">
                    <div class="account-x subscription-x">
                        <div class="head-x flex-item"><i class="fa fa-money"></i><h4 id="expand">Subscription</h4></div>
                        <?php if($subscription): ?>
                        <ul class="inner-acc-bd">
                            <li class="text-center"><h3 class="text-primary"><?= ucfirst($subscription->s_type) ?></h3></li>
                            <li class="text-center"><?= $subscription->s_duration ?></li>
                            <li class="text-center"><?= money($subscription->s_amount) ?></li>
                            <li class="text-center mt-2"><b>start date:</b><?= date('d M Y', strtotime($subscription->start_date)) ?></li>
                            <li class="text-center text-danger"><b>End date:</b> <?= date('d M Y', strtotime($subscription->end_date)) ?></li>
                            <hr>
                            <li class="text-center mt-3"><a href="<?= url('/subscription')?>" class="view-btn-fill">Renew</a></li>
                        </ul>
                        <?php else: ?>
                            <div class="alert-danger text-center p-3 mt-2">You have no active subscription</div>
                            <div class="text-center"><a href="<?= url('/subscription')?>" class="text-primary">Subscribe</a></div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- subscribed end -->

               <!-- viewed workers start-->
               <div class="col-xl-4 col-lg-4">
                    <div class="account-x" id="viewed-workers-container">
                        <div class="head-x flex-item"><i class="fa fa-eye"></i><h4>Viewed workers</h4></div>
                            <?php if($daily_views):  ?>
                            <div class="inner-acc-bd">
                            <?php $views = json_decode($daily_views->worker_id);
                                foreach($views as $key => $view): 
                                    $worker = $connection->select('workers')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('worker_id', $key)->first(); ?>
                                    <div class="account-h-body flex-item">
                                        <?php $worker_img = $worker->w_image ? $worker->w_image : '/images/employee/demo.png' ?>
                                        <div class="em-img">
                                        <a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>"> <img src="<?= asset($worker_img) ?>" alt="<?= $worker->first_name ?>" class="acc-img"></a>
                                        </div>
                                        <ul class="info">
                                            <li><?= stars($worker->ratings, $worker->rating_count) ?></li>
                                            <li><a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>"><?= ucfirst($worker->first_name.' '.$worker->last_name) ?></a></l>
                                            <li><b>Job:</b> <?= $worker->job_title ?></li>
                                            <li class="text-right"><a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>" class="text-primary">View details</a></li>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-worker-x" id="empty-worker-x">
                                <div class="empty-inner view-x">
                                    <img src="<?= asset('/images/icons/1.svg')?>" alt="">
                                    <h3>No employee yet!</h3>
                                    <h5>You have not viewed any worker today!</h5>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- viewed workers end -->

                <!-- password start -->
                <div class="col-xl-4 col-lg-4">
                    <div class="account-x">
                        <div class="head-x flex-item"><i class="fa fa-key"></i><h4>Update password</h4></div>
                        <div class="alert-cont-x">
                            <?php if(Session::has('success')): ?>
                                <div class="alert-success text-center"><?= Session::flash('success') ?></div>
                            <?php endif; ?>
                            <?php if(Session::has('error')): ?>
                                <div class="alert-danger text-center"><?= Session::flash('error') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="account-x-body">
                            <form action="<?= current_url() ?>" method="POST">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <?php  if(isset($errors['old_password'])) : ?>
                                            <div class="text-danger alert-pwd"><?= $errors['old_password']; ?></div>
                                        <?php endif; ?>
                                        <input type="password" name="old_password" class="last_name_input form-control" placeholder="Old password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <?php  if(isset($errors['new_password'])) : ?>
                                            <div class="text-danger alert-pwd"><?= $errors['new_password']; ?></div>
                                        <?php endif; ?>
                                        <input type="password" name="new_password" class="last_name_input form-control" placeholder="New password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <?php  if(isset($errors['confirm_password'])) : ?>
                                            <div class="text-danger alert-pwd"><?= $errors['confirm_password']; ?></div>
                                        <?php endif; ?>
                                        <input type="password" name="confirm_password" class="last_name_input form-control" placeholder="Confirm password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text-right">
                                            <button type="submit" name="update_password" class="btn btn-primary">Update...</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- password end -->

                <!-- employed workers start-->
                <div class="col-xl-7 col-lg-7">
                    <div class="account-x" id="hired-workers-container">
                        <div class="head-x flex-item"><i class="fa fa-briefcase"></i><h4>Hired workers</h4></div>
                        <?php if(count($workers)): 
                        foreach($workers as $worker): 
                            $status = $worker->is_accept ? 'accepted' : 'pending';
                            $icon = $worker->is_accept ? 'bg-success' : 'bg-warning';
                            if($worker->is_cancle)
                            {
                                $status = 'cancled';
                                $icon = 'bg-danger';
                            }            
                        ?>
                        <div class="account-h-body flex-item">
                            <?php $worker_img = $worker->w_image ? $worker->w_image : '/images/employee/demo.png' ?>
                            <div class="em-img">
                               <a href="#"> <img src="<?= asset($worker_img) ?>" alt="<?= $worker->first_name ?>" class="acc-img"></a>
                            </div>
                            <ul class="info">
                                <li>
                                    <?= stars($worker->ratings, $worker->rating_count) ?>
                                    <span class="float-right text-secondary">Date: <?= date('d M Y', strtotime($worker->date_added)) ?></span><br>
                                    <span class="float-right is_accept <?= $icon ?>"><?= $status?></span>
                                </li>
                                <li><a href="<?= url('/employer/employee-detail.php?wid='.$worker->worker_id) ?>"><?= ucfirst($worker->first_name.' '.$worker->last_name) ?></a></l>
                                <li><b>Job:</b> <?= $worker->job_title ?> <span class="float-right"><a href="<?= url('/employer/employee-detail.php?wid='.$worker->worker_id) ?>" class="text-primary">View details</a></span></li>
                            </ul>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                             <div class="empty-worker-x">
                                <div class="empty-inner">
                                    <img src="<?= asset('/images/icons/1.svg')?>" alt="">
                                    <h3>No employee yet!</h3>
                                    <h5>You have not ordered for a worker yet!</h5>
                                </div>
                             </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- epmplyed workers end-->

                <!-- Requested workers start-->
                <div class="col-xl-5 col-lg-5">
                    <div class="account-x" id="hired-workers-container">
                        <div class="head-x flex-item"><i class="fa fa-bell"></i><h4>Requested workers</h4></div>
                        <?php if(count($requested)): 
                        foreach($requested as $request): 
                            $status = $request->is_accept ? 'accepted' : 'pending';
                            $icon = $request->is_accept ? 'bg-success' : 'bg-warning';
                            if($request->is_cancle)
                            {
                                $status = 'cancled';
                                $icon = 'bg-danger';
                            }            
                        ?>
                        <div class="account-h-body flex-item">
                            <?php $worker_img = $request->w_image ? $request->w_image : '/images/employee/demo.png' ?>
                            <div class="em-img">
                               <a href="#"> <img src="<?= asset($worker_img) ?>" alt="<?= $request->first_name ?>" class="req-img"></a>
                            </div>
                            <ul class="info">
                                <li>
                                    <?= stars($worker->ratings, $worker->rating_count) ?>
                                    <span class="float-right text-secondary">Date: <?= date('d M Y', strtotime($request->date_added)) ?></span><br>
                                    <span class="float-right is_accept <?= $icon ?>"><?= $status?></span>
                                </li>
                                <li><a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>"><?= ucfirst($request->first_name.' '.$request->last_name) ?></a></l>
                                <li><b>Job:</b> <?= $worker->job_title ?></li>
                            </ul>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                             <div class="empty-worker-x">
                                <div class="empty-inner">
                                    <img src="<?= asset('/images/icons/1.svg')?>" alt="">
                                    <h3>No employee yet!</h3>
                                    <h5>You have not requested for a worker yet!</h5>
                                </div>
                             </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Requested workers end-->

            </div>
        </div>
    </div>
</div>




<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>



















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
