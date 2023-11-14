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
if(!Input::exists('get') || !Input::get('page'))
{
    return view('/admin-nanny/employees');
}







// ============================================
//  UPDATE EMPLOYEE ABOUT
// ============================================
if(Input::post('update_about'))
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
            ])->where('employee_id', Input::get('wid'))->save();
            if($update)
            {
                Session::flash('success', 'About updated successufully!');
            }
        }
    return view('/admin-nanny/employee-detail?wid='.Input::get('wid'));
    }
}






// ============================================
//  UPDATE EMPLOYEE SUMMARY
// ============================================
if(Input::post('update_summary'))
{
    if(Token::check())
    {
        $validate = new DB();
        $validation = $validate->validate([
            'summary' => 'required|min:3|max:3000',
        ]);

        if($validation->passed())
        {
            $update = $connection->update('workers', [
                'summary' => Input::get('summary')
            ])->where('employee_id', Input::get('wid'))->save();
            if($update)
            {
                Session::flash('success', 'Summary updated successufully!');
            }
        }
        return view('/admin-nanny/employee-detail?wid='.Input::get('wid'));
    }
}







// ======================================
// GET EMPLOYEE DETAILS
// ======================================
$employee = $connection->select('employee')->where('e_id', Input::get('wid'))->first();
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
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Employee Update</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/index.php') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin/customers.php') ?>">Employees</a></li>
                            </ol>
                        </nav>
                    </div>
                     
                    <!-- INNER CONENT START-->
                    <div class="col-lg-12">
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
                                                <a href="<?= url('/admin-nanny/employee-detail.php?wid='.Input::get('wid')) ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                <button type="submit" name="update_about" class="btn view-btn-fill">UPDATE...</button>
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
                            <!-- about start -->
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
                                                    <a href="<?= url('/admin-nanny/employee-detail.php?wid='.Input::get('wid')) ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <button type="button" id="update_education_btn" class="btn view-btn-fill">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- about end -->
                            <?php endif; ?>


                            <?php if(Input::exists('get') && Input::get('page') == 'edu_edit' && Input::get('wid')): 
                            $old_edu = json_decode($worker->education, true) ?>
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
                                                    <a href="<?= url('/admin-nanny/employee-detail.php?wid='.Input::get('wid')) ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <button type="button" id="update_education_edit_btn" class="btn view-btn-fill">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- edit education end -->
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
                                                    <a href="<?= url('/admin-nanny/employee-detail.php?wid='.Input::get('wid')) ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <button type="button" id="update_job_info_btn" class="btn view-btn-fill">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- add work end -->
                            <?php endif; ?>


                            <?php if(Input::exists('get') && Input::get('page') == 'edit_work' && Input::get('wid')): 
                                $experience = json_decode($worker->work_experience, true); ?>
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
                                                    <a href="<?= url('/admin-nanny/employee-detail.php?wid='.Input::get('wid')) ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                                                    <button type="button" id="edit_job_info_btn" class="btn view-btn-fill">EDIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- edit work end -->
                            <?php endif; ?>
                    </div>
                    <!-- INNER CONTENT END-->

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
<a href="#" id="<?= Input::get('wid') ?>" class="employee_id_input" style="display: none;"></a>




<!-- footer-->
<?php  include('includes/footer.php') ?>







<script>
$(document).ready(function(){


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
    var employee_id = $(".employee_id_input").attr('id');
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
            employee_id: employee_id,
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
    var employee_id = $(".employee_id_input").attr('id');
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
            employee_id: employee_id,
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
// UPDATE JOB EXPERIENCE
// ====================================
$("#update_job_info_btn").click(function(e){
    e.preventDefault();
    update_work_experience();
});



function update_work_experience(){
    $(".all_alert").html('');
    var url = $(".ajax_url_page").attr('href');
    var employee_id = $(".employee_id_input").attr('id');
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
            employee_id: employee_id,
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
    var employee_id = $(".employee_id_input").attr('id');
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
                employee_id: employee_id,
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