<?php include('Connection.php');  ?>
<?php

// ********** GET FREQUESNTLY ASK QUESTIONS *********//
$faqs = $connection->select('faqs')->where('is_feature', 1)->get();


?>


<?php include('includes/header.php');  ?>


<!--  navigation-->
<?php include('includes/navigation.php');  ?>

<?php include('includes/side-navigation.php');  ?>

<?php include('includes/slider.php');  ?>













	<!-- how it works start -->
     <div class="how-it-works content-two two">
        <div class="content-two-body">
            <div class="three-img">
                <img src="<?= asset('/images/banner/8.png')?>" alt="">
            </div>
            <div class="how-body">
                <ul class="">
                    <li class="title"><h3>Want to hire an employee?</h3></li>
                    <li>1. Register as an employer</li>
                    <li>2. Complete personal informations</li>
                    <li>3. Search for available employees</li>
                    <li>4. Fill in job informations</li>
                    <li>5. Click on <b>Hire now</b></li>
                    <li>6. To complete an employment, click on <b>Complete employment</b></li>
                    <li class="create-btn">
                        <a href="<?= url('/employer/register')?>" class="btn-fill">Create account</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
  <!-- how it works end -->

  <!-- how it works start -->
  <div class="how-it-works content-two">
        <div class="content-two-body">
            <div class="one-img">
                <img src="<?= asset('/images/banner/9.png')?>" alt="" class="one-image-left">
            </div>
            <div class="how-body">
                <ul class="">
                    <li class="title"><h3>Want to subscribe to hire?</h3></li>
                    <li>1. Register as an employer</li>
                    <li>2. Complete personal informations</li>
                    <li>3. Proceed to subscription page</li>
                    <li>4. Select your prefered subscription plan</li>
                    <li>5. View subscription plan on your account page</li>
                    <li>6. Click on <b>Pay now</b></li>
                    <li class="create-btn">
                        <a href="<?= url('/subscription')?>" class="btn-fill">Subscribe now</a>
                    </li>
                </ul>
            </div>
            <div class="two-img fade-right-container">
                <img src="<?= asset('/images/banner/9.png')?>" alt="" class="two-image-right">
            </div>
        </div>
    </div>
    <!-- how it works end -->
    

	<!-- how it works start -->
    <div class="how-it-works content-two two">
        <div class="content-two-body">
            <div class="three-img">
                <img src="<?= asset('/images/banner/7.png')?>" alt="">
            </div>
            <div class="how-body">
                <ul class="">
                    <li class="title"><h3>Want to become an employee?</h3></li>
                    <li>1. Register as an employee</li>
                    <li>2. Complete personal informations</li>
                    <li>3. Upload profile image</li>
                    <li>4. Complete educational information if any</li>
                    <li>5. Complete educational information  if any</li>
                    <li>6. Accept employment request from an employer</li>
                    <li>7. Account becomes <b>disabled</b> until completion of job</li>
                    <li>8. Upon completion of job, contact your employer to complete your employment offer.</li>
                    <li class="create-btn">
                        <a href="<?= url('/employee/register')?>" class="btn-fill">Create account</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
  <!-- how it works end -->

 <?php if(count($faqs)):?>
		<!-- FAQS start-->
		<div class="top-jobs-container">
		<div class="fags-header">
			<h3>Frequestly Asked Questions</h3>
			<p>You can also browse the topic bellow to find what you are loooking for.</p>
		</div>
		<div class="faqs-body-x">
			<ul>
				<?php foreach($faqs as $faq): ?>
				<li>
					<a href="#" class="faq-single-item-x"><?= $faq->faq?> <i class="fa fa-angle-right float-right angle"></a></i>
                     <div class="inner-faq">
						<h4><?= $faq->faq?></h4>
						<?= $faq->content; ?>
					</div>
			    </li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<!-- FAQS end-->
	<?php endif; ?>

    <!-- news letter -->
    <?php include('includes/news-letter.php') ?>

</div>



<!-- Our Footer -->
<?php include('includes/footer.php');  ?>











<script>
$(document).ready(function(){

// ==============================================
// OPEN FAQS CONTENT DETAILS
// ==============================================
$(".faq-single-item-x").click(function(e){
    e.preventDefault();
	var content = $(this).parent().children('.inner-faq');
	$(content).toggle();
});






// end ready function
});
</script>