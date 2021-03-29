<?php include('Connection.php');  ?>

<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<!-- images/home/4.jpg -->
	

	<!-- mobile navigation-->
    <?php include('includes/mobile-navigation.php');  ?>
    

    
	<!-- job search start-->
		<?php include('includes/search.php');  ?>
	<!-- job search end-->

    

   <!-- jobs  start-->
    <div class="page-content">
        <div class="inner-jobs">
            <div class="advert-banner">
              <a href="#">  <img src="<?= asset('/images/adverts/1.jpg')?>" alt=""></a>
            </div>
            <div class="job-head">
                <br>
                <h3>Featured jobs</h3>
            </div>
            <div class="row">
                <div class="col-lg-3">  <!-- category jobs start-->
                   <div class="ui_kit_input">
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control h50" id="exampleInputText" placeholder="Search by name">
                            </div>
                        </form>
                    </div>
                    <div class="job-category">
                        <div class="job-cat-head">
                            <h3>Categories</h3>
                        </div>
                        <div class="selected_filter_widget style2 mb30" id="job-category">
                            <div id="accordion" class="panel-group">
                                <div class="panel">
                                    <div id="panelBodySoftware" class="panel-collapse collapse show">
                                        <div class="panel-body">
                                            <div class="category_sidebar_widget">
                                                <ul class="category_list">
                                                    <li><a href="#">Photoshop <span class="float-right">(03)</span></a></li>
                                                    <li><a href="#">Adobe Illustrator <span class="float-right">(15)</span></a></li>
                                                    <li><a href="#">Graphic Design <span class="float-right">(126)</span></a></li>
                                                    <li><a href="#">Sketch <span class="float-right">(1,584)</span></a></li>
                                                    <li><a href="#">InDesign <span class="float-right">(34)</span></a></li>
                                                    <li><a href="#">CorelDRAW <span class="float-right">(58)</span></a></li>
                                                    <li><a href="#">After Effects <span class="float-right">(06)</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="job-alert-banner"><!-- job-alert jobs start-->
                        <div class="alert-header">
                            <h3>Jobs in Nigeria</h3>
                        </div>
                        <div class="alert-body">
                            <p><b>1280</b> jobs found</p>
                            <a href="#">Create job alert</a>
                        </div>
                    </div><!-- job-alert jobs start-->

                    <div class="advert-banner-2">
                        <a href="#"><img src="<?= asset('/images/adverts/4.jpg')?>" alt=""></a>
                    </div>
                    <div class="advert-banner-2">
                        <a href="#"><img src="<?= asset('/images/adverts/4.jpg')?>" alt=""></a>
                    </div>
                </div><!-- category jobs end-->

                <div class="col-lg-9">
                    <div class="job-head-2">
                        <br>
                        <h3>Featured jobs</h3>
                    </div>
                     <!-- featured jobs start-->
                    <div class="job-body">
                        <div class="jobs-info">
                            <img src="images/employee/1.png" alt="">
                            <ul class="ul">
                                <li>
                                    <h4>
                                        <a href="<?= url('/job-detail.php') ?>">Kitchen assistance</a> 
                                        <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> 30/2/2020</span>
                                    </h4>
                                </li>
                                <li>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </li>
                                <li>kasemen alfenso</li>
                                <li>Lagos | Live in | <span class="text-warning money-amount">#55,00 - #65,00</span></li>
                                <li>Care giver, driving, cooking</li>
                            </ul>
                        </div>
                        <div class="jobs-detail">
                            <img src="images/icons/1.svg" alt="">
                            <p>hello i am an employee and i like to get a job as soon as possible. Teach what you love. Dove Schooll gives you the tools to create an
                                online course.
                            </p>
                        </div>
                        <div class="view-btn">
                            <a href="<?= url('/job-detail.php') ?>" class="view-btn-fill">view details</a>
                        </div>
                    </div>
                    <!-- featured jobs end-->

                    <!-- featured jobs start-->
                    <div class="job-body">
                        <div class="jobs-info">
                            <img src="images/employee/1.png" alt="">
                            <ul class="ul">
                                <li>
                                    <h4>
                                        <a href="<?= url('/job-detail.php') ?>">Kitchen assistance</a> 
                                        <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> 30/2/2020</span>
                                    </h4>
                                </li>
                                <li>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </li>
                                <li>kasemen alfenso</li>
                                <li>Lagos | Live in | <span class="text-warning money-amount">#55,00 - #65,00</span></li>
                                <li>Care giver, driving, cooking</li>
                            </ul>
                        </div>
                        <div class="jobs-detail">
                            <img src="images/icons/1.svg" alt="">
                            <p>hello i am an employee and i like to get a job as soon as possible. Teach what you love. Dove Schooll gives you the tools to create an
                                online course.
                            </p>
                        </div>
                        <div class="view-btn">
                            <a href="<?= url('/job-detail.php') ?>" class="view-btn-fill">view details</a>
                        </div>
                    </div>
                    <!-- featured jobs end-->

                    <!-- featured jobs start-->
                    <div class="job-body">
                        <div class="jobs-info">
                            <img src="images/employee/1.png" alt="">
                            <ul class="ul">
                                <li>
                                    <h4>
                                        <a href="<?= url('/job-detail.php') ?>">Kitchen assistance</a> 
                                        <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> 30/2/2020</span>
                                    </h4>
                                </li>
                                <li>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </li>
                                <li>kasemen alfenso</li>
                                <li>Lagos | Live in | <span class="text-warning money-amount">#55,00 - #65,00</span></li>
                                <li>Care giver, driving, cooking</li>
                            </ul>
                        </div>
                        <div class="jobs-detail">
                            <img src="images/icons/1.svg" alt="">
                            <p>hello i am an employee and i like to get a job as soon as possible. Teach what you love. Dove Schooll gives you the tools to create an
                                online course.
                            </p>
                        </div>
                        <div class="view-btn">
                            <a href="<?= url('/job-detail.php') ?>" class="view-btn-fill">view details</a>
                        </div>
                    </div>
                    <!-- featured jobs end-->
                </div>
            </div>
        </div>
    </div>
    <!-- jobs end-->


    <!-- Our Footer -->
<?php include('includes/footer.php');  ?>