<?php include('Connection.php');  ?>
<?php 


// ===========================================
// POST CONTACT MESSAGE
// ===========================================
if(Input::post('contact_nanny'))
{
    $validate = new DB();
    $validation = $validate->validate([
        'full_name' => 'required|min:3|max:50',
        'email' => 'required|email',
        'subject' => 'required|min:6|max:50',
        'message' => 'required|min:6|max:3000',
    ]);

    if(!$validation->passed())
    {
        return back();
    }

    if($validation->passed())
    {
    $create = $connection->create('contact_us', [
        'full_name' => Input::get('full_name'),
        'email' => Input::get('email'),
        'subject' => Input::get('subject'),
        'message' => Input::get('message'),
    ]);

    if($create->passed())
    {
        Session::flash('success', 'Message has been sent and would be attended to shortly!');
        return back();
    }
    }
}





// ********** GET FREQUESNTLY ASK QUESTIONS *********//
$faqs = $connection->select('faqs')->where('is_feature', 1)->get();






?>
<?php include('includes/header.php');  ?>


<!--  navigation-->
<?php include('includes/navigation.php');  ?>

<?php include('includes/side-navigation.php');  ?>

    
 
    

 <!-- contacts start -->
 <div class="contact-container">
        <div class="contact-body">
            <div class="contact-img">
                <img src="<?= asset('/images/banner/6.png')?>" alt="">
            </div>
            <div class="contact-form">
                <form action="<?= current_url() ?>" method="POST">
                    <?php if(Session::has('success')): ?>
                        <div class="text-success text-center"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="title"><h3>Contact us</h3></div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label for="">Full name:</label>
                                <input type="text" name="full_name" class="form-control h50" value="<?= old('full_name') ?>">
                                <div class="alert-cont">
                                        <?php  if(isset($errors['full_name'])) : ?>
                                        <div class="text-danger"><?= $errors['full_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Email:</label>
                                    <input type="email" name="email" class="form-control h50" value="<?= old('email') ?>">
                                    <div class="alert-cont">
                                         <?php  if(isset($errors['email'])) : ?>
                                            <div class="text-danger"><?= $errors['email']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Subject:</label>
                                    <input type="text" name="subject" class="form-control h50" value="<?= old('subject') ?>">
                                    <div class="alert-cont">
                                         <?php  if(isset($errors['subject'])) : ?>
                                            <div class="text-danger"><?= $errors['subject']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Message:</label>
                                  <textarea name="message" class="form-control h50" cols="30" rows="6" placeholder="Message or ask a question here..."><?= old('message') ?></textarea>
                                    <div class="alert-cont">
                                         <?php  if(isset($errors['message'])) : ?>
                                            <div class="text-danger"><?= $errors['message']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button type="submit" name="contact_nanny" class="btn btn-fill">SUBMIT</button>
                                </div>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  <!-- contacts end -->



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