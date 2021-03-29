<?php include('../Connection.php');  ?>
<?php
if(!Auth_employer::is_loggedin())
{
    Session::put('old_url', '/employer/account');
    Session::put('error', '*Signup or Login to access that page!');
    return view('/');
}

if(Input::post('update_profile'))
{
    echo "yes";
}



$employer = $connection->select('employers')->where('id', Auth_employer::employer('id'))->where('email', Auth_employer::employer('email'))->where('e_deactivate', 0)->first();
if(!$employer)
{
    Session::put('old_url', '/employer/account');
    Session::delete('employer');
    return view('/employer/login');
}



// =====================================
// HIRED WORKERS
// =====================================
$workers = $connection->select('request_workers')->leftJoin('workers', 'request_workers.r_worker_id', '=', 'workers.worker_id')->where('j_employer_id', Auth_employer::employer('id'))->get();
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
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="account-x">
                        <div class="head-x flex-item"><i class="fa fa-user-o"></i><h4>Personal information</h4></div>
                        <div class="account-x-body flex-item">
                            <div class="em-img">
                                <?php $profile_image = $employer->e_image ? $employer->e_image : '/images/employee/demo.png' ?>
                                <img src="<?= asset($profile_image) ?>" alt="<?= $employer->first_name ?>" class="acc-img">
                                <i class="fa fa-camera"></i>
                            </div>
                            <ul class="info">
                                <li><?= ucfirst($employer->first_name.' '.$employer->last_name) ?></l>
                                <li class="text-success"><?= $employer->email ?></li>
                                <li>Memeber since <b><?= date('d M Y', strtotime($employer->e_date_joined)) ?></b></li>
                                <li class="anchor-x"><a href="#" class="text-primary"><b>update info...</b></a></li>
                            </ul>
                        </div>
                        <div class="account-z-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6"><div class="ph-x"><b>Phone:</b> <br><span><?= $employer->e_phone ?></span></div></div>
                                <div class="col-lg-6 col-md-6 col-sm-6"><div class="ph-x"><b>City:</b> <br><span><?= $employer->city ?></span></div></div>
                                <div class="col-lg-6 col-md-6 col-sm-6"><div class="ph-x"><b>State:</b> <br><span><?= $employer->state ?></span></div></div>
                                <div class="col-lg-6 col-md-6 col-sm-6"><div class="ph-x"><b>Country:</b> <br><span><?= $employer->country ?></span></div></div>
                                <div class="col-lg-6 col-md-6 col-sm-6"><div class="ph-x"><b>Address:</b> <br><span><?= $employer->address ?></span></div></div>
                            </div>
                        </div>
                        <p class="p-p">Your profile is based on the information you provided this account. you can also choose to update your profile.</p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
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
                            <?php $worker_img = $worker->w_image ? $worker->w_image : '/images/employee/worker.png' ?>
                            <div class="em-img">
                               <a href="#"> <img src="<?= asset($worker_img) ?>" alt="<?= $worker->first_name ?>" class="acc-img"></a>
                            </div>
                            <ul class="info">
                                <li>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="float-right text-secondary">Date: <?= date('d M Y', strtotime($worker->date_added)) ?></span><br>
                                    <span class="float-right is_accept <?= $icon ?>"><?= $status?></span>
                                </li>
                                <li><a href="#"><?= ucfirst($worker->first_name.' '.$worker->last_name) ?></a></l>
                                <li><b>Job:</b> <?= $worker->job_title ?></li>
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

                 <div class="col-xl-8 col-lg-8">
                    <div class="account-x">
                        <div class="head-x flex-item"><i class="fa fa-folder"></i><h4>Update information</h4></div>
                        <div class="account-x-body">
                            <form action="<?= current_url()?>" method="POST">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['first_name'])) : ?>
                                                <div class="text-danger"><?= $errors['first_name']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="first_name" class="form-control" placeholder="First name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['last_name'])) : ?>
                                                <div class="text-danger"><?= $errors['last_name']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="last_name" class="form-control" placeholder="Last name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['email'])) : ?>
                                                <div class="text-danger"><?= $errors['email']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="email" class="form-control" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['phone'])) : ?>
                                                <div class="text-danger"><?= $errors['phone']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="phone" class="form-control" placeholder="Phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['city'])) : ?>
                                                <div class="text-danger"><?= $errors['city']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="city" class="form-control" placeholder="City">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <?php  if(isset($errors['state'])) : ?>
                                                <div class="text-danger"><?= $errors['state']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="state" class="form-control" placeholder="State">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <?php  if(isset($errors['country'])) : ?>
                                                <div class="text-danger"><?= $errors['country']; ?></div>
                                            <?php endif; ?>
                                            <input type="text" name="country" class="form-control" placeholder="Country">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <?php  if(isset($errors['address'])) : ?>
                                                <div class="text-danger"><?= $errors['address']; ?></div>
                                            <?php endif; ?>
                                           <textarea name="address" cols="30" rows="3" class="form-control" placeholder="Address"></textarea>
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

                <div class="col-xl-4 col-lg-4">
                    <div class="account-x">
                        <div class="head-x flex-item"><i class="fa fa-key"></i><h4>Update password</h4></div>
                        <div class="account-x-body">
                            <form action="" method="POST">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" class="last_name_input form-control" placeholder="Old password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" class="last_name_input form-control" placeholder="New password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" class="last_name_input form-control" placeholder="Confirm password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                        <div class="form-group">
                                             <button type="button" class="btn btn-primary float-right">Update...</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>









<li class="text-right acc-detail">
    <span class=""><a href="<?= url('/employee/job-detail.php?rid='.$request->request_id ) ?>" class="text-primary v-deatil">View detail</a></span>
    <span><a href="#" data-toggle="modal"  data-target="#employee_request_accept_btn" data-id="<?= $request->request_id?>" class="act <?= $request->is_accept ? 'bg-success' : 'bg-warning'?> employee_request_accept_btn"><?= !$request->is_accept ? 'Accept offer' : 'Accepted'?></a></span>
</li>









    <script>
$(document).ready(function(){
// client ID
// 653369834194-p048lc6ed0iep6m1bplejlbvirm2beko.apps.googleusercontent.com

// client secrete
// 1yUaw8xTZL5FOg7ow61PvaZw
});
</script>





