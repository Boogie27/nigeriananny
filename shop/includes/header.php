<?php
// ============================================
// app banner settings
// ============================================
$settings =  $connection->select('settings')->where('id', 1)->first();
if(!$settings->is_active && !Admin_auth::is_loggedin())
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

<meta property="og:url" content="<?= current_url() ?>">
<meta property="og:title" content="<?= $settings->site_name ?>">
<meta name="description" content="We offer you the best products">
<meta property="og:image" content="<?= asset('/images/icons/icon.ico') ?>" href="<?= asset('/images/icons/icon.ico') ?>">

<!-- css file -->
<link rel="stylesheet" href="<?= url('/shop/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= url('/shop/css/style.css') ?>">
<!-- Responsive stylesheet -->
<link rel="stylesheet" href="<?= url('/shop/css/responsive.css') ?>">

<link rel="stylesheet" href="<?= url('/shop/css/dashbord_navitaion.css') ?>">

<!-- Title -->
<title><?= $settings->app_name ?></title>
<!-- Favicon -->
<!-- <link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" /> -->

<link href="<?= asset('/images/icons/icon.ico') ?>" rel="shortcut icon" /> 

<!-- main script-->
<link rel="stylesheet" href="<?= url('/shop/css/main-style.css') ?>">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
  
<div class="wrapper page_wrapper">
	<div class="preloader"></div>


 <div class="detail_preloader">
        <img src="<?= url('/shop/images/preloader.gif') ?>" class="product_review_preloader" alt="preloader">
</div>



<!-- ajax preloader-->
<div class="preloader-container">
	<div class="ajax-preloader-x">
		<div class="loader-y">
			<div class="ajax-loader-x"></div> loading...
		</div>
	</div>
</div>