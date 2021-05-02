<?php include('Connection.php');  ?>

<?php include('includes/header.php');  ?>


<!--  navigation-->
<?php include('includes/navigation.php');  ?>

<?php include('includes/side-navigation.php');  ?>

    
   <!-- jobs  start-->
   <div class="page-content">
        <div class="register-container">
            <h3>Create your account</h3>
            <div class="register-forms" id="work-form-container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                       <div class="fc">
                            <ul class="employee-form">
                                <li><i class="fa fa-user employee-icon"></i></li>
                                <li><h4>Job seeker</h4></li>
                                <li>
                                    <p>Are you looking for your dream job? <br>
                                    Create a new career with nigeriananny company.
                                    </p>
                                </li>
                            </ul>
                            <div class="btn-anchor"><a href="<?= url('/employee/register') ?>" class="">Job seeker signup</a></div>
                            <div class="alternative-login">
                                  <p>Or</p>
                                  Already have and account? <a href="<?= url('/employee/login') ?>" class="text-primary"> Login as Employee</a>
                            </div>
                       </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="fc">
                            <ul class="employee-form">
                                <li><i class="fa fa-briefcase employee-icon"></i></li>
                                <li><h4>Employer</h4></li>
                                <li>
                                    <p>Are you looking for quality candidate? <br>
                                    search with nigeriananny company.
                                    </p>
                                </li>
                            </ul>
                            <div class="btn-anchor"><a href="<?= url('/employer/register') ?>">Employer signup</a></div>
                            <div class="alternative-login">
                                  <p>Or</p>
                                  Already have and account?<a href="<?= url('/employer/login') ?>" class="text-primary"> Login as Employer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>












    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>