<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/add-employers');
  return view('/admin/login');
}




if(Input::post('create_employer'))
{
    if(Token::check())
    {
        $validate = new DB();
    
        $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'email' => 'required|unique:employers',
            'gender' => 'required'
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        if($validation->passed())
        {
            $create = new DB();
            $create->create('employers', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'email' => Input::get('email'),
                    'e_gender' => Input::get('gender'),
                ]);

            if($create->passed())
            {
                Session::flash('success', 'Employer created successfully!');
                return view('/admin-nanny/employers');
            }
        }
    }
}





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
                            <h4 class="title float-left">Add employer</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/employers') ?>">Employers</a></li>
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
                                    <form action="<?= current_url()?>" method="POST">
                                        <div class="sr-head text-center"><h4>Create Employer Account</h4></div><br>
                                        
                                            <div class="row">
                                                <div class="col-lg-6 col-sm-6">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['first_name'])) : ?>
                                                            <div class="text-danger"><?= $errors['first_name']; ?></div>
                                                        <?php endif; ?>
                                                        <label for="">First name:</label>
                                                        <input type="text" name="first_name" class="form-control h50" value="<?= old('first_name')?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-sm-6">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['last_name'])) : ?>
                                                            <div class="text-danger"><?= $errors['last_name']; ?></div>
                                                        <?php endif; ?>
                                                        <label for="">Last name:</label>
                                                        <input type="text" name="last_name" class="form-control h50" value="<?= old('last_name')?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['email'])) : ?>
                                                            <div class="text-danger"><?= $errors['email']; ?></div>
                                                        <?php endif; ?>
                                                        <label for="">Email:</label>
                                                        <input type="email" name="email" class="form-control h50" value="<?= old('email')?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['gender'])) : ?>
                                                            <div class="text-danger"><?= $errors['gender']; ?></div>
                                                        <?php endif; ?>
                                                        <div class="apply_checkbox_d">
                                                            <input type="checkbox" class="gender_checkbox_input" value="male" <?= old('gender') == 'male' ? 'checked' : ''?>>
                                                            <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Male</label>
                                                        </div>	
                                                        <div class="apply_checkbox_d">
                                                            <input type="checkbox" class="gender_checkbox_input" value="female" <?= old('gender') == 'female' ? 'checked' : ''?>>
                                                            <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;" >Female</label>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="gender" id="employer_gender_input" class="employer_gender_input" value="<?= old('gender') ?>">
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <button type="submit" name="create_employer" id="create_employee" class="btn-fill">SUBMIT</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?= csrf_token() ?>
                                        </form>
                                </div>
                            </div>

                        </div><!-- content end-->

                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-copy-right">
    <p><?= $banner->alrights ?></p>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>
                       
                        




<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>
<a href="#" id="<?= Input::get('wid') ?>" class="employee_id_input" style="display: none;"></a>




<?php  include('includes/footer.php') ?>









<script>
$(document).ready(function(){



// ========================================
// ASSIGN GENDER FIELD
// ========================================
var gender = $(".gender_checkbox_input");
$.each($(".gender_checkbox_input"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < gender.length; i++){
            if(index != i)
            {
               $($(gender)[i]).prop('checked', false);
            }else{
                $($(gender)[i]).prop('checked', true);
            }
        }
    });
});


$(gender).click(function(){
    $(".employer_gender_input").val($(this).val());
});



// end
});
</script>

                        