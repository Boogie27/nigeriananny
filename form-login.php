<?php include('Connection.php');  ?>

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
        <div class="register-container">
            <h3>Login to your account</h3>
            <div class="register-forms">
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
                            <div class="btn-anchor"><a href="#" class="">Job seeker login</a></div>
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
                            <div class="btn-anchor"><a href="<?= url('/employer/login.php') ?>" class="">Employer login</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>







    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>