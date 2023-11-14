<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/employer-detail.php?wid='.Input::get('wid'));
  return view('/admin/login');
}




// =====================================
// CHECK IF EMPLOYEE WAS CLICK
// =====================================
if(!Input::exists('get') || !Input::get('wid'))
{
    return view('/admin-nanny/employers');
}



// ============================================
//  UPDATE EMPLOYER PROFILE
// ============================================
if(Input::post('update_profile'))
{
    if(Token::check())
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

        if(!$validation->passed())
        {
            return back();
        }

        $my_email = $connection->select('employers')->where('email', Input::get('email'))->where('id', Input::get('wid'))->first();
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
                ])->where('id', Input::get('wid'))->save();
    
            if($create->passed())
            {
                Session::flash('success', 'Account updated successfully!');
                return back();
            }
        }
    }
}








// ======================================
// GET EMPLOYER DETAILS
// ======================================
$employer = $connection->select('employers')->where('id', Input::get('wid'))->first();
if(!$employer)
{
    return view('/admin-nanny/employers');
}





// ======================================
// GET REQUESTED OFFERS
// ======================================
$requests = $connection->select('request_workers')->leftJoin('employee', 'request_workers.j_employee_id', '=', 'employee.e_id')
                       ->leftJoin('workers', 'request_workers.r_worker_id', '=', 'workers.worker_id')->where('j_employer_id', Input::get('wid'))->get();




// ===============================================
// app banner settings
// ===========================================
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
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Employee infomation</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/employees') ?>">Employees</a></li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="col-lg-12"><!-- content start-->
                            <div class="mobile-alert">
                                <?php if(Session::has('error')): ?>
                                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                                <?php endif; ?>
                                <?php if(Session::has('success')): ?>
                                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body"><br>
                                    <div class="img-conatiner-x">
                                        <div class="em-img">
                                            <?php $profile_image = $employer->e_image ? $employer->e_image : '/employer/images/demo.png' ?>
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
                                        <?= csrf_token() ?>
                                    </form>
                                </div>
                            </div>
                        </div><!-- content end-->
                        <div class="account-x accepted-x-body">
                            <div class="accepted-x">
                            <?php if(count($requests)): ?>
                                <h3 class="rh-head">Employer job Offers</h3><br><br>
                            <?php endif; ?>
                                <?php if(count($requests)): 
                            foreach($requests as $request):
                                $profile_image = $request->w_image ? $request->w_image : '/employee/images/demo.png';
                                $amount = !$request->amount_to ? money($request->amount_form) : money($request->amount_form).' - '.money($request->amount_to);
                            ?>
                            <div class="jobs-info accept-x-inner">
                                <img src="<?= asset($profile_image) ?>" alt="">
                                <ul class="ul">
                                    <li>
                                        <h4>
                                            <a href="<?= url('/job-detail.php?wid='.$request->r_worker_id) ?>"><?= ucfirst($request->job_title)?></a> 
                                            <span class="date text-success float-right" style="font-size: 12px;"><i class="fa fa-clock-o text-success" style="font-size: 12px;"></i> <?= date('d M Y', strtotime($request->request_date)) ?></span>
                                        </h4>
                                    </li>
                                    <li>Name: <?= ucfirst($request->first_name).' '.ucfirst($request->last_name) ?></li>
                                    <li>Email: <?= $request->email ?></li>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                        <li>
                                            <?php 
                                            if($request->job_type):
                                                if($request->job_type != 'live in'):
                                                $living = json_decode($request->job_type, true); ?>
                                                    <b>Job Location: </b><?= ucfirst($living['city'])?> | <?= ucfirst($living['state'])?> 
                                                <?php else: ?>
                                                    <?= $request->job_type ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="money-amount">No job type</span>
                                            <?php endif; ?>
                                            | <span class="text-warning money-amount"><?= $amount ?></span>
                                        </li>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <?php if(!$request->is_cancle) :?>
                                            <li><b>Status: </b><span class="<?= $request->is_accept ? 'text-success' : 'text-warning' ?>"><?= $request->is_accept ? 'Accepted' : 'Pending' ?></span></li>
                                            <?php else: ?>
                                            <li><b>Status: </b><span class="text-danger">Cancled</span></li>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <li class="text-right lupe-x">
                                        <div class="drop-down">
                                            <i class="fa fa-ellipsis-h dot-icon"></i>
                                            <ul class="drop-down-ul">
                                                <li><a href="<?= url('/admin-nanny/job-detail.php?rid='.$request->request_id) ?>" class="request_cancle_btn" id="<?= $request->request_id?>">Detail</a></li>
                                                <li><a href="#" class="request_delete_btn" id="<?= $request->request_id?>">Delete offer</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-worker-z">
                                <div class="empty-inner">
                                    <img src="<?= asset('/images/icons/1.svg')?>" alt="">
                                    <h3>No job offers yet!</h3>
                                    <h5>You have no pending job offers!</h5>
                                </div>
                            </div>
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
                       
                        




<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>
<a href="#" id="<?= Input::get('wid') ?>" class="employer_id_input" style="display: none;"></a>




<?php  include('includes/footer.php') ?>







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
//  ADD PROFILE IMAGE
// ============================================
$('.img-conatiner-x').on('change', '.profile_img_input', function(){
    var url = $(".ajax_url_page").attr('href');
    var image = $(".profile_img_input");
    var employer_id = $(".employer_id_input").attr('id');
    $(".e-loader-kamo").show();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('employer_id', employer_id);
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
    var employer_id = $(".employer_id_input").attr('id');

    $.ajax({
        url: url,
        method: "post",
        data: {
            employer_id: employer_id,
            get_employer_img: 'get_employer_img'
        },
        success: function (response){
            $(".img-conatiner-x .em-img").html(response)
        }
    });
}







// ========================================
//     GET ERROR PRELOADER
// ========================================
function img_preloader(string){
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






// ========================================
// DELETE JOB OFFER
// ========================================
$(".request_delete_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var request_id = $(this).attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            request_id: request_id,
            employee_delete_request: 'employee_delete_request'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                location.reload();
            }else if(data.data){
                location.reload();
            }
        }
    });
});













});
</script>                