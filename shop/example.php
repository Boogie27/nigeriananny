<?php require_once('includes/header.php') ?>

<?php

// use PHPMailer\PHPMailer\PHPMailer;

// require_once "../PHPMailer/Exception.php";
// require_once "../PHPMailer/PHPMailer.php";
// require_once "../PHPMailer/SMTP.php";


if(Input::post('send_mail'))
{
	$name = Input::get('name');
	$email = Input::get('email');
	$message = Input::get('message');
	$image = Image::files('image');
	$subject  = Input::get('subject');


	$body = '<h1 style="color: red; border: 1px solid #ccc; padding: 20px; background-color: #555;">'.$message.'</h1>';




	$mail = new Mail();
    $send = $mail->mail([
				'name' => $name,
		        'from' => 'anonyecharles@gmail.com',
				'to' => $email,
				'subject' => $subject,
				'body' => $body,
				'image' => $image
			]);
	
	if(!$send->passed())
	{
		print_r($send->error());
	}else{
		print_r($send->send_email());
	}


	// $sendMail = new PHPMailer();
	// $sendMail->isSMTP();
	// $sendMail->Host = 'smtp.gmail.com';
	// $sendMail->SMTPAuth = true;
	// $sendMail->Username = 'anonyecharles@gmail.com';
	// $sendMail->Password = 'boogie190';
	// $sendMail->Port = 465;
	// $sendMail->SMTPSecure = 'ssl';

	// // /email settings
	// $sendMail->isHTML(true);
	// $sendMail->setFrom('anonyecharles@gmail.com', $name);
	// $sendMail->addAddress($email);
	// $sendMail->Subject = $subject;
	// $sendMail->Body = '<h1 style="color: red; border: 1px solid #ccc; padding: 20px; background-color: #555;">'.$message.'</h1>';
	// $sendMail->addAttachment($_FILES['image']['tmp_name'], $_FILES['image']['name']);

	// if($sendMail->send())
	// {
	// 	echo "Email has been sent!";
	// }else{
	// 	echo $sendMail->ErrorInfo;
	// }
}
?>




<!-- Main Header Nav -->
<?php require_once('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php require_once('includes/search-bar.php') ?>














	<!-- Inner Page Breadcrumb -->
	<section class="inner_page_breadcrumb">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 offset-xl-3 text-center">
					<div class="breadcrumb_content">
						<h4 class="breadcrumb_title">Shop</h4>
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="#">Home</a></li>
						    <li class="breadcrumb-item active" aria-current="page">Shop</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>





<div class="container">
<br>
    <div class="col-lg-6">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<input type="text" class="form-control" name="name" value="" placeholder="name">
			</div>
			<div class="form-group">
				<input type="text" class="form-control" name="subject" value="" placeholder="subject">
			</div>
			<div class="form-group">
				<input type="text" class="form-control" name="email" value="" placeholder="email">
			</div>
			<div class="form-group">
				<input type="text" class="form-control" name="message" value="" placeholder="message">
			</div>
			<div class="form-group">
				<input type="file" class="form-conrol" name="image">
			</div>
			<div class="form-group">
			<button type="submit" name="send_mail" class="btn btn-primary">Send mail</button>
			</div>
		</form>
	</div>
</div>













    <!-- footer -->
<?php include('includes/footer.php') ?>
