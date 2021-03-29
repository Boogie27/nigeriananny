

<?php include('Connection.php');  ?>

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
        <div class="sr-head"><h4>Creat a job seeker account</h4></div>
        <form action="" method="POST">
            <div class="form-seeker">
                <h4 class="pb-3"><i class="fa fa-user"></i> Personal information</h4>
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">First name:</label>
                            <input type="text" name="first_name" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">Last name:</label>
                            <input type="text" name="last_name" class="form-control h50" placeholder="Last name">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <label for="">Email:</label>
                            <input type="email" name="email" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <label for="">Password:</label>
                            <input type="password" name="password" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <label for="">Confirm password:</label>
                            <input type="password" name="confirm_password" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">Phone</label>
                            <input type="text" name="phone" class="form-control h50" placeholder="Phone">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">Date of birth:</label>
                            <input type="date" name="date_of_birth" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">Gender:</label>
                            <div class="ui_kit_select_box">
                                <select class="selectpicker custom-select-lg mb-3">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">City:</label>
                            <input type="city" name="city" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">State:</label>
                            <input type="text" name="state" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">Country</label>
                            <div class="ui_kit_select_box">
                                <select class="selectpicker custom-select-lg mb-3">
                                    <option value="">Select</option>
                                    <option value="">nigeri</option>
                                    <option value="">london</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <div class="form-seeker">
                <h4 class="pb-3"><i class="fa fa-briefcase"></i> Work information:</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Highest qualification:</label>
                            <input type="text" name="qualification" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label for="">Years of expirience:</label>
                            <input type="number" min="1" name="years_of_expirience" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label for="">Salary expectation:</label>
                            <input type="text" name="salary_expectation" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label for="">Salary expectation:</label>
                            <input type="text" name="salary_expectation" class="form-control h50" placeholder="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="apply_checkbox_d">
                                <input type="checkbox" class="">
                                <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Can you read and write?</label>
                            </div>	
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="apply_checkbox_d">
                                <input type="checkbox" class="">
                                <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Live inside?</label>
                            </div>	
                            <div class="apply_checkbox_d">
                                <input type="checkbox" class="">
                                <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Live outside?</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Desired job location:</label>
                            <input type="text" name="salary_expectation" class="form-control h50" placeholder="">
                        </div>
                    </div>
                   
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <label for="">Upload CV:</label>
                            <input type="file" name="cv" class="form-control cv_input h50">
                            <span class="cv-p">Upload CV no longer than 10MB for .pdf, .doc, .docx files</span><br>
                            <span class="cv-p"><b>Please note</b> : You will need to upload a CV to apply for jobs.</span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <label for="">Cover letter:</label>
                            <input type="file" name="cover_letter" class="form-control cv_input h50">
                            <p class="p-c">
                                Cover letter helps you tell the employer all about who you are, your accomplishment 
                                and your strength and the reason why you are good for the job.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <br>
                           <button type="" name="" class="uppercase btn-fill">Create your account</button>
                           <p class="apply-p">
                                By click 'Apply now', You agree to our <a href="#" class="text-primary">terms & conditions</a>
                                and <a href="#" class="text-primary">Privacy policy</a>
                            </p>
                            <p class="apply-p">Already have an account? <br><a href="#" class="text-primary">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>




    
<!-- Our Footer -->
<?php include('includes/footer.php');  ?>