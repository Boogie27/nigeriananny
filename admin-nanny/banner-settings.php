<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/banner-settings');
  return view('/admin/login');
}

    



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
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
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
                            <!-- slider start -->
                            <div class="form-sm">
                               <div class="slider-banner">
                                    <label for=""><b>Home slider:</b></label>
                                    <div class="row" id="main_slider_banner_parant">
                                        <?php if($settings->sliders):
                                        $sliders = json_decode($settings->sliders, true); 
                                        foreach($sliders as $key => $slider):   
                                        ?>
                                            <div class="col-lg-6 col-md-6 col-sm-6"><!-- banner start-->
                                                <div class="home-slider-body">
                                                    <a href="#" data-key="<?= $key ?>" data-toggle="modal" data-target="#exampleModal_slide_app_slider_delete" class="slider-banner-delete-btn"><i class="fa fa-times"></i></a>
                                                    <img src="<?= asset($slider['image']) ?>" alt="slider" class="slider-img">
                                                    <ul>
                                                        <li><b>Header: </b><?= $slider['title']?></li>
                                                        <li><b>Paragraph: </b><?= $slider['body']?></li>
                                                        <li><b>Button:</b> <?= $slider['button']?></li>
                                                        <li><b>Link:</b> <span class="text-primary"><?= $slider['link']?></span></li>
                                                    </ul>
                                                </div>
                                            </div><!-- banner end-->
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                        <div class="col-lg-6 col-md-6 col-sm-6"><!-- banner start-->
                                            <div class="home-slider-body text-center">
                                                 <div class="icon-camera">
                                                    <a href="#" data-toggle="modal" data-target="#exampleModal_add_app_slider_open" class="slider-banner-icon"><i class="fa fa-camera"></i></a>
                                                 </div>
                                            </div>
                                        </div><!-- banner end-->
                                    </div>
                               </div>
                            </div>
                            <!-- slider end -->

                              <div class="form-sm"> <!-- construction banner start-->
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
                            </div> <!-- construction banner end-->
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
<div class="sign_up_modal modal fade" id="exampleModal_slide_app_slider_delete" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <p class="text-center">Do you wish to delete this slider?</p>
                                <input type="hidden" id="slider_delete_image_input" value="">
                            </div>
                            <button type="button" id="slider_image_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete slider</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal home banner delete -->
<div class="sign_up_modal modal fade" id="exampleModal_add_app_slider_open" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal_promt_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url()?>" method="post">
                            <div class="">
                                <h4 class="text-center">Add app slider</h4>
                                <div class="alert_label alert_0 text-danger text-center"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="alert_label alert_1 text-danger"></div>
                                        <label for="">Header</label>
                                        <input type="text" id="slider_header_input" class="form-control h50" value="" placeholder="Slider header">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="alert_label alert_2 text-danger"></div>
                                        <label for="">link:</label>
                                        <input type="text" id="slider_link_input" class="form-control h50" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="alert_label alert_3 text-danger"></div>
                                        <label for="">Button text:</label>
                                        <input type="text" id="slider_btn_text_input" class="form-control h50" value="">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="alert_label alert_4 text-danger"></div>
                                        <label for="">Paragraph:</label>
                                       <textarea id="slider_paragraph_input"  class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="alert_label alert_5 text-danger"></div>
                                        <input type="file" id="slide_image_input" style="display: none;">
                                        <div class="text-center slider-modal-icon">
                                            <a href="#" id="slider_banner_img_open"><i class="fa fa-camera"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="slider_banner_img_submit" class="btn bg-primary btn-log btn-block" style="color: #fff;">Upload slider</button>
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








// ********** OPEN SLIDER FILE INPUT ********//
$("#slider_banner_img_open").click(function(e){
    e.preventDefault()
    $("#slide_image_input").click()
})

// *********** UPLOAD SLIDER IMAGE **********//
$("#slider_banner_img_submit").click(function(e){
    e.preventDefault()
    upload_slide_image()
})


function upload_slide_image(){
    $('.alert_label').html('')
    var url = $(".ajax_url_tag").attr('href');
    var header = $("#slider_header_input").val()
    var link = $("#slider_link_input").val()
    var button = $("#slider_btn_text_input").val()
    var paragraph = $("#slider_paragraph_input").val()
    var image = $("#slide_image_input")
    $("#slider_banner_img_submit").html("Please wait...")

    if(valiadate_slide_form(header, link, button, paragraph)){
        $("#slider_banner_img_submit").html("Upload image")
        return;
    }

    var data = new FormData();
	var image = $(image)[0].files[0];

    data.append('header', header);
    data.append('link', link);
    data.append('button', button);
    data.append('image', image);
    data.append('paragraph', paragraph);
    data.append('upload_slide_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
           if(data.img_error){
               $(".alert_5").html(data.img_error.image)
               $("#slider_banner_img_submit").html("Upload image")
           }else if(data.data){
               get_all_sliders()
           }
		},
        error: function(){
            $('.alert_0').html('Network error, try again later!')
            $("#slider_banner_img_submit").html("Upload image")
        }
    });

}





function  valiadate_slide_form(header, link, button, paragraph){
    var state = false;
    if(header == ''){
        state = true;
        $(".alert_1").html('*Header field is required')
    }else if(header.length < 6){
        state = true;
        $(".alert_1").html('*Minimum of 6 characters')
    }else if(header.length > 50){
        state = true;
        $(".alert_1").html('*Maxmum of 15 characters')
    }

    if(link && link.length > 50){
        state = true;
        $(".alert_2").html('*Maxmum of 50 characters')
    }
    if(button && button.length > 50){
        state = true;
        $(".alert_3").html('*Maxmum of 50 characters')
    }

    if(paragraph == ''){
        state = true;
        $(".alert_4").html('*Paragraph field is required')
    }else if(paragraph.length < 10){
        state = true;
        $(".alert_4").html('*Minimum of 10 characters')
    }else if(paragraph.length > 200){
        state = true;
        $(".alert_4").html('*Maxmum of 50 characters')
    }

    return state;
}




// ******** GET ALL SLIDERS *****//
function get_all_sliders(){
    var url = $(".ajax_url_tag").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
			get_all_slider_banner: 'get_all_slider_banner'
		},
        success: function (response){
            $(".modal_promt_close").click();
			$("#main_slider_banner_parant").html(response)
            $("#slider_banner_img_submit").html("Upload image")
		}
    });
}








// ************* DELETE SLIDER OPEN MODAL ***************//
$("#main_slider_banner_parant").on('click', '.slider-banner-delete-btn', function(e){
    e.preventDefault()
    var key = $(this).attr('data-key')
    $("#slider_delete_image_input").val(key)
})




// ************* DELETE SLIDER ***************//
$("#slider_image_delete_btn").click(function(e){
    e.preventDefault()
    var key = $("#slider_delete_image_input").val()
    var url = $(".ajax_url_tag").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
            key: key,
			delete_slider_banner_action: 'delete_slider_banner_action'
		},
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                get_all_sliders()
            }
            $(".modal_promt_close").click();
            console.log(response)
		}
    });
})












// end
});
</script>