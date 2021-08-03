<?php include('../Connection.php');  ?>

<?php
    if(Auth_employee::is_loggedin())
    {
        return view('/');
    }

    if(Input::post('create_employee'))
    {
        $validate = new DB();
       
        $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|match:password',
            'email' => 'required|email|unique:employee',
            'gender' => 'required',
            'image' => 'file_required|img_min:10000'
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        if($validation->passed())
        {
            $image = new Image();
            $file = Image::files('image');
            $fileName = Image::name('image', 'employee');
            $image_name = '/employee/images/'.$fileName;
            $images = $image->upload_image($file, [ 'name' => $fileName, 'size_allowed' => 5000000,'file_destination' => '../employee/images/' ]);
               
            if(!$images->passed())
            {
                Session::errors('errors', ['image' => $images->error()]);
                return back();
            }

            $create = new DB();
            $employee = $create->create('employee', [
                        'first_name' => Input::get('first_name'),
                        'last_name' => Input::get('last_name'),
                        'email' => Input::get('email'),
                        'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                        'gender' => Input::get('gender'),
                        'w_image' => $image_name
                    ]);
            if($employee->passed())
            {
                $employer = $connection->select('employee')->where('email', Input::get('email'))->first();
                $create->create('workers', [
                    'employee_id' => $employer->e_id,
                ]);

                $worker = $connection->select('workers')->where('employee_id', $employer->e_id)->first();
                $location = '/';
                if($worker)
                {
                    $location = '/job-detail.php?wid='.$worker->worker_id;
                }
                Session::flash('success', 'Account created successfully, please complete your account information, <b><a href="'.url('/employee/account').'">click here</a></b>');
                Auth_employee::login(Input::get('email'));
                return view($location);
            }
        }
    }


?>



<?php include('../includes/header.php');  ?>


<!-- top navigation-->
<?php include('../includes/navigation.php');  ?>

<?php include('../includes/side-navigation.php');  ?>




<div class="page-content">
    <div class="job-seeker-conatiner">
        <div class="sr-head"><h4>Create Employee Account</h4></div>
        <form action="<?= current_url() ?>" method="POST" enctype="multipart/form-data" class="form-input-container">
            <?php if(Session::has('error')): ?>
                <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
            <?php endif; ?>
            <div class="form-seeker">
                <h4 class="pb-3"><i class="fa fa-user"></i> Personal information</h4>
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <div class="alert_label">
                                <?php  if(isset($errors['first_name'])) : ?>
                                    <div class="text-danger"><?= $errors['first_name']; ?></div>
                                <?php endif; ?>
                            </div>
                            <label for="">First name:</label>
                            <input type="text" name="first_name" class="form-control h50" value="<?= $input['first_name'] ?? old('first_name')?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <div class="alert_label">
                                <?php  if(isset($errors['last_name'])) : ?>
                                    <div class="text-danger"><?= $errors['last_name']; ?></div>
                                <?php endif; ?>
                            </div>
                            <label for="">Last name:</label>
                            <input type="text" name="last_name" class="form-control h50" value="<?= $input['last_name'] ?? old('last_name')?>">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <div class="alert_label">
                                <?php  if(isset($errors['email'])) : ?>
                                    <div class="text-danger"><?= $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            <label for="">Email:</label>
                            <input type="email" name="email" class="form-control h50" value="<?= $input['email'] ?? old('email')?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <div class="alert_label">
                                <?php  if(isset($errors['password'])) : ?>
                                    <div class="text-danger"><?= $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                            <label for="">Password:</label>
                            <input type="password" name="password" class="form-control h50" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <div class="alert_label">
                                <?php  if(isset($errors['confirm_password'])) : ?>
                                    <div class="text-danger"><?= $errors['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                            <label for="">Confirm password:</label>
                            <input type="password" name="confirm_password" class="form-control h50" required>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="form-group">
                            <div class="alert_label">
                                <?php  if(isset($errors['image'])) : ?>
                                    <div class="text-danger"><?= $errors['image']; ?></div>
                                <?php endif; ?>
                            </div>
                            <label for="">Image:</label><br>
                            <input type="file" name="image" class="" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="alert_label">
                                <?php  if(isset($errors['gender'])) : ?>
                                    <div class="text-danger"><?= $errors['gender']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="apply_checkbox_d">
                                <input type="checkbox" class="gender_checkbox_input" value="male">
                                <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Male</label>
                            </div>	
                            <div class="apply_checkbox_d">
                                <input type="checkbox" class="gender_checkbox_input" value="female">
                                <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Female</label>
                            </div>
                        </div>
                        <input type="hidden" name="gender" class="employee_gender_input" value="">
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button type="submit" name="create_employee" class="btn-fill">Register</button>
                            <p class="apply-p">Already have an account? <br><a href="<?= url('/employee/login') ?>" class="text-primary">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


    
<!-- Our Footer -->
<?php include('../includes/footer.php');  ?>





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
    $(".employee_gender_input").val($(this).val());
});







});
</script>