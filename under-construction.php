<?php 

ob_start();

spl_autoload_register(function($class){
    require_once 'classes/'.$class.'.php';
});

session_start(); // starts app session

error_reporting(E_ALL);

ini_set('display_errors', 1);

defined('SITE_URL') ? NULL : define('SITE_URL', "http://localhost/JOB/nigeriananny");

$GLOBALS['mysql'] = array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db' => 'nanny'
);




include('helpers/helpers.php');


$connection = DB::instantiate(); //use this instantiated class to make query to database;

?>


<?php

// ============================================
// app banner settings
// ============================================
$banner =  $connection->select('settings')->where('id', 1)->first();
if($banner->is_active)
{
    return view('/');
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
<!-- Responsive stylesheet -->
<link rel="stylesheet" href="<?= url('/css/responsive.css') ?>">

<!-- Title -->
<title><?= title() ?></title>
<!-- Favicon -->
<!-- <link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" /> -->

<!-- main script-->
<link rel="stylesheet" href="<?= url('/css/main-style.css') ?>">
</head>
<body>
    <div class="wrapper page_wrapper">
        <div class="preloader"></div> <!--preloader-->
        <div class="under-contruction">
            <div class="inner-construction">
                <img src="<?= asset($banner->construction_banner); ?>" alt="<?= $banner->app_name?>">
                <h3>Nigeria nanny company</h3>
                <p>We are currently unavailable but we will be back soon!</p>
            </div>
        </div>
    </div>

    <div class="footer-btn">
        <ul>
            <li><?= $banner->info_email; ?></li>
            <li><?= $banner->alrights; ?></li>
        </ul>
    </div>
</body>
</html>




<!-- Wrapper End -->
<script data-cfasync="false" src="https://grandetest.com/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-migrate-3.0.0.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/popper.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery.mmenu.all.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/ace-responsive-menu.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/snackbar.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/simplebar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/parallax.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/scrollto.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-scrolltofixed-min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery.counterup.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/wow.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/progressbar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/slider.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/timepicker.js"></script>
<!-- Custom script for all pages --> 
<script type="text/javascript" src="<?= SITE_URL ?>/js/script.js"></script>










