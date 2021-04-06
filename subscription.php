<?php include('Connection.php');  ?>
<?php

// =======================================
//         PAYSTACK SUBSCRIPTION
// =======================================
if(Input::post('subscribe'))
{
    if(Auth_employer::is_loggedin() && Session::has('subscription'))
    {
        $url = url('/success');
        $subscription = Session::get('subscription');
        $amount = $subscription['amount'];
        $email = Auth_employer::employer('email');

        $paystack = new Paystack();
        $paystack->initialize($email, $amount, $url);
    }
}




$subscription_pans = $connection->select('subscription_pan')->where('is_feature', 1)->get();

?>
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
            <h3 class="h">Choose a subscription plan</h3>
             <div class="alert-container">
                <?php if(Session::has('success')): ?>
                    <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div>
                <?php endif; ?>
             </div>
            <div class="subscription-forms">
            <?php  if(count($subscription_pans)): ?>
                <div class="row">
                    <?php foreach($subscription_pans as $subscription): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                       <div class="sub-form">
                            <ul class="">
                                <!-- <li><i class="fa fa-user employee-icon"></i></li> -->
                                <li class='plan'><h1><?= ucfirst($subscription->type) ?></h1></li>
                                <li><h1  class=""> <?= $subscription->duration ?></h1></li>
                                <li><h4 class="text-primary"> <?= money($subscription->amount) ?></h4></li>
                                <li><p><?= ucfirst($subscription->description) ?></p></li>
                            </ul>
                            <a href="#" data-toggle="modal" id="<?= $subscription->sub_id?>" data-target="#exampleModalCenter" class="subscription_open_btn view-btn-fill">Subscribe now</a>
                       </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                 <div class="empty-sub-plan">
                    <div class="sub-form">
                        <ul class="">
                            <li><img src="<?= asset('/images/icons/1.svg') ?>" alt=""></li>
                            <li><h1>No subscription plan</h1></li>
                        </ul>
                        <a href="<?= url('/') ?>" class="view-btn-fill">Back</a>
                    </div>   
                 </div>
                <?php endif; ?>
            </div>
        </div>
   </div>






<!-- Modal -->
<div class="sign_up_modal modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
	  	<div class="modal-dialog modal-dialog-centered" role="document">
	    	<div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close close_subscription_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      	</div>
				<div class="tab-content" id="myTabContent">
				  	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
						<div class="login_form">
							<form action="<?= current_url()?>" method="POST">
								<div class="heading">
                                    <p class="text-center">Do you wish to Subscribe to this plan?</p>
                                </div>
                                <input type="hidden" class="subscription_input" value="">
                                <button type="submit"  name="subscribe" class="subcribe_now_btn" style="display: none;"></button>
								<button type="submit" class="btn btn-log btn-block btn-thm2" id="subscription_btn_now">Pay now</button>
							</form>
						</div>
				  	</div>
				</div>
	    	</div>
	  	</div>
	</div>





<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>



















    <script>
$(document).ready(function(){
// =====================================
//      SUBSCRIBE 
// ====================================
$(".subscription_open_btn").click(function(e){
     e.preventDefault();
     var sub_id = $(this).attr('id');
    $(".subscription_input").val(sub_id);

});


// =====================================
//      EMPLOYER PAY NOW 
// ====================================
$('#subscription_btn_now').click(function(e){
    e.preventDefault();
    var sub_id = $(".subscription_input").val();
    var url = $(".ajax_url_page").attr('href');

    $(".preloader-container").show() //show preloader
    $(".close_subscription_btn").click();

    $.ajax({
        url: url,
        method: 'post',
        data: {
            sub_id: sub_id,
            subscribe_now: 'subscribe_now'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.login){
                location.assign(data.login.login)
            }else if(data.data){
                $(".subcribe_now_btn").click();
            }else{
                console.log('something went wrong');
            }
        }
    });
});







// ========================================
// REMOVE PRELOADER
// ========================================
// function remove_preloader(){
//     setTimeout(function(){
//         $(".preloader-container").hide();
//         $(".alert-success").hide();
//     }, 2000);
// }





});
</script>
