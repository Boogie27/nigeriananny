<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return view('/admin/login');
}


// =====================================
// CHECK IF EMPLOYEE WAS CLICK
// =====================================
if(!Input::exists('get') || !Input::get('wid'))
{
    return view('/admin-nanny/employees');
}




// ============================================
//  UPDATE EMPLOYEE PROFILE
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
            'phone' => 'required|number:phone',
            'birth_date' => 'required',
            'city' => 'required|min:3|max:50',
            'state' => 'required|min:3|max:50',
            'country' => 'required|min:3|max:50',
            'address' => 'required|min:3|max:100',
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        $my_email = $connection->select('employee')->where('email', Input::get('email'))->where('e_id', Input::get('wid'))->first();
        if(!$my_email)
        {
            $all_email = $connection->select('employee')->where('email', Input::get('email'))->get();           
            if(count($all_email))
            {
                Session::errors('errors', ['email' => '*Email already exists']);
                return back();
            }
        }

        if($validation->passed())
        {
            $create = new DB();
            $create->update('employee', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'email' => Input::get('email'),
                    'phone' => Input::get('phone'),
                    'dob' => Input::get('birth_date'),
                    'city' => Input::get('city'),
                    'state' => Input::get('state'),
                    'country' => Input::get('country'),
                    'address' => Input::get('address'),
                ])->where('e_id', Input::get('wid'))->save();
    
            if($create->passed())
            {
                Session::flash('success', 'Account updated successfully!');
                return back();
            }
        }
    }
}






// ======================================
// GET EMPLOYEE DETAILS
// ======================================
$employee = $connection->select('employee')->leftJoin('workers', 'employee.e_id', '=', 'workers.employee_id')->where('e_id', Input::get('wid'))->first();
if(!$employee)
{
    return view('/admin-nanny/employees');
}






// ======================================
// GET WORKER DETAILS
// ======================================
$worker = $connection->select('workers')->where('employee_id', Input::get('wid'))->first();


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
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Employee infomation</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/employees') ?>">Employees</a></li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="col-lg-12"><!-- content start-->
                        <div class="mobile-alert">
                            <?php if(Session::has('error')): ?>
                                <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                            <?php endif; ?>
                            <?php if(Session::has('success')): ?>
                                <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="account-x">
                            <div class="account-x-body" id="account-x-body"><br>
                                <div class="options-x text-right">
                                    <div class="drop-down">
                                        <i class="fa fa-ellipsis-h dot-icon"></i>
                                        <ul class="drop-down-ul">
                                            <li><a href="mailto:<?= $employee->email ?>">Send mail</a></li>
                                            <li><a href="#" id="<?= Input::get('wid') ?>" class="employee_add_top_btn"><?= $employee->is_top ? 'Remove from top' : 'Add to top' ?></a></li>
                                            <li><a href="#" id="<?= Input::get('wid') ?>" class="employee_feature_btn"><?= $employee->is_feature ? 'Unfeature' : 'Feature' ?></a></li>
                                            <li><a href="#" id="<?= Input::get('wid') ?>" class="employee_deactivate_btn"><?= $employee->e_is_deactivate ? 'Activate' : 'Deactivate' ?></a></li>
                                            <li><a href="#" id="<?= Input::get('wid') ?>" class="employee_approve_btn"><?= $employee->e_approved ? 'Unapprove' : 'Approve' ?></a></li>                                        
                                        </ul>
                                    </div>
                                </div>
                                <div class="img-conatiner-x">
                                    <div class="em-img">
                                        <?php $profile_image = $employee->w_image ? $employee->w_image : '/images/employee/demo.png' ?>
                                        <img src="<?= asset($profile_image) ?>" alt="<?= $employee->first_name ?>" class="acc-img" id="profile_image_img">
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
                                    <div class="approved text-center">
                                        <span class="text-<?= $employee->is_active ? 'success' : 'danger'?>"><?= $employee->is_active ? 'online' : 'offline'?></span>
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
                                                <input type="text" name="first_name" class="form-control h50" value="<?= $employee->first_name ?? old('first_name') ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['last_name'])) : ?>
                                                    <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                <?php endif; ?>
                                                <label for="">Last name:</label>
                                                <input type="text" name="last_name" class="form-control h50" value="<?= $employee->last_name ?? old('last_name') ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['email'])) : ?>
                                                    <div class="text-danger"><?= $errors['email']; ?></div>
                                                <?php endif; ?>
                                                <label for="">Email:</label>
                                                <input type="text" name="email" class="form-control h50" value="<?= $employee->email ?? old('email') ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['phone'])) : ?>
                                                    <div class="text-danger"><?= $errors['phone']; ?></div>
                                                <?php endif; ?>
                                                <label for="">Phone:</label>
                                                <input type="text" name="phone" class="form-control h50" value="<?= $employee->phone ?? old('phone') ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-6 col-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['birth_date'])) : ?>
                                                    <div class="text-danger"><?= $errors['birth_date']; ?></div>
                                                <?php endif; ?>
                                                <label for="">Birth date:</label>
                                                <input type="date" name="birth_date" class="form-control h50" value="<?= date('Y-m-d', strtotime($employee->dob)) ?? date('Y-m-d', strtotime(old('birth_date'))) ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['city'])) : ?>
                                                    <div class="text-danger"><?= $errors['city']; ?></div>
                                                <?php endif; ?>
                                                <label for="">City:</label>
                                                <input type="text" name="city" class="form-control h50" value="<?= $employee->city ?? old('city') ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <?php  if(isset($errors['state'])) : ?>
                                                    <div class="text-danger"><?= $errors['state']; ?></div>
                                                <?php endif; ?>
                                                <label for="">State:</label>
                                                <input type="text" name="state" class="form-control h50" value="<?= $employee->state ?? old('state') ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['country'])) : ?>
                                                    <div class="text-danger"><?= $errors['country']; ?></div>
                                                <?php endif; ?>
                                                <label for="">Country:</label>
                                                <input type="text" name="country" class="form-control h50" value="<?= $employee->country ?? old('country') ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php  if(isset($errors['address'])) : ?>
                                                    <div class="text-danger"><?= $errors['address']; ?></div>
                                                <?php endif; ?>
                                                <label for="">Address:</label>
                                            <textarea name="address" cols="30" rows="3" class="form-control h50" placeholder="Write something..."><?= $employee->address ?? old('address') ?></textarea>
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

                             <!-- inner content start -->
                             <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">Job title</h5>
                                   </div>
                                   <?php if(!$worker->job_title): ?>
                                   <ul>
                                       <li><p class="inner-p">Select form one of the job categories listed bellow</p></li>
                                       <li class="text-right"><a href="#" id="employee_add_job_title" class="text-primary">Update</a></li>
                                   </ul>
                                    <?php else: ?>
                                   <div class="inner-body">
                                        <p class="inner-p"><?= ucfirst($worker->job_title) ?></p>
                                       <ul>
                                           <li class="text-right"><a href="#"  id="employee_add_job_title" class="text-primary">Update</a></li>
                                       </ul>
                                   </div>
                                    <?php endif; ?>
                                    <div class="col-lg-12 expand" id="employee_job_title_form" style="display: none;">
                                        <div class="form-group">
                                            <div class="all_alert alert_title text-danger"></div>
                                            <div class="ui_kit_select_box manipulated">
                                            <?php $categories = $connection->select('job_categories')->where('is_category_featured', 1)->get();  
                                            if(count($categories)): ?>
                                                <select id="input_employee_job_title" class="selectpicker custom-select-lg mb-3">
                                                <option value="">Select job title</option>
                                                <?php  foreach($categories as $category): ?>
                                                    <option value="<?= $category->job_category_id ?>" <?= $worker->job_category_id == $category->job_category_id ? 'selected' : '';?>><?= $category->category_name ?></option>
                                                <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                       <div class="col-lg-12">
                                            <div class="form-group text-right">
                                                <a href="#" class="text-primary text-danger" id="cancle_employee_job_title">Cancle</a>
                                                <a href="#" class="text-primary" id="update_employee_job_title">Update</a>
                                            </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                            <!-- inner content end -->


                              <!-- inner content start -->
                              <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">About me</h5>
                                   </div>
                                   <?php if(!$worker->bio): ?>
                                   <ul>
                                       <li><p class="inner-p">Give a short overview of your career history and skills</p></li>
                                       <li class="text-right"><a href="<?= url('/admin-nanny/employee-update.php?page=about&wid='.$worker->employee_id) ?>" class="text-primary">Add</a></li>
                                   </ul>
                                    <?php else: ?>
                                   <div class="inner-body">
                                        <p class="inner-p"><?= ucfirst($worker->bio) ?></p>
                                       <ul>
                                           <li class="text-right"><a href="<?= url('/admin-nanny/employee-update.php?page=about&wid='.$worker->employee_id) ?>" class="text-primary">Update</a></li>
                                       </ul>
                                   </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- inner content end -->

                           

                              <!-- inner content start -->
                              <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">Education</h5>
                                   </div>
                                    <?php if(!$worker->education): ?>
                                    <ul>
                                        <li><p class="inner-p">List your qualification here</p></li>
                                        <li class="text-right"><a href="<?= url('/admin-nanny/employee-update.php?page=education&wid='.$worker->employee_id) ?>"  class="text-primary">Add</a></li>
                                    </ul>
                                    <?php else: ?>
                                   <div class="inner-body">
                                        <?php  $education = json_decode($worker->education, true); ?>
                                       <ul class="inner_ul">
                                            <li><b>Qualification: </b><?= $education['qualification']?></li>
                                            <li><b>Institution: </b><?= $education['institution']?></li>
                                            <li><b>City: </b><?= $education['city']?></li>
                                            <li><b>State: </b><?= $education['state']?></li>
                                            <li><b>Country: </b><?= $education['country']?></li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6"><b>Start date: </b><?= $education['start_date'] ?></div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <?php if(!$education['inview']): ?>
                                                            <b>End date: </b><?= $education['end_date'] ?>
                                                        <?php else: ?>
                                                        <b>End date: </b><span class="inview-x">inview</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </li>
                                           <li class="text-right li-link">
                                               <a href="<?= url('/admin-nanny/employee-update.php?page=edu_edit&&wid='.$worker->employee_id) ?>" class="text-primary">Edit</a>
                                               <a href="#" data-toggle="modal"  data-target="#employee_delete_education_btn" class="text-danger">Delete</a>
                                           </li>
                                       </ul>
                                   </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- inner content end -->

                            <!-- inner content start -->
                            <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">Work experience</h5>
                                   </div>
                                   <?php if(!$worker->work_experience): ?>
                                   <ul>
                                       <li><p class="inner-p">Add your work experience such as internship, part-time work or full time work</p></li>
                                       <li class="text-right"><a href="<?= url('/admin-nanny/employee-update.php?page=work&wid='.$worker->employee_id) ?>" class="text-primary">Add</a></li>
                                   </ul> 
                                    <?php else: ?>
                                   <div class="inner-body">
                                       <?php  $experience = json_decode($worker->work_experience, true); ?>
                                       <ul class="inner_ul">
                                            <li><b>Job title:</b> <?= ucfirst($experience['job_title']) ?></li>
                                            <li><b>Job function:</b> <?= $experience['job_function'] ?></li>
                                            <li><b>Employer:</b> <?= $experience['employer_name'] ?></li>
                                            <li><b>Employer phone:</b> <?= $experience['employer_phone'] ?></li>
                                            <li><b>Employer email:</b> <?= $experience['employer_email'] ?></li>
                                            <li><b>Description:</b> <p class="inner-p"><?= $experience['description'] ?></p></li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6"><b>Start date: </b><?= $experience['start_date'] ?></div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <?php if(!$experience['inview']): ?>
                                                            <b>End date: <?= $experience['end_date'] ?></b>
                                                        <?php else: ?>
                                                            <b>End date: </b><span class="inview-x">inview</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </li>
                                           <li class="text-right li-link">
                                               <a href="<?= url('/admin-nanny/employee-update.php?page=edit_work&wid='.$worker->employee_id) ?>" class="text-primary">Edit</a>
                                               <a href="#" data-toggle="modal"  data-target="#employee_delete_work_btn" class="text-danger">Delete</a>
                                           </li>
                                       </ul>
                                   </div>
                                   <?php endif; ?>
                                </div>
                            </div>
                            <!-- inner content end -->

                            <!-- inner content start -->
                            <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">Reading</h5>
                                   </div>
                                   <ul class="inner_ul_2">
                                       <li><p class="inner-p">Do you read in english?</p></li>
                                       <li>
                                           <form action="<?= current_url() ?>" method="POST">
                                               <div class="form-c">
                                                   <input type="checkbox" class="employee_reading_check" value="true" <?= $worker->reading === '1' ? 'checked' : '';?>> <label for="">YES</label>
                                               </div>
                                               <div class="form-c">
                                                   <input type="checkbox" class="employee_reading_check" value="false" <?= $worker->reading === '0' ? 'checked' : '';?>> <label for="">NO</label>
                                               </div>
                                           </form>
                                       </li>
                                   </ul>
                                </div>
                            </div>
                            <!-- inner content end -->

                             <!-- inner content start -->
                             <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">Writing</h5>
                                   </div>
                                   <ul class="inner_ul_2">
                                       <li><p class="inner-p">Do you write in english?</p></li>
                                       <li>
                                           <form action="<?= current_url() ?>" method="POST">
                                               <div class="form-c">
                                                   <input type="checkbox" class="employee_writing_check" value="true" <?= $worker->writing === '1' ? 'checked' : '';?>> <label for="">YES</label>
                                               </div>
                                               <div class="form-c">
                                                   <input type="checkbox" class="employee_writing_check" value="false" <?= $worker->writing === '0' ? 'checked' : '';?>> <label for="">NO</label>
                                               </div>
                                           </form>
                                       </li>
                                   </ul>
                                </div>
                            </div>
                            <!-- inner content end -->

                            <!-- inner content start -->
                            <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">Job type</h5>
                                   </div>
                                   <ul class="inner_ul_2">
                                       <li><p class="inner-p">Identify if you would like to live in or out</p></li>
                                        <?php if($worker->job_type && $worker->job_type != 'live in'):
                                         $living = json_decode($worker->job_type, true); ?>
                                            <ul class="inner_ul" id="liveout_container_x">
                                                <li><b>Location: </b><?= ucfirst($living['city'])?> | <?= ucfirst($living['state'])?></li>
                                                <li class="text-right"><a href="#" class="text-primary" id="employee_living_open_edit_check">Update</a></li>
                                            </ul>
                                        <?php endif; ?>
                                      
                                       <li>
                                           <form action="" method="POST">
                                               <div class="form-c">
                                                   <input type="checkbox" class="employee_living_check" value="in" <?= $worker->job_type == 'live in' ? 'checked' : '';?>> <label for="">Live in</label>
                                               </div>
                                               <div class="form-c">
                                                   <input type="checkbox" class="employee_living_check" value="out" <?= isset($living['liveout']) ? 'checked' : '';?>> <label for="">Live out</label>
                                               </div>
                                                <div id="employee_living_input" style="display: none;">
                                                        <label for=""><b>Select live out location</b></label>
                                                       <div class="row">
                                                       <?php $living = $worker->job_type ? json_decode($worker->job_type, true) : null ?>
                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="form-group">
                                                                    <div class="all_alert alert_0 text-danger"></div>
                                                                    <label for="">City:</label>
                                                                    <input type="text" id="liveout_city_input" class="form-control h50" value="<?= isset($living['city']) ? $living['city'] : null ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="form-group">
                                                                    <div class="all_alert alert_1 text-danger"></div>
                                                                    <label for="">State:</label>
                                                                    <input type="text" id="liveout_state_input" class="form-control h50" value="<?= isset($living['state']) ? $living['state'] : null ?>">
                                                                </div>
                                                            </div>
                                                       </div>
                                                    <div class="text-right">
                                                        <a href="#" id="close_living_type_btn" class="text-danger mr-2">close</a>
                                                        <a href="#" id="living_type_btn" class="text-primary">Update</a>
                                                    </div>
                                              </div>
                                           </form>
                                        </li>
                                   </ul>
                                </div>
                            </div>
                            <!-- inner content end -->

                             <!-- inner content start -->
                             <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">Salary</h5>
                                   </div>
                                   <div class="inner-body">
                                       <ul class="inner_ul">
                                            <li><p class="inner-p">Specify your prefered salaray</p></li>
                                            <?php if($worker->amount_form): 
                                            $amount = $worker->amount_to ? money($worker->amount_form).' - '.money($worker->amount_to) : money($worker->amount_form);
                                            ?>
                                                <li><b>From:</b> <?= $amount ?></li>
                                            <?php endif; ?>
                                       </ul>
                                   </div>
                                  
                                   <ul class="inner_ul_2">
                                       <li  id="employee_add_amount_form" style="display: none;">
                                           <form action="<?= current_url() ?>" method="POST">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_2 text-danger"></div>
                                                            <label for="">From</label>
                                                            <input type="number" min="1" id="employee_amount_from" class="form-control h50" value="<?= $worker->amount_form ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_3 text-danger"></div>
                                                            <label for="">From</label>
                                                            <input type="number" min="1" id="employee_amount_to" class="form-control h50" value="<?= $worker->amount_to ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                           </form>
                                       </li>
                                       <li class="text-right"> 
                                            <a href="#" id="employee_close_amount_btn" class="text-secondary mr-2" style="display: none;">Close</a>
                                            <a href="#" id="employee_open_amount_btn" class="text-primary mr-2">Update</a>
                                           <a href="#" id="employee_add_amount_btn" class="text-primary mr-2" style="display: none;">Update</a>
                                        </li>
                                   </ul>
                            
                                </div>
                            </div>
                            <!-- inner content end -->

                              <!-- inner content start -->
                              <div class="account-h">
                                <div class="inner-content-x">
                                   <div class="inner-h">
                                        <h5 class="">My CV</h5>
                                        <p class="inner-p">Upload cv type: pdf, docx</p>
                                   </div>
                                   <?php if(!$worker->cv): ?>
                                        <ul>
                                            <li style="border-bottom: 1px solid #ccc;">
                                                <p class="inner-p pb-2">Quickly upload your cv here</p>
                                            </li>
                                        </ul>
                                    <?php else: ?>
                                   <div class="inner-body">
                                   <?php  $store = json_decode($worker->cv, true); ?>
                                       <ul class="inner_ul">
                                           <li><b class="text-success"><?= $store['name'] ?></b></li>
                                           <li>Uploaded on <?= $store['date']?>  <span class="float-right"><a href="<?= url($store['cv'])?>" class="text-success">Download cv</a></span></li>
                                       </ul>
                                   </div>
                                   <?php endif; ?>
                                   <ul>
                                       <li class="text-right li-link">
                                            <span class="float-left text-danger" id="alert_cv" style="font-size: 13px;"></span>
                                            <a href="#" class="employee_upload_cv text-primary mr-2">Upload</a>
                                            <?php if($worker->cv): ?>
                                                <a href="#" id="employee_delete_cv_btn" class="text-danger">Delete</a>
                                            <?php endif; ?>
                                        </li>
                                   </ul>
                                    <form action="">
                                        <input type="file" id="employee_upload_cv_input" style="display: none;">
                                    </form>
                                </div>
                            </div>
                            <!-- inner content end -->

                        </div><!-- content end-->

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
                       
                        






<!-- Modal delete education -->
<div class="sign_up_modal modal fade" id="employee_delete_education_btn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_cancle_request_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST">
                            <div class="heading">
                                <div class="alert-delete-review text-danger text-center"></div>
                                <p class="text-center">Do you wish to delete education?</p>
                            </div>
                            <button type="submit"  name="subscribe" class="subcribe_now_btn" style="display: none;"></button>
                            <button type="submit" class="btn btn-log btn-block bg-danger" id="delete_education_modal_btn" style="color: #fff">Delete education</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>









<!-- Modal delete work -->
<div class="sign_up_modal modal fade" id="employee_delete_work_btn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_cancle_request_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="POST">
                            <div class="heading">
                                <div class="alert-delete-review text-danger text-center"></div>
                                <p class="text-center">Do you wish to delete work experience?</p>
                            </div>
                            <button type="submit"  name="subscribe" class="subcribe_now_btn" style="display: none;"></button>
                            <button type="submit" class="btn btn-log btn-block bg-danger" id="delete_experience_btn" style="color: #fff">Delete work experience</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>
<a href="#" id="<?= Input::get('wid') ?>" class="employee_id_input" style="display: none;"></a>




<?php  include('includes/footer.php') ?>









<script>
$(document).ready(function(){

// ===========================================
// FEATURE EMPLOYEE
// ===========================================
$(".employee_feature_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(this).attr('id');
    $(".preloader-container").show() //show preloader
    $(".page_alert_danger").hide();

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            update_employee_feature: 'update_employee_feature'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
    });
    
});






// ==========================================
// EMPLOYEE DEACTIVATE BUTTON
// ==========================================
$('.employee_deactivate_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $(this).attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			employee_id: id,
			is_employee_deactivate: 'is_employee_deactivate'
		},
		success: function(response){
            var data = JSON.parse(response);
            location.reload();
		},
        error: function(){
            remove_preloader();
            $('.page_alert_danger').show();
            $('.page_alert_danger').html('*Network error, try again later');
        }
	});
});

















// ===========================================
// EMPLOYEE ADD TO TOP
// ===========================================
$(".employee_add_top_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(this).attr('id');
    $(".preloader-container").show() //show preloader
    $(".page_alert_danger").hide();

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            update_employee_top: 'update_employee_top'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }
            remove_dark_preloader();
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
    });
    
});










// ===========================================
// EMPLOYEE ADD TO TOP
// ===========================================
$(".employee_approve_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(this).attr('id');
    $(".preloader-container").show() //show preloader
    $(".page_alert_danger").hide();

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            update_employee_approve: 'update_employee_approve'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }
            remove_dark_preloader();
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
    });
    
});








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
    var employee_id = $(".employee_id_input").attr('id');
    $(".e-loader-kamo").show();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('employee_id', employee_id);
    data.append('upload_employee_image', true);

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
    var employee_id = $(".employee_id_input").attr('id');

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            get_employee_img: 'get_employee_img'
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






// =========================================
// OPEN TITLE FORM
// =========================================
$("#employee_add_job_title").click(function(e){
    e.preventDefault();
    $(this).hide();
    $("#employee_job_title_form").show();
});






// ================================
// REMOVE PRELOADER
// ================================
function remove_dark_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
    }, 1000);
}






// ==========================================
// UPDATE JOB TITLE
// ==========================================
$("#update_employee_job_title").click(function(e){
    e.preventDefault();
    $(".alert_title").html('');
    var job_title = $("#input_employee_job_title").val();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            job_title: job_title,
            employee_id: employee_id,
            update_employee_job_title: 'update_employee_job_title'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                $(".alert_title").html(data.error.job_title);
            }else if(data.data){
                location.reload();
            }else{
                location.reload();
            }
            remove_dark_preloader();
        }
    });

});




// ========================================
// JOB TITLE CLOSE
// ========================================
$("#cancle_employee_job_title").click(function(e){
    e.preventDefault();
    $("#employee_job_title_form").hide();
    $("#employee_add_job_title").show();
});








// =========================================
// GET READING ABILITY
// =========================================
var reading = $(".employee_reading_check");
$.each($(".employee_reading_check"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < reading.length; i++){
            if(index != i)
            {
               $($(reading)[i]).prop('checked', false);
            }else{
                $($(reading)[i]).prop('checked', true);
            }
        }
    });
});




// ==========================================
// READING ABILITY
// ==========================================
$(reading).click(function(){
    var read_value = $(this).val();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id');
   $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            reading: read_value,
            employee_reading_ability: 'employee_reading_ability'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(!data.data){
                location.reload();  
            }
            remove_dark_preloader();
        }
    });
});







// =========================================
// GET WRITING ABOLITY
// =========================================
var writing = $(".employee_writing_check");
$.each($(".employee_writing_check"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < writing.length; i++){
            if(index != i)
            {
               $($(writing)[i]).prop('checked', false);
            }else{
                $($(writing)[i]).prop('checked', true);
            }
        }
    });
});

// ==========================================
// WRITING ABILITY
// ==========================================
$(writing).click(function(){
    var writing = $(this).val();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            writing: writing,
            employee_id: employee_id,
            employee_writing_ability: 'employee_writing_ability'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(!data.data){
                location.reload();  
            }
            remove_dark_preloader();
        }
    });
});









// =========================================
// GET LIVE IN OR OUT
// =========================================
var living = $(".employee_living_check");
$.each($(".employee_living_check"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < living.length; i++){
            if(index != i)
            {
               $($(living)[i]).prop('checked', false);
            }else{
                $($(living)[i]).prop('checked', true);
            }
        }
    });
});


$(living).click(function(){
    if($(this).val() == 'out')
    {
        $("#employee_living_input").show();
    }else{
        live_in();
        $("#employee_living_input").hide();
    }
});








// ========================================
// EMPLOYEE OPEN LIVIN LIVEOUT EDIT FORM
// ========================================
$("#employee_living_open_edit_check").click(function(e){
    e.preventDefault();
    $("#employee_living_input").show();
});




// =========================================
// EMPLOYEE CLOSE LIVING FORM
// =========================================
$("#close_living_type_btn").click(function(e){
    e.preventDefault();
    $("#employee_living_input").hide();
});


// =========================================
// EMPLOYEE LIVE IN
// =========================================
function live_in(){
    var url = $(".ajax_url_page").attr('href');
    $("#liveout_container_x").hide();
    var employee_id = $(".employee_id_input").attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            employee_live_in: 'employee_live_in'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(!data.data){
                location.reload();  
            }
            remove_dark_preloader();
        }
    });
}







// ==========================================
// LIVE OUT
// ==========================================
$("#living_type_btn").click(function(e){
    e.preventDefault();
    $(".all_alert").html('');
    var url = $(".ajax_url_page").attr('href');
    var city = $("#liveout_city_input").val();
    var state = $("#liveout_state_input").val();
    var employee_id = $(".employee_id_input").attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            city: city,
            state: state,
            employee_id: employee_id,
            employee_live_out: 'employee_live_out'
        },
        success: function (response){
            var data = JSON.parse(response);
           if(data.error){
            get_error(data.error);
           }else if(data.data){
            location.reload();
           }
            remove_dark_preloader();
        }
    });
});




// ==========================================
// GET ERROR MESSAGE
// ==========================================
function get_error(error){
    setTimeout(function(){
        $(".alert_0").html(error.city);
        $(".alert_1").html(error.state);
        $(".alert_2").html(error.amount_from);
        $(".alert_3").html(error.amount_to);
        $(".alert_4").html(error.image);
    }, 200);
}








// ========================================
// OPEN ADD AMOUNT FORM
// ========================================
$("#employee_open_amount_btn").click(function(e){
    e.preventDefault();
    $(this).hide();
    $("#employee_add_amount_form").show();
    $("#employee_close_amount_btn").show();
    $("#employee_add_amount_btn").show();
});



// ========================================
// CLOSE ADD AMOUNT FORM
// ========================================
$("#employee_close_amount_btn").click(function(e){
    e.preventDefault();
    $(this).hide();
    $("#employee_add_amount_form").hide();
    $("#employee_open_amount_btn").show();
    $("#employee_add_amount_btn").hide();
});







// =========================================
// EMPLOYEE PAYMENT AMOUNT
// =========================================
$("#employee_add_amount_btn").click(function(e){
    e.preventDefault();
    $(".all_alert").html('');
    var url = $(".ajax_url_page").attr('href');
    var amount_from = $("#employee_amount_from").val();
    var amount_to = $("#employee_amount_to").val();
    var employee_id = $(".employee_id_input").attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            amount_from: amount_from,
            amount_to: amount_to,
            employee_payment_amounts: 'employee_payment_amounts'
        },
        success: function (response){
            var data = JSON.parse(response);
           if(data.error){
            get_error(data.error);
           }else if(data.data){
            location.reload();
           }
            remove_dark_preloader();
        }
    });
});












// ============================================
// OPEN UPLOAD CV WINDOW
// ===========================================
$(".employee_upload_cv").click(function(e){
    e.preventDefault();
    $("#alert_cv").html('');
    $("#employee_upload_cv_input").click();
});



// ====================================
// UPLOAD CV
// ====================================
$("#employee_upload_cv_input").on('change', function(){
    var image = $("#employee_upload_cv_input");
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id');
    $(".preloader-container").show() //show preloader

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('employee_id', employee_id);
    data.append('upload_employee_cv', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                $("#alert_cv").html(data.error.image);
            }else if(data.data){
               location.reload();
            }
            remove_dark_preloader();
        }
    });
});





// ===========================================
// DELETE EXPERIENCE
// ===========================================
$("#delete_experience_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id');

    $(".preloader-container").show() //show preloader

     $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            delete_experience_action: 'delete_experience_action'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                location.reload();
            }else if(data.url){
                location.assign(data.url);
            }else{
                location.reload();
            }
            remove_dark_preloader();
        }
    });
});




// ====================================
// DELETE CV
// ====================================
$("#employee_delete_cv_btn").click(function(e){
    e.preventDefault();
    $("#alert_cv").html('');
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            delete_employee_cv: 'delete_employee_cv' 
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                $("#alert_cv").html(data.error.image);
            }else if(data.data){
               location.reload();
            }else{
                location.reload();
            }
            remove_dark_preloader();
        }
    });
});










// ===================================
// DELETE EDUCATION 
// ===================================
$("#delete_education_modal_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id');

    $(".close_cancle_request_btn").click()
    $(".preloader-container").show() //show preloader

     $.ajax({
        url: url,
        method: "post",
        data: {
            employee_id: employee_id,
            delete_education_action: 'delete_education_action'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.url){
                location.assign(data.url);
            }else{
                location.reload();
            }
            remove_dark_preloader();
        }
    });
});




// end
});
</script>

                        