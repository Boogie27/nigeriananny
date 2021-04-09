<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/general-settings');
  return view('/admin/login');
}


// smtp = smtp.gmail.com
// smtp port = 465
// smtp_username: anonyecharles@gmail.com





// =======================================
// app banner settings
// =======================================
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
                    <?php if(Session::has('success')): ?>
                        <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Banner settings</h4>
							<ol class="breadcrumb float-right">
								<li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Banner settings</li>
							</ol>
                        </nav>
                    </div>
                    <div class="col-lg-12"> <!--content start -->
                        <form action="<?= current_url() ?>" method="post" class="">
                            <div class="form-sm"> <!-- home banner start-->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for=""><b>Home banner:</b></label>
                                            <div class="alert alert-danger text-center" id="home_banner_alert" style="display: none;"></div>
                                            <div class="banner-image" id="home_banner_img_x">
                                                <?php if($settings->job_banner):?>
                                                    <img src="<?= asset($settings->job_banner) ?>" alt="<?=$settings->app_name ?>">
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
                                            <label for=""><b>Under construction:</b></label>
                                            <div class="alert alert-danger text-center" id="coonstruction_banner_alert" style="display: none;"></div>
                                            <div class="banner-image" id="construction_banner_img_x">
                                                <?php if($settings->construction_banner):?>
                                                    <img src="<?= asset($settings->construction_banner) ?>" alt="<?=$settings->app_name ?>">
                                                <?php else: ?>
                                                    <a href="#" class="construction_banner_img_update"><i class="fa fa-camera"></i></a>
                                                <?php endif; ?>
                                            </div>
                                            <div class="img-btns text-center">
                                                <input type="file" id="construction_banner_img_input" class="" style="display: none;">
                                                <label class="upload_img_icon"><a href="#" class="construction_banner_img_update"><i class="fa fa-pencil"></i></a></label>
                                                <label class="upload_img_icon"><a href="#" data-toggle="modal" data-target="#exampleModal_construction_delete"><i class="fa fa-trash"></i></a></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- home banner end-->
                        </form>
                    </div> <!-- content end-->
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








<!-- Modal construction banner delete -->
<div class="sign_up_modal modal fade" id="exampleModal_construction_delete" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <p class="text-center">Do you wish to delete construction banner?</p>
                            </div>
                            <button type="button" id="construction_banner_img_delete" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>





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







// ============================================
// UPDATE CONSTRUCTION BANNER IMAGE
// ============================================
$(".construction_banner_img_update").click(function(e){
	e.preventDefault();
	$("#construction_banner_img_input").click();
});



$("#construction_banner_img_input").on('change', function(e){
	$(".alert_all").html('');
	var url = $(".ajax_url_tag").attr('href');
	var image = $("#construction_banner_img_input");
	$('#coonstruction_banner_alert').hide();

	var data = new FormData();
	var image = $(image)[0].files[0];

	$(".preloader-container").show() //show preloader

    data.append('construction_banner', image);
    data.append('update_construction_banner_image', true);

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
				$('#coonstruction_banner_alert').show();
				$('#coonstruction_banner_alert').html(data.error.home_banner);
           }else if(data.data){
                get_construction_banner_img();
           }else{
				remove_preloader();
				$('#coonstruction_banner_alert').show();
				$('#coonstruction_banner_alert').html('*Network error, try again later!');
		   }
		   console.log(response)
		   $("#home_banner_img_input").val('');
		},
		error: function(){
			remove_preloader();
			$('#coonstruction_banner_alert').show();
			$('#coonstruction_banner_alert').html('*Network error, try again later!');
		}
    });
});




// ========================================
// GET CONSTRUCTION BANNER IMAGE
// ========================================
function get_construction_banner_img(){
	var url = $(".ajax_url_tag").attr('href');

	$.ajax({
        url: url,
        method: "post",
        data: {
			get_construction_banner_img: 'get_construction_banner_img'
		},
        success: function (response){
		   remove_preloader();
		   $("#construction_banner_img_x").html(response)
        }
    });
}






// ========================================
// DELETE CONSTRUCTION BANNER IMAGE
// ========================================
$("#construction_banner_img_delete").click(function(e){
	e.preventDefault();
	var url = $(".ajax_url_tag").attr('href');
	$('#coonstruction_banner_alert').hide();
	$(".preloader-container").show() //show preloader
	$(".modal_promt_close").click();
	
	$.ajax({
        url: url,
        method: "post",
        data: {
			delete_construction_banner_img: 'delete_construction_banner_img'
		},
        success: function (response){
			var data = JSON.parse(response);
			if(data.error){
				remove_preloader();
				$('#coonstruction_banner_alert').show();
				$('#coonstruction_banner_alert').html('*Network error, try again later!');
			}else if(data.data){
                get_construction_banner_img();
			}
		},
		error: function(){
				$('#coonstruction_banner_alert').show();
				$('#coonstruction_banner_alert').html('*Network error, try again later!');
			}
    });
});







// end
});
</script>