<?php

// ********** SUBSCRIPTION EXPIRE ON DUE MONTH ************//
$employer_subs = $connection->select('employer_subscriptions')->where('is_expire', 0)->get();
if(count($employer_subs))
{
	$today = date('Y-m-d H:i:s');
	foreach($employer_subs as $subs)
	{
		if($today > $subs->end_date)
		{
		    $update = $connection->update('employer_subscriptions', [
		               'is_expire' => 1,
		               'is_expire_date' => $today
			        ])->where('is_expire', 0)->where('subscription_id', $subs->subscription_id )->save();
		}
	}
}


// app banner settings
$settings =  $connection->select('settings')->where('id', 1)->first();
if(!$settings->is_active && !Admin_auth::is_loggedin())
{
    return view('/under-construction');
}

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<!-- Mirrored from grandetest.com/theme/edumy-html/page-dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:06:31 GMT -->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords">
<meta name="description" content="shop">
<meta name="CreativeLayers" content="ATFN">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta property="og:url" content="<?= current_url() ?>">
<meta property="og:title" content="<?= $settings->site_name ?>">
<meta name="description" content="We offer you the best employee">
<meta property="og:image" content="<?= asset('/images/icons/icon.ico') ?>" href="<?= asset('/images/icons/icon.ico') ?>">

<!-- css file -->
<link rel="stylesheet" href="<?= asset('/admin/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= asset('/admin/css/style.css') ?>">
<link rel="stylesheet" href="<?= asset('/admin/css/dashbord_navitaion.css') ?>">
<!-- Responsive stylesheet -->
<link rel="stylesheet" href="<?= asset('/admin/css/responsive.css') ?>">

<!-- main style -->
<link rel="stylesheet" href="<?= asset('/admin/css/main-style.css') ?>">

<!-- Title -->
<title><?= title() ?></title>
<!-- Favicon -->
<!-- <link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="images/favicon.ico" sizes="128x128" rel="shortcut icon" /> -->

 <!-- ck editor -->
 <script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrapper">
	<div class="preloader"></div>





<!-- ajax preloader-->
<div class="preloader-container">
	<div class="ajax-preloader-x">
		<div class="loader-y">
			<div class="ajax-loader-x"></div> Please wait...
		</div>
	</div>
</div>