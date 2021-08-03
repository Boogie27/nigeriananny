<?php include('../Connection.php');  ?>
<?php
if(!Auth_employee::is_loggedin())
{
    return view('/employee/login');
}





// ============================================
//  UPDATE EMPLOYEE ABOUT
// ============================================
if(Input::post('update_profile'))
{
    if(Token::check())
    {
        $validate = new DB();
        $validation = $validate->validate([
            'about' => 'required|min:3|max:5000',
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        if($validation->passed())
        {
            $update = $connection->update('workers', [
                'bio' => Input::get('about')
            ])->where('employee_id', Auth_employee::employee('id'))->save();
            if($update)
            {
                Session::flash('success', 'About updated successufully!');
                Session::flash('success-m', 'About updated successufully!');
            }
        }
    }
    return view('/employee/account');
}






// ======================================
// GET EMPLOYEE DETAILS
// ======================================
$employee = $connection->select('employee')->where('e_id', Auth_employee::employee('id'))->where('email', Auth_employee::employee('email'))->where('e_is_deactivate', 0)->first();
if(!$employee)
{
    Session::put('old_url', '/employee/account');
    Session::delete('employee');
    return view('/employee/login');
}




// ======================================
// GET WORKER DETAILS
// ======================================
$worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();





// =====================================
// GET EDUCATION EDIT
// =====================================
if(Input::exists('get') && Input::get('page') == 'edu_edit' && Input::get('eid'))
{
    if($worker->employee_id != Input::get('eid'))
    {
        return view('/employee/account');
    }
}



// =====================================
// GET EXPERIENCE EDIT
// =====================================
if(Input::exists('get') && Input::get('page') == 'edit_work' && Input::get('eid'))
{
    if($worker->employee_id != Input::get('eid'))
    {
        return view('/employee/account');
    }
}


?>



<?php include('../includes/header.php');  ?>


<!-- top navigation-->
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
                                            <?php $profile_image = $employee->w_image ? $employee->w_image : '/employee/images/demo.png' ?>
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
                                        <div class="dob text-center text-success" style="font-size: 12px;"><span>Joined: </span><?= date('d M Y', strtotime($employee->date_joined)) ?></div>
                                        <ul class="anchor-acc">
                                            <li><a href="<?= url('/employee/account') ?>">Account</a></li>
                                            <li><a href="<?= url('/employee/job-offer') ?>">Job offers</a></li>
                                            <li><a href="<?= url('/employee/accepted')?>">Accepted offers</a></li>
                                            <li><a href="<?= url('/employee/job-history')?>">Offer history</a></li>
                                            <li><a href="<?= url('/employee/change-password')?>">Change password</a></li>
                                            <li><a href="<?= url('/employee/logout')?>">Logout</a></li>
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

                            <?php if(Input::exists('get') && Input::get('page') == 'about'): ?>
                            <!-- about start -->
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body">
                                    <h3 class="rh-head">About information</h3><br><br>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-6">
                                                <div class="form-group">
                                                    <?php  if(isset($errors['about'])) : ?>
                                                        <div class="text-danger"><?= $errors['about']; ?></div>
                                                    <?php endif; ?>
                                                    <label for="">About:</label>
                                                     <textarea name="about" class="form-control h50" cols="30" rows="10" placeholder="Write something about your self"><?= $worker->bio ?? old('about') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group text-right">
                                                    <a href="<?= url('/employee/account') ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <button type="submit" name="update_profile" class="btn view-btn-fill">UPDATE...</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?= csrf_token() ?>
                                    </form>
                                </div>
                            </div>
                            <!-- about end -->
                            <?php endif; ?>


                            <?php if(Input::exists('get') && Input::get('page') == 'education'): ?>
                            <!-- education start -->
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body">
                                    <h3 class="rh-head">Education information</h3><br><br>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12">
                                                <div class="form-group">
                                                    <div class="all_alert alert_0 text-danger"></div>
                                                    <label for="">Qualification:</label>
                                                    <input type="text" id="input_qualification" class="form-control h50" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_1 text-danger"></div>
                                                    <label for="">Institution:</label>
                                                    <input type="text" id="input_institution" class="form-control h50" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_2 text-danger"></div>
                                                    <label for="">City:</label>
                                                    <input type="text" id="input_city_institution" class="form-control h50" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_3 text-danger"></div>
                                                    <label for="">State:</label>
                                                    <input type="text" id="input_state_institution" class="form-control h50" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_4 text-danger"></div>
                                                    <label for="">Country:</label>
                                                    <input type="text" id="input_country_institution" class="form-control h50" value="">
                                                </div>
                                            </div>

                                            <div class="col-lg-12 ">
                                                <label for="">Start date:</label>
                                                <div class="row expand-parent">
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_5 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_start_day_institution" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Day</option>
                                                                    <?php for($i = 1; $i <= 31; $i++): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_6 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_start_month_institution" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Month</option>
                                                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                        <div class="all_alert alert_7 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_start_year_institution" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Year</option>
                                                                    <?php for($i = date('Y'); $i >= 1960; $i--): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                             <div class="col-lg-12" id="end_date_container" style="display: none">
                                                <label for="">End date:</label>
                                                <div class="row expand-parent">
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                           <div class="all_alert alert_8 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_end_day_institution" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Day</option>
                                                                    <?php for($i = 1; $i <= 31; $i++): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_9 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_end_month_institution" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Month</option>
                                                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_10 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_end_year_institution" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Year</option>
                                                                    <?php for($i = date('Y'); $i >= 1960; $i--): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-c">
                                                   <input type="checkbox" id="input_present_institution" class="presently_study_input" value="true" checked> <label for="">presently studying</label>
                                               </div>
                                            </div>
                                           
                                            <div class="col-lg-12">
                                                <div class="form-group text-right">
                                                    <a href="<?= url('/employee/account') ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <button type="button" id="update_education_btn" class="btn view-btn-fill">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- education end -->
                            <?php endif; ?>


                            <?php if(Input::exists('get') && Input::get('page') == 'edu_edit' && Input::get('eid')): 
                            $old_edu = json_decode($worker->education, true);  ?>
                            <!-- edit education start -->
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body">
                                    <h3 class="rh-head">Edit Education information</h3><br><br>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12">
                                                <div class="form-group">
                                                    <div class="all_alert alert_0 text-danger"></div>
                                                    <label for="">Qualification:</label>
                                                    <input type="text" id="input_qualification" class="form-control h50" value="<?= $old_edu['qualification'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_1 text-danger"></div>
                                                    <label for="">Institution:</label>
                                                    <input type="text" id="input_institution" class="form-control h50" value="<?= $old_edu['institution'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_2 text-danger"></div>
                                                    <label for="">City:</label>
                                                    <input type="text" id="input_city_institution" class="form-control h50" value="<?= $old_edu['city'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_3 text-danger"></div>
                                                    <label for="">State:</label>
                                                    <input type="text" id="input_state_institution" class="form-control h50" value="<?= $old_edu['state'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_4 text-danger"></div>
                                                    <label for="">Country:</label>
                                                    <input type="text" id="input_country_institution" class="form-control h50" value="<?= $old_edu['country'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-lg-12 ">
                                                <label for="">Start date:</label>
                                                <div class="row expand-parent">
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_5 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_start_day_institution" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Day</option>
                                                                    <?php for($i = 1; $i <= 31; $i++): ?>
                                                                    <option value="<?= $i?>" <?= $old_edu['s_day'] == $i ? 'selected' : '' ?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_6 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_start_month_institution" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Month</option>
                                                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= $i?>" <?= $old_edu['s_month'] == $i ? 'selected' : '' ?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                        <div class="all_alert alert_7 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_start_year_institution" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Year</option>
                                                                    <?php for($i = date('Y'); $i >= 1960; $i--): ?>
                                                                    <option value="<?= $i?>" <?= $old_edu['s_year'] == $i ? 'selected' : '' ?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                             <div class="col-lg-12" id="end_date_container" style="display: <?= $old_edu['inview'] ? 'none' : 'block'?>">
                                                <label for="">End date:</label>
                                                <div class="row expand-parent">
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                           <div class="all_alert alert_8 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_end_day_institution" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Day</option>
                                                                    <?php for($i = 1; $i <= 31; $i++): ?>
                                                                    <option value="<?= $i?>" <?= $old_edu['e_day'] == $i ? 'selected' : '' ?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_9 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_end_month_institution" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Month</option>
                                                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= $i?>" <?= $old_edu['e_month'] == $i ? 'selected' : '' ?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_10 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_end_year_institution" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Year</option>
                                                                    <?php for($i = date('Y'); $i >= 1960; $i--): ?>
                                                                    <option value="<?= $i?>" <?= $old_edu['e_year'] == $i ? 'selected' : '' ?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-c">
                                                   <input type="checkbox" id="input_present_institution" class="presently_study_input" value="<?= $old_edu['inview'] ? 'true' : 'false'?>" <?= $old_edu['inview'] ? 'checked' : ''?>> <label for="">presently studying</label>
                                               </div>
                                            </div>
                                           
                                            <div class="col-lg-12">
                                                <div class="form-group text-right">
                                                    <a href="<?= url('/employee/account') ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <input type="hidden" id="education_key_input" value="<?= Input::get('eid')?>">
                                                    <button type="button" id="update_education_edit_btn" class="btn view-btn-fill">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- edit education end -->
                            <?php endif; ?>


                            <?php if(Input::exists('get') && Input::get('page') == 'edu_delete' && Input::get('eid')): 
                            $education = json_decode($worker->education, true) ?>
                            <!-- delete education start -->
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body">
                                    <h3 class="rh-head">Delete Education information</h3><br><br>
                                    <div class="inner-body">
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
                                       </ul>
                                       <ul>
                                           <li class="text-right"> 
                                                <a href="<?= url('/employee/account') ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> back</a>
                                                <a href="#" id="<?= Input::get('eid') ?>" class="delete_education_btn text-danger">Delete</a>
                                            </li>
                                       </ul>
                                   </div>
                                </div>
                            </div>
                            <!-- delete education end -->
                            <?php endif; ?>



                             <?php if(Input::exists('get') && Input::get('page') == 'work'): ?>
                            <!-- add work start -->
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body">
                                    <h3 class="rh-head">Experience information</h3><br><br>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12">
                                                <div class="form-group">
                                                    <div class="all_alert alert_0 text-danger"></div>
                                                    <label for="">Job title:</label>
                                                    <input type="text" id="input_job_title" class="form-control h50" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_1 text-danger"></div>
                                                    <label for="">Job functions:</label>
                                                    <input type="text" id="input_job_function" class="form-control h50" value="" placeholder="Example: cooking, cleaning, driving">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_2 text-danger"></div>
                                                    <label for="">Employer name:</label>
                                                    <input type="email" id="input_employer_name" class="form-control h50" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_3 text-danger"></div>
                                                    <label for="">Employer email:</label>
                                                    <input type="email" id="input_employer_email" class="form-control h50" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_4 text-danger"></div>
                                                    <label for="">Employee phone:</label>
                                                    <input type="text" id="input_employer_phone" class="form-control h50" value="">
                                                </div>
                                            </div>
                                           
                                            <div class="col-lg-12 ">
                                                <label for="">Start date:</label>
                                                <div class="row expand-parent">
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_5 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_start_day" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Day</option>
                                                                    <?php for($i = 1; $i <= 31; $i++): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_6 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_start_month" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Month</option>
                                                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                        <div class="all_alert alert_7 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_start_year" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Year</option>
                                                                    <?php for($i = date('Y'); $i >= 1960; $i--): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                             <div class="col-lg-12" id="end_date_container" style="display: none;">
                                                <label for="">End date:</label>
                                                <div class="row expand-parent">
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                           <div class="all_alert alert_8 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_end_day" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Day</option>
                                                                    <?php for($i = 1; $i <= 31; $i++): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_9 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_end_month" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Month</option>
                                                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_10 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_end_year" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Year</option>
                                                                    <?php for($i = date('Y'); $i >= 1960; $i--): ?>
                                                                    <option value="<?= $i?>"><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-c">
                                                   <input type="checkbox" id="input_present_institution" class="presently_study_input" value="true" checked> <label for="">presently working</label>
                                               </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="all_alert alert_11 text-danger"></div>
                                                    <label for="">Description:</label>
                                                  <textarea  id="input_job_description"  class="form-control h50" cols="30" rows="5" placeholder="Write something about the job and the experience you had..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group text-right">
                                                    <a href="<?= url('/employee/account') ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <button type="button" id="update_job_info_btn" class="btn view-btn-fill">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- add work end -->
                            <?php endif; ?>




                            <?php if(Input::exists('get') && Input::get('page') == 'edit_work' && Input::get('eid')): 
                            $experience = json_decode($worker->work_experience, true);    
                            ?>
                            <!-- edit work start -->
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body">
                                    <h3 class="rh-head">Edit Experience information</h3><br><br>
                                    <form action="<?= current_url()?>" method="POST" class="account-profile-form">
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12">
                                                <div class="form-group">
                                                    <div class="all_alert alert_0 text-danger"></div>
                                                    <label for="">Job title:</label>
                                                    <input type="text" id="input_job_title" class="form-control h50" value="<?= $experience['job_title']?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_1 text-danger"></div>
                                                    <label for="">Job functions:</label>
                                                    <input type="text" id="input_job_function" class="form-control h50" value="<?= $experience['job_function']?>" placeholder="Example: cooking, cleaning, driving">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_2 text-danger"></div>
                                                    <label for="">Employer name:</label>
                                                    <input type="email" id="input_employer_name" class="form-control h50" value="<?= $experience['employer_name']?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_3 text-danger"></div>
                                                    <label for="">Employer email:</label>
                                                    <input type="email" id="input_employer_email" class="form-control h50" value="<?= $experience['employer_email']?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="all_alert alert_4 text-danger"></div>
                                                    <label for="">Employee phone:</label>
                                                    <input type="text" id="input_employer_phone" class="form-control h50" value="<?= $experience['employer_phone']?>">
                                                </div>
                                            </div>
                                           
                                            <div class="col-lg-12 ">
                                                <label for="">Start date:</label>
                                                <div class="row expand-parent">
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_5 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_start_day" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Day</option>
                                                                    <?php for($i = 1; $i <= 31; $i++): ?>
                                                                    <option value="<?= $i?>" <?= $experience['s_day'] == $i ? 'selected' : ''?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_6 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_start_month" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Month</option>
                                                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= $i?>" <?= $experience['s_month'] == $i ? 'selected' : ''?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                        <div class="all_alert alert_7 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_start_year" class="selectpicker custom-select-lg mb-3">
                                                                    <option value="">Year</option>
                                                                    <?php for($i = date('Y'); $i >= 1960; $i--): ?>
                                                                    <option value="<?= $i?>" <?= $experience['s_year'] == $i ? 'selected' : ''?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                             <div class="col-lg-12" id="end_date_container" style="display: <?= $experience['inview'] ? 'none' : 'block';?>;">
                                                <label for="">End date:</label>
                                                <div class="row expand-parent">
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                           <div class="all_alert alert_8 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_end_day" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Day</option>
                                                                    <?php for($i = 1; $i <= 31; $i++): ?>
                                                                    <option value="<?= $i?>" <?= $experience['e_day'] == $i ? 'selected' : ''?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_9 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_end_month" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Month</option>
                                                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                                                    <option value="<?= $i?>" <?= $experience['e_month'] == $i ? 'selected' : ''?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-4 col-4 expand">
                                                        <div class="form-group">
                                                            <div class="all_alert alert_10 text-danger"></div>
                                                            <div class="ui_kit_select_box manipulated">
                                                                <select id="input_job_end_year" class="selectpicker custom-select-lg mb-3 end_date_input">
                                                                    <option value="">Year</option>
                                                                    <?php for($i = date('Y'); $i >= 1960; $i--): ?>
                                                                    <option value="<?= $i?>" <?= $experience['e_year'] == $i ? 'selected' : ''?>><?= $i?></option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-c">
                                                   <input type="checkbox" id="input_present_institution" class="presently_study_input" value="<?= $experience['inview'] ? 'true' : 'false';?>" <?= $experience['inview'] ? 'checked' : '';?>> <label for="">presently working</label>
                                               </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="all_alert alert_11 text-danger"></div>
                                                    <label for="">Description:</label>
                                                  <textarea  id="input_job_description"  class="form-control h50" cols="30" rows="5" placeholder="Write something about the job and the experience you had..."><?= $experience['description']?></textarea>
                                                  <input type="hidden" id="input_job_experience_id" value="<?= Input::get('eid')?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group text-right">
                                                    <a href="<?= url('/employee/account') ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <button type="button" id="edit_job_info_btn" class="btn view-btn-fill">EDIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- edit work end -->
                            <?php endif; ?>

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
//  ADD PROFILE IMAGE
// ============================================
$('.img-conatiner-x').on('change', '.profile_img_input', function(){
    var url = $(".ajax_url_page").attr('href');
    var image = $(".profile_img_input");
    $(".e-loader-kamo").show();
    
    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
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
                $(".nav-profile-img").attr('src', data.data)
                $("#profile_image_img").attr('src', data.data)
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






// =======================================
// PRESENTLY STUDY
// =======================================
$(".presently_study_input").click(function(){
    $(this).val(false);
    if($(this).prop('checked')){
        $(this).val(true);
        $("#end_date_container").hide();
    }else{
        $("#end_date_container").show();
    }
});








// =========================================
// UPDATE EDUCATION
// =========================================
$("#update_education_btn").click(function(e){
    e.preventDefault();
    $(".all_alert").html('');
    var url = $(".ajax_url_page").attr('href');
    var qualification = $("#input_qualification").val();
    var institution = $("#input_institution").val();
    var city = $("#input_city_institution").val();
    var state = $("#input_state_institution").val();
    var country = $("#input_country_institution").val();
    var start_day = $("#input_start_day_institution").val();
    var start_month = $("#input_start_month_institution").val();
    var start_year = $("#input_start_year_institution").val();
    var end_day = $("#input_end_day_institution").val();
    var end_month = $("#input_end_month_institution").val();
    var end_year = $("#input_end_year_institution").val();
    var inview = $("#input_present_institution").val();

    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            qualification: qualification,
            institution: institution,
            city: city,
            state: state,
            country: country,
            start_day: start_day,
            start_month: start_month,
            start_year: start_year,
            end_day: end_day,
            end_month: end_month,
            end_year: end_year,
            inview: inview,
            update_institution: 'update_institution'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                get_error(data.error);
            }else if(data.url){
                 location.assign(data.url);
            }else{
                console.log(data)
            }
            remove_dark_preloader();
        }
    });
   
});




function get_error(error){
    setTimeout(function(){
        $(".alert_0").html(error.qualification);
        $(".alert_1").html(error.institution);
        $(".alert_2").html(error.city);
        $(".alert_3").html(error.state);
        $(".alert_4").html(error.country);
        $(".alert_5").html(error.start_day);
        $(".alert_6").html(error.start_month);
        $(".alert_7").html(error.start_year);
        $(".alert_8").html(error.end_day);
        $(".alert_9").html(error.end_month);
        $(".alert_10").html(error.end_year);
    }, 200);
}



// ================================
// REMOVE PRELOADER
// ================================
function remove_dark_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
    }, 1000);
}






// ======================================
// EDIT EDUCATION
// ======================================
$("#update_education_edit_btn").click(function(e){
    e.preventDefault();
    $(".all_alert").html('');

    var url = $(".ajax_url_page").attr('href');
    var education_key = $("#education_key_input").val();
    var qualification = $("#input_qualification").val();
    var institution = $("#input_institution").val();
    var city = $("#input_city_institution").val();
    var state = $("#input_state_institution").val();
    var country = $("#input_country_institution").val();
    var start_day = $("#input_start_day_institution").val();
    var start_month = $("#input_start_month_institution").val();
    var start_year = $("#input_start_year_institution").val();
    var end_day = $("#input_end_day_institution").val();
    var end_month = $("#input_end_month_institution").val();
    var end_year = $("#input_end_year_institution").val();
    var inview = $("#input_present_institution").val();

     $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            key: education_key,
            qualification: qualification,
            institution: institution,
            city: city,
            state: state,
            country: country,
            start_day: start_day,
            start_month: start_month,
            start_year: start_year,
            end_day: end_day,
            end_month: end_month,
            end_year: end_year,
            inview: inview,
            edit_update_institution: 'edit_update_institution'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                get_error(data.error);
            }else if(data.url){
                location.assign(data.url);
            }else{
                console.log(data)
            }
            remove_dark_preloader();
        }
    });
});












// ====================================
// ADD JOB EXPERIENCE
// ====================================
$("#update_job_info_btn").click(function(e){
    e.preventDefault();
    update_work_experience();
});



function update_work_experience(){
    $(".all_alert").html('');
    var url = $(".ajax_url_page").attr('href');
    
    var job_title = $("#input_job_title").val();
    var job_function = $("#input_job_function").val();
    var employer_name = $("#input_employer_name").val();
    var employer_email = $("#input_employer_email").val();
    var employer_phone = $("#input_employer_phone").val();
    var start_day = $("#input_job_start_day").val();
    var start_month = $("#input_job_start_month").val();
    var start_year = $("#input_job_start_year").val();
    var end_day = $("#input_job_end_day").val();
    var end_month = $("#input_job_end_month").val();
    var end_year = $("#input_job_end_year").val();
    var description = $("#input_job_description").val();
    var inview = $("#input_present_institution").val();

    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            job_title: job_title,
            job_function: job_function,
            employer_name: employer_name,
            employer_email: employer_email,
            employer_phone: employer_phone,
            start_day: start_day,
            start_month: start_month,
            start_year: start_year,
            end_day: end_day,
            end_month: end_month,
            end_year: end_year,
            description: description,
            inview: inview,
            update_job_experience: 'update_job_experience'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.error){
                get_job_error(data.error);
            }else if(data.url){
                location.assign(data.url);
            }else{
                location.reload();
            }
            remove_dark_preloader();
            console.log(response)
        }
    });
} 





function get_job_error(error){
    setTimeout(function(){
        $(".alert_0").html(error.job_title);
        $(".alert_1").html(error.job_function);
        $(".alert_2").html(error.employer_name);
        $(".alert_3").html(error.employer_email);
        $(".alert_4").html(error.employer_phone);
        $(".alert_5").html(error.start_day);
        $(".alert_6").html(error.start_month);
        $(".alert_7").html(error.start_year);
        $(".alert_8").html(error.end_day);
        $(".alert_9").html(error.end_month);
        $(".alert_10").html(error.end_year);
        $(".alert_11").html(error.description);
    }, 200);
}







// ====================================
// EDIT JOB EXPERIENCE
// ====================================
$("#edit_job_info_btn").click(function(e){
    e.preventDefault();
    edit_work_experience();
});


function edit_work_experience(){
    $(".all_alert").html('');
    var url = $(".ajax_url_page").attr('href');
    
    var job_key = $("#input_job_experience_id").val();
    var job_title = $("#input_job_title").val();
    var job_function = $("#input_job_function").val();
    var employer_name = $("#input_employer_name").val();
    var employer_email = $("#input_employer_email").val();
    var employer_phone = $("#input_employer_phone").val();
    var start_day = $("#input_job_start_day").val();
    var start_month = $("#input_job_start_month").val();
    var start_year = $("#input_job_start_year").val();
    var end_day = $("#input_job_end_day").val();
    var end_month = $("#input_job_end_month").val();
    var end_year = $("#input_job_end_year").val();
    var description = $("#input_job_description").val();
    var inview = $("#input_present_institution").val();

    $(".preloader-container").show() //show preloader

    $.ajax({
            url: url,
            method: "post",
            data: {
                key: job_key,
                job_title: job_title,
                job_function: job_function,
                employer_name: employer_name,
                employer_email: employer_email,
                employer_phone: employer_phone,
                start_day: start_day,
                start_month: start_month,
                start_year: start_year,
                end_day: end_day,
                end_month: end_month,
                end_year: end_year,
                description: description,
                inview: inview,
                edit_job_experience: 'edit_job_experience'
            },
            success: function (response){
                var data = JSON.parse(response);
                if(data.error){
                    get_job_error(data.error);
                }else if(data.url){
                    location.assign(data.url);
                }else if(data.not_exist){
                    location.assign(data.not_exist);
                }else{
                    location.reload();
                }
                remove_dark_preloader();
                console.log(response)
            }
        });
}









// end
});
</script>
