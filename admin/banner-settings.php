<?php include('../Connection.php');  ?>

<?php

if(Input::post('update_email_settings'))
{
	$validate = new DB();
       
	$validation = $validate->validate([
		'from_name' => 'required|min:1|max:50',
		'from_email' => 'required',
		'smtp_host' => 'required|min:3|max:50',
		'smtp_port' => 'required|number',
		'smtp_username' => 'required|min:3|max:200',
		'smtp_password' => 'required|min:6|max:12',
	]);
	
	if($validation->passed())
	{
		$connection = new DB();
		$update_settings = $connection->update('settings', [
						'from_name' => Input::get('from_name'),
						'from_email' => Input::get('from_email'),
						'smtp_host' => Input::get('smtp_host'),
						'smtp_port' => Input::get('smtp_port'),
						'smtp_username' => Input::get('smtp_username'),
						'smtp_password' => Input::get('smtp_password'),
					])->where('id', 1)->save();
		if($update_settings)
		{
			Session::flash('success', 'Email settings has been updated successfully!');
			return back();
		}   
	}
}



// app banner settings
$settings =  $connection->select('settings')->where('id', 1)->first();



?>



	<?php include('includes/header.php'); ?>

	<!-- Main Header Nav -->
    <?php include('includes/navigation.php') ?>

	<!-- Main Header Nav For Mobile -->
	<?php include('includes/mobile-navigation.php') ?>


	<!-- Our Dashbord Sidebar -->
	<?php include('includes/side-navigation.php') ?>
	

	<!-- Our Dashbord -->
	<div class="our-dashbord dashbord">
		<div class="dashboard_main_content">
			<div class="container-fluid">
				<div class="main_content_container">
					<div class="row">
						<div class="col-lg-12">
							<?php include('includes/mobile-drop-nav.php') ?><!-- mobile-navigation -->
						</div>
						<div class="col-lg-12">
							<nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
								<h4 class="title float-left">App Banner Settings</h4>
								<ol class="breadcrumb float-right">
							    	<li class="breadcrumb-item"><a href="#">Home</a></li>
							    	<li class="breadcrumb-item active" aria-current="page">Settings</li>
								</ol>
							</nav>
						</div>
						<div class="col-lg-12">
							<form action="<?= current_url() ?>" method="post" class="">
								<div class="form-sm"> <!-- home banner start-->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label for=""><b>Home banner:</b></label>
												<div class="alert alert-danger text-center" id="home_banner_alert" style="display: none;"></div>
												<div class="banner-image" id="home_banner_img_x">
													<?php if($settings->home_banner):?>
														<img src="<?= asset($settings->home_banner) ?>" alt="<?=$settings->app_name ?>">
													<?php else: ?>
														<a href="#" class="home_banner_img_update"><i class="fa fa-camera"></i></a>
													<?php endif; ?>
												</div>
	                                            <div class="img-btns text-center">
													<input type="file" id="home_banner_img_input" class="" style="display: none;">
													<label class="upload_img_icon"><a href="#" class="home_banner_img_update"><i class="fa fa-pencil"></i></a></label>
													<label class="upload_img_icon"><a href="#" data-toggle="modal" data-target="#exampleModal_homebanner_delete"><i class="fa fa-trash"></i></a></label>
												</div>
											</div>
										</div>
							    	</div>
								</div> <!-- home banner end-->

								<div class="form-sm"> <!-- home banner start-->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label for=""><b>Category banner:</b></label>
												<div class="alert alert-danger text-center" id="category_banner_alert" style="display: none;"></div>
												<div class="banner-image" id="category_banner_img_x">
													<?php if($settings->category_banner):?>
														<img src="<?= asset($settings->category_banner) ?>" alt="<?= $settings->app_name?>">
													<?php else: ?>
														<a href="#"  class="category_banner_img_update"></a><i class="fa fa-camera"></i></a>
													<?php endif; ?>
												</div>
	                                            <div class="img-btns text-center">
													<input type="file" id="category_banner_img_input" style="display: none;">
													<label class="upload_img_icon"><a href="#" class="category_banner_img_update"><i class="fa fa-pencil"></i></a></label>
													<label class="upload_img_icon"><a href="#" data-toggle="modal" data-target="#exampleModal_categorybanner_delete"><i class="fa fa-trash"></i></a></label>
												</div>
											</div>
										</div>
							    	</div>
								</div> <!-- home banner end-->
								
								<div class="form-sm"> <!-- home banner start-->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label for=""><b>Cart banner:</b></label>
												<div class="alert alert-danger text-center" id="cart_banner_alert" style="display: none;"></div>
												<div class="banner-image" id="cart_banner_img_x">
													<?php if($settings->cart_banner):?>
														<img src="<?= asset($settings->cart_banner) ?>" alt="<?= $settings->app_name?>">
													<?php else: ?>
														<a href="#"  class="cart_banner_img_update"></a><i class="fa fa-camera"></i></a>
													<?php endif; ?>
												</div>
	                                            <div class="img-btns text-center">
													<input type="file" id="cart_banner_img_input" style="display: none;">
													<label class="upload_img_icon"><a href="#" class="cart_banner_img_update"><i class="fa fa-pencil"></i></a></label>
													<label class="upload_img_icon"><a href="#" data-toggle="modal" data-target="#exampleModal_cartbanner_delete"><i class="fa fa-trash"></i></a></label>
												</div>
											</div>
										</div>
							    	</div>
								</div> <!-- home banner end-->

								<div class="form-sm"> <!-- home banner start-->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label for=""><b>Form banner:</b></label>
												<div class="alert alert-danger text-center" id="form_banner_alert" style="display: none;"></div>
												<div class="banner-image" id="form_banner_img_x">
													<?php if($settings->form_banner):?>
														<img src="<?= asset($settings->form_banner) ?>" alt="<?= $settings->app_name?>">
													<?php else: ?>
														<a href="#"  class="form_banner_img_update"></a><i class="fa fa-camera"></i></a>
													<?php endif; ?>
												</div>
	                                            <div class="img-btns text-center">
													<input type="file" id="form_banner_img_input" style="display: none;">
													<label class="upload_img_icon"><a href="#" class="form_banner_img_update"><i class="fa fa-pencil"></i></a></label>
													<label class="upload_img_icon"><a href="#" data-toggle="modal" data-target="#exampleModal_formbanner_delete"><i class="fa fa-trash"></i></a></label>
												</div>
											</div>
										</div>
							    	</div>
								</div> <!-- home banner end-->


								<div class="form-sm"> <!-- home banner start-->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label for=""><b>Checkout banner:</b></label>
												<div class="alert alert-danger text-center" id="checkout_banner_alert" style="display: none;"></div>
												<div class="banner-image" id="checkout_banner_img_x">
													<?php if($settings->checkout_banner):?>
														<img src="<?= asset($settings->checkout_banner) ?>" alt="<?= $settings->app_name?>">
													<?php else: ?>
														<a href="#"  class="checkout_banner_img_update"></a><i class="fa fa-camera"></i></a>
													<?php endif; ?>
												</div>
	                                            <div class="img-btns text-center">
													<input type="file" id="checkout_banner_img_input" style="display: none;">
													<label class="upload_img_icon"><a href="#" class="checkout_banner_img_update"><i class="fa fa-pencil"></i></a></label>
													<label class="upload_img_icon"><a href="#" data-toggle="modal" data-target="#exampleModal_checkout_delete"><i class="fa fa-trash"></i></a></label>
												</div>
											</div>
										</div>
							    	</div>
								</div> <!-- home banner end-->

							</form>
						</div>
					</div>
				
					<div class="row mt50 mb50">
						<div class="col-lg-6 offset-lg-3">
							<div class="copyright-widget text-center">
								<p class="color-black2"><?= $settings->alrights ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>





<!-- Modal home banner delete -->
<div class="sign_up_modal modal fade" id="exampleModal_homebanner_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal_promt_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete home banner?</p>
                            </div>
                            <button type="button" id="home_banner_img_delete" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<!-- Modal category banner delete -->
<div class="sign_up_modal modal fade" id="exampleModal_categorybanner_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal_promt_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete category banner?</p>
                            </div>
                            <button type="button" id="category_banner_img_delete" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal category banner delete -->
<div class="sign_up_modal modal fade" id="exampleModal_cartbanner_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal_promt_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete cart banner?</p>
                            </div>
                            <button type="button" id="cart_banner_img_delete" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>











<!-- Modal form banner delete -->
<div class="sign_up_modal modal fade" id="exampleModal_formbanner_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal_promt_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete form banner?</p>
                            </div>
                            <button type="button" id="form_banner_img_delete" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>












<!-- Modal checkout banner delete -->
<div class="sign_up_modal modal fade" id="exampleModal_checkout_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal_promt_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete checkout banner?</p>
                            </div>
                            <button type="button" id="chcekout_banner_img_delete" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<a href="<?= url('/admin/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>






<?php  include('includes/footer.php') ?>









<script>
$(document).ready(function(){
// ============================================
// UPDATE HOME BANNER IMAGE
// ============================================
$(".home_banner_img_update").click(function(e){
	e.preventDefault();
	$("#home_banner_img_input").click();
});



$("#home_banner_img_input").on('change', function(e){
	$(".alert_all").html('');
	var url = $(".ajax_url_tag").attr('href');
	var image = $("#home_banner_img_input");
	$('#home_banner_alert').hide();

	var data = new FormData();
	var image = $(image)[0].files[0];

	$(".preloader-container").show() //show preloader

    data.append('home_banner', image);
    data.append('update_home_banner_image', true);

	$.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
           if(data.error){
				remove_preloader();
				$('#home_banner_alert').show();
				$('#home_banner_alert').html(data.error.home_banner);
           }else if(data.data){
                get_home_banner_img();
           }else{
				remove_preloader();
				$('#home_banner_alert').show();
				$('#home_banner_alert').html('*Network error, try again later!');
		   }
		   console.log(response)
		   $("#home_banner_img_input").val('');
		},
		error: function(){
			remove_preloader();
			$('#home_banner_alert').show();
			$('#home_banner_alert').html('*Network error, try again later!');
		}
    });
});




// ========================================
// GET HOME BANNER IMAGE
// ========================================
function get_home_banner_img(){
	var url = $(".ajax_url_tag").attr('href');

	$.ajax({
        url: url,
        method: "post",
        data: {
			get_home_banner_img: 'get_home_banner_img'
		},
        success: function (response){
		   remove_preloader();
		   $("#home_banner_img_x").html(response)
        }
    });
}





// ========================================
// DELETE HOME BANNER IMAGE
// ========================================
$("#home_banner_img_delete").click(function(e){
	e.preventDefault();
	var url = $(".ajax_url_tag").attr('href');
	$('#home_banner_alert').hide();
	$('#home_banner_alert').hide();
	$(".preloader-container").show() //show preloader
	$(".modal_promt_close").click();
	
	$.ajax({
        url: url,
        method: "post",
        data: {
			delete_home_banner_img: 'delete_home_banner_img'
		},
        success: function (response){
			var data = JSON.parse(response);
			if(data.error){
				remove_preloader();
				$('#home_banner_alert').show();
				$('#home_banner_alert').html('*Network error, try again later!');
			}else if(data.data){
				get_home_banner_img();
			}
		},
		error: function(){
				$('#home_banner_alert').show();
				$('#home_banner_alert').html('*Network error, try again later!');
			}
    });
});



// ========================================
// REMOVE PRELOADER
// ========================================
function remove_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
        $(".alert-success").hide();
    }, 2000);
}





// =======================================
// OPEN CATEGORY BANNER IMAGE
// =======================================
$(".category_banner_img_update").click(function(e){
	e.preventDefault();
	$("#category_banner_img_input").click();
});




// =======================================
// UPDATE CATEGORY BANNER IMAGE
// =======================================
$("#category_banner_img_input").on('change', function(e){
	$(".alert_all").html('');
	var url = $(".ajax_url_tag").attr('href');
	var image = $("#category_banner_img_input");
	$('#category_banner_alert').hide();

	var data = new FormData();
	var image = $(image)[0].files[0];

	$(".preloader-container").show() //show preloader

    data.append('category_banner', image);
    data.append('update_category_banner_image', true);

	$.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
           if(data.error){
				remove_preloader();
				$('#category_banner_alert').show();
				$('#category_banner_alert').html(data.error.category_banner);
           }else if(data.data){
				get_category_banner_img();
           }else{
				remove_preloader();
				$('#category_banner_alert').show();
				$('#category_banner_alert').html('*Network error, try again later!');
		   }
		   console.log(response)
		   $("#category_banner_img_input").val('');
		},
		error: function(){
			remove_preloader();
			$('#category_banner_alert').show();
			$('#category_banner_alert').html('*Network error, try again later!');
		}
    });
});







// ========================================
// GET CATEGORY BANNER IMAGE
// ========================================
function get_category_banner_img(){
	var url = $(".ajax_url_tag").attr('href');

	$.ajax({
        url: url,
        method: "post",
        data: {
			get_category_banner_img: 'get_category_banner_img'
		},
        success: function (response){
		   remove_preloader();
		   $("#category_banner_img_x").html(response)
        }
    });
}







// =========================================
// DELETE CATEGORY BANNER
// =========================================
$("#category_banner_img_delete").click(function(e){
	e.preventDefault();
	var url = $(".ajax_url_tag").attr('href');
	$('#category_banner_alert').hide();
	$('#category_banner_alert').hide();
	$(".preloader-container").show() //show preloader
	$(".modal_promt_close").click();
	
	$.ajax({
        url: url,
        method: "post",
        data: {
			delete_category_banner_img: 'delete_category_banner_img'
		},
        success: function (response){
			var data = JSON.parse(response);
			if(data.error){
				remove_preloader();
				$('#category_banner_alert').show();
				$('#category_banner_alert').html('*Network error, try again later!');
			}else if(data.data){
				get_category_banner_img();
			}
		},
		error: function(){
				$('#category_banner_alert').show();
				$('#category_banner_alert').html('*Network error, try again later!');
			}
    });
});






// ============================================
// UPDATE CART BANNER IMAGE
// ============================================
$(".cart_banner_img_update").click(function(e){
	e.preventDefault();
	$("#cart_banner_img_input").click();
});


$("#cart_banner_img_input").on('change', function(e){
	$(".alert_all").html('');
	var url = $(".ajax_url_tag").attr('href');
	var image = $("#cart_banner_img_input");
	$('#cart_banner_alert').hide();

	var data = new FormData();
	var image = $(image)[0].files[0];

	$(".preloader-container").show() //show preloader

    data.append('cart_banner', image);
    data.append('update_cart_banner_image', true);

	$.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
           if(data.error){
				remove_preloader();
				$('#cart_banner_alert').show();
				$('#cart_banner_alert').html(data.error.cart_banner);
           }else if(data.data){
                get_cart_banner_img();
           }else{
				remove_preloader();
				$('#cart_banner_alert').show();
				$('#cart_banner_alert').html('*Network error, try again later!');
		   }
		   console.log(response)
		   $("#cart_banner_img_input").val('');
		},
		error: function(){
			remove_preloader();
			$('#cart_banner_alert').show();
			$('#cart_banner_alert').html('*Network error, try again later!');
		}
    });
});




// ========================================
// GET CART BANNER IMAGE
// ========================================
function get_cart_banner_img(){
	var url = $(".ajax_url_tag").attr('href');

	$.ajax({
        url: url,
        method: "post",
        data: {
			get_cart_banner_img: 'get_cart_banner_img'
		},
        success: function (response){
		   remove_preloader();
		   $("#cart_banner_img_x").html(response)
        }
    });
}








// =========================================
// DELETE CART BANNER
// =========================================
$("#cart_banner_img_delete").click(function(e){
	e.preventDefault();
	var url = $(".ajax_url_tag").attr('href');
	$('#cart_banner_alert').hide();
	$('#cart_banner_alert').hide();
	$(".preloader-container").show() //show preloader
	$(".modal_promt_close").click();
	
	$.ajax({
        url: url,
        method: "post",
        data: {
			delete_cart_banner_img: 'delete_cart_banner_img'
		},
        success: function (response){
			var data = JSON.parse(response);
			if(data.error){
				remove_preloader();
				$('#cart_banner_alert').show();
				$('#cart_banner_alert').html('*Network error, try again later!');
			}else if(data.data){
				get_cart_banner_img();
			}
		},
		error: function(){
				$('#cart_banner_alert').show();
				$('#cart_banner_alert').html('*Network error, try again later!');
			}
    });
});








// ============================================
// UPDATE FORM BANNER IMAGE
// ============================================
$(".form_banner_img_update").click(function(e){
	e.preventDefault();
	$("#form_banner_img_input").click();
});


$("#form_banner_img_input").on('change', function(e){
	$(".alert_all").html('');
	var url = $(".ajax_url_tag").attr('href');
	var image = $("#form_banner_img_input");
	$('#form_banner_alert').hide();

	var data = new FormData();
	var image = $(image)[0].files[0];

	$(".preloader-container").show() //show preloader

    data.append('form_banner', image);
    data.append('update_form_banner_image', true);

	$.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
           if(data.error){
				remove_preloader();
				$('#form_banner_alert').show();
				$('#form_banner_alert').html(data.error.form_banner);
           }else if(data.data){
			get_form_banner_img();
           }else{
				remove_preloader();
				$('#form_banner_alert').show();
				$('#form_banner_alert').html('*Network error, try again later!');
		   }
		   console.log(response)
		   $("#form_banner_img_input").val('');
		},
		error: function(){
			remove_preloader();
			$('#form_banner_alert').show();
			$('#form_banner_alert').html('*Network error, try again later!');
		}
    });
});




// ========================================
// GET FORM BANNER IMAGE
// ========================================
function get_form_banner_img(){
	var url = $(".ajax_url_tag").attr('href');

	$.ajax({
        url: url,
        method: "post",
        data: {
			get_form_banner_img: 'get_form_banner_img'
		},
        success: function (response){
		   remove_preloader();
		   $("#form_banner_img_x").html(response)
        }
    });
}









// =========================================
// DELETE FORM BANNER
// =========================================
$("#form_banner_img_delete").click(function(e){
	e.preventDefault();
	var url = $(".ajax_url_tag").attr('href');
	$('#form_banner_alert').hide();
	$(".preloader-container").show() //show preloader
	$(".modal_promt_close").click();
	
	$.ajax({
        url: url,
        method: "post",
        data: {
			delete_form_banner_img: 'delete_form_banner_img'
		},
        success: function (response){
			var data = JSON.parse(response);
			if(data.error){
				remove_preloader();
				$('#form_banner_alert').show();
				$('#form_banner_alert').html('*Network error, try again later!');
			}else if(data.data){
				get_form_banner_img();
			}
		},
		error: function(){
				$('#form_banner_alert').show();
				$('#form_banner_alert').html('*Network error, try again later!');
			}
    });
});










// ============================================
// UPDATE CHECKOUT BANNER IMAGE
// ============================================
$(".checkout_banner_img_update").click(function(e){
	e.preventDefault();
	$("#checkout_banner_img_input").click();
});


$("#checkout_banner_img_input").on('change', function(e){
	$(".alert_all").html('');
	var url = $(".ajax_url_tag").attr('href');
	var image = $("#checkout_banner_img_input");
	$('#checkout_banner_alert').hide();

	var data = new FormData();
	var image = $(image)[0].files[0];

	$(".preloader-container").show() //show preloader

    data.append('checkout_banner', image);
    data.append('update_chcekout_banner_image', true);

	$.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
           if(data.error){
				remove_preloader();
				$('#checkout_banner_alert').show();
				$('#checkout_banner_alert').html(data.error.checkout_banner);
           }else if(data.data){
				get_checkout_banner_img();
           }else{
				remove_preloader();
				$('#checkout_banner_alert').show();
				$('#checkout_banner_alert').html('*Network error, try again later!');
		   }
		   console.log(response)
		   $("#form_banner_img_input").val('');
		},
		error: function(){
			remove_preloader();
			$('#checkout_banner_alert').show();
			$('#checkout_banner_alert').html('*Network error, try again later!');
		}
    });
});




// ========================================
// GET FORM BANNER IMAGE
// ========================================
function get_checkout_banner_img(){
	var url = $(".ajax_url_tag").attr('href');

	$.ajax({
        url: url,
        method: "post",
        data: {
			get_checkout_banner_img: 'get_checkout_banner_img'
		},
        success: function (response){
		   remove_preloader();
		   $("#checkout_banner_img_x").html(response)
        }
    });
}












// =========================================
// DELETE FORM BANNER
// =========================================
$("#chcekout_banner_img_delete").click(function(e){
	e.preventDefault();
	var url = $(".ajax_url_tag").attr('href');
	$('#checkout_banner_alert').hide();
	$(".preloader-container").show() //show preloader
	$(".modal_promt_close").click();
	
	$.ajax({
        url: url,
        method: "post",
        data: {
			delete_checkout_banner_img: 'delete_checkout_banner_img'
		},
        success: function (response){
			var data = JSON.parse(response);
			if(data.error){
				remove_preloader();
				$('#checkout_banner_alert').show();
				$('#checkout_banner_alert').html('*Network error, try again later!');
			}else if(data.data){
				get_checkout_banner_img();
			}
		},
		error: function(){
				$('#checkout_banner_alert').show();
				$('#checkout_banner_alert').html('*Network error, try again later!');
			}
    });
});
















});
</script>