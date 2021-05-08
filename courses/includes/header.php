<?php
// ============================================
// app banner settings
// ============================================
$app_active =  $connection->select('settings')->where('id', 1)->where('is_active', 1)->first();
if(!$app_active && !Admin_auth::is_loggedin())
{
    return view('/under-construction');
}
?>


<!DOCTYPE html>
<html dir="ltr" lang="en">

<!-- Mirrored from grandetest.com/theme/edumy-html/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:05:00 GMT -->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="academy, college, coursera, courses, education, elearning, kindergarten, lms, lynda, online course, online education, school, training, udemy, university">
<meta name="description" content="shop">
<meta name="CreativeLayers" content="ATFN">
<!-- css file -->
<link rel="stylesheet" href="<?= url('/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= url('/css/style.css') ?>">

<!-- Title -->
<title><?= title() ?></title>
<!-- Favicon -->
<!-- <link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" /> -->

<!-- main style-->
<link rel="stylesheet" href="<?= url('/css/main-style.css') ?>">

<!-- video style-->
<link rel="stylesheet" href="<?= url('/css/course-style.css') ?>">

</head>
<body>
  
<div class="main-page">
	<!-- <div class="preloader"></div> -->

<!-- ajax preloader-->
<div class="preloader-container">
	<div class="ajax-preloader-x">
		<div class="loader-y">
			<div class="ajax-loader-x"></div> Please wait...
		</div>
	</div>
</div>



 <!-- little preloader start-->
 <div class="little-preloader-container">
    <div class="little-dark-theme">
        <div class="preloader-back-light">
          <div class="little-p-content">
                <div class="little-loader"></div>
          </div>
        </div>
    </div>
 </div>
<!-- littl preloader end -->


