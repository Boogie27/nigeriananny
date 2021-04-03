<?php include('../Connection.php');  ?>

<?php
    if(Input::post('create_employer'))
    {
        $validate = new DB();
       
        $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|match:password',
            'email' => 'required|email|unique:employers',
            'phone' => 'required|min:11|max:11|number:phone',
            'city' => 'required|min:3|max:50',
            'state' => 'required|min:3|max:50',
            'country' => 'required|min:3|max:50',
            'e_gender' => 'required'
        ]);

        if($validation->passed())
        {
            $create = new DB();
            $create->create('employers', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'email' => Input::get('email'),
                    'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                    'e_phone' => Input::get('phone'),
                    'city' => Input::get('city'),
                    'state' => Input::get('state'),
                    'country' => Input::get('country'),
                    'e_gender' => Input::get('gender'),
                ]);
    
            if($create->passed())
            {
                Session::flash('success', 'You have Signedup successfully!');
                Auth_employer::login(Input::get('email'));
                return view('/jobs');
            }
        }
    }


    $countries = $connection->select('tbl_country')->where('active', 1)->get();
?>
<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<!-- images/home/4.jpg -->
	

<!-- mobile navigation-->
<?php include('includes/mobile-navigation.php');  ?>
    



<div class="page-content">
    <div class="job-seeker-conatiner">
        <div class="sr-head"><h4>Creat an Employer Account</h4></div>
        <form action="<?= current_url() ?>" method="POST">
            <?php if(Session::has('success')): ?>
                <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
            <?php endif; ?>
            <div class="form-seeker form-employer-container">
                <h4 class="pb-3"><i class="fa fa-user"></i> Personal information</h4>
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
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['password'])) : ?>
                                <div class="text-danger"><?= $errors['password']; ?></div>
                            <?php endif; ?>
                            <label for="">Password:</label>
                            <input type="password" name="password" class="form-control h50" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['confirm_password'])) : ?>
                                <div class="text-danger"><?= $errors['confirm_password']; ?></div>
                            <?php endif; ?>
                            <label for="">Confirm password:</label>
                            <input type="password" name="confirm_password" class="form-control h50" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['phone'])) : ?>
                                <div class="text-danger"><?= $errors['phone']; ?></div>
                            <?php endif; ?>
                            <label for="">Phone</label>
                            <input type="text" name="phone" class="form-control h50" value="<?= old('phone')?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['city'])) : ?>
                                <div class="text-danger"><?= $errors['city']; ?></div>
                            <?php endif; ?>
                            <label for="">City:</label>
                            <input type="city" name="city" class="form-control h50" value="<?= old('city')?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['state'])) : ?>
                            <div class="text-danger"><?= $errors['state']; ?></div>
                        <?php endif; ?>
                            <label for="">State:</label>
                            <input type="text" name="state" class="form-control h50" value="<?= old('state')?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['country'])) : ?>
                                <div class="text-danger"><?= $errors['country']; ?></div>
                            <?php endif; ?>
                            <label for="">Country</label>
                            <div class="ui_kit_select_box">
                                <select name="country" class="selectpicker custom-select-lg mb-3">
                                <?php if(count($countries)): 
                                    foreach($countries as $country): ?>
                                        <option value="<?= $country->country ?>" <?= $country->country == "NIGERIA" ? 'selected' : '' ?>><?= $country->country?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No country</option>
                                <?php endif; ?>
                                </select>
                            </div>
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
                            <button type="submit" name="create_employer" class="uppercase btn-fill">Create an account</button>
                            <p class="apply-p">Already have an account? <br><a href="<?= url('/employer/login') ?>" class="text-primary">Login</a></p>
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
    $(".employer_gender_input").val($(this).val());
});



// end
});
</script>
