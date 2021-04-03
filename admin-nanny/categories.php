<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/employees');
  return view('/admin/login');
}


// =============================================
// app banner settings
// =============================================
$banner =  $connection->select('settings')->where('id', 1)->first();

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
                            <h4 class="title float-left">Manage categories</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item active" aria-current="page"><a href="#" data-toggle="modal" data-target="#exampleModal_category_add" class="view-btn-fill">Add categories</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Category name</th>
                                    <th scope="col">Category slug</th>
                                    <th scope="col">Fetaure</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php $categories = $connection->select('job_categories')->paginate(15);
                                if($categories->result()): 
                                foreach($categories->result() as $category):    
                                ?>
                                    <tr>
                                        <td><?= ucfirst($category->category_name) ?></td>
                                        <td><?= ucfirst($category->category_slug) ?></td>
                                      
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  data-id="<?= $category->job_category_id  ?>" class="custom-control-input categories_feature_btn" id="customSwitch_<?= $category->job_category_id  ?>" <?= $category->is_category_featured ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $category->job_category_id  ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($category->date_added)) ?></td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#exampleModal_category_edit" id="<?= $category->job_category_id  ?>" data-category="<?= $category->category_name ?>" class="edit_category_btn" title="Edit employee"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal" id="<?= $category->job_category_id  ?>" data-target="#exampleModal_category_delete" class="delete_category_btn" title="Delete customer"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $categories->links(); ?>

                                <?php if(!$categories->result()): ?>
                                    <div class="empty-table">There are no categories yet!</div>
                                <?php endif; ?>
                            </div>
                        </div><!-- table end-->
                    </div>
                </div>
                <div class="row mt50 mb50">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="copyright-widget text-center">
                            <p class="color-black2"><?= $banner->alrights ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>










<!-- Modal edit category-->
<div class="sign_up_modal modal fade" id="exampleModal_category_add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_category_add_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="top_alert_modal alert-danger text-center p-2 mb-2" style="display: none;"></div>
                            <div class="sr-head text-center"><h4>Create new category</h4></div><br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" id="category_category_name_input" class="form-control h50" value="">
                                    </div>
                                </div>
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_category_add_btn" class="btn btn-fill btn-log btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








<!-- Modal edit category-->
<div class="sign_up_modal modal fade" id="exampleModal_category_edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_category_edit_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="top_alert_modal alert-danger text-center p-2 mb-2" style="display: none;"></div>
                            <div class="sr-head text-center"><h4>Update category</h4></div><br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" id="category_name_input" class="form-control h50" value="">
                                        <input type="hidden" id="category_id_edit_input" value="">
                                    </div>
                                </div>
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_category_edit_btn" class="btn btn-fill btn-log btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<!-- Modal delete -->
<div class="sign_up_modal modal fade" id="exampleModal_category_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_delete_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete this category?</p>
                                <input type="hidden" id="category_id_input" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_category_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>













<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){

// ==========================================
// CATEGORY FEATURE BUTTON
// ==========================================
$('.categories_feature_btn').click(function(e){
    var url = $(".ajax_url_page").attr('href');
    var id = $(this).attr('data-id');
    $('.page_alert_danger').hide();
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			category_id: id,
			is_category_feature: 'is_category_feature'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(!data.data){
                $('.page_alert_danger').show();
                $('.page_alert_danger').html('*Network error, try again later');
            }
            remove_preloader();
		}
	});
});







// ==========================================
// OPEN DELETE EMPLOYER MODAL
// ==========================================
$(".delete_category_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    $("#category_id_input").val(id);
    $('.page_alert_danger').hide();
});





// ========================================
// DELETE CATEGORY
// ========================================
$("#submit_category_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#category_id_input").val();
     var url = $(this).attr('data-url');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			category_id: id,
			delete_category_action: 'delete_category_action'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }else{
                remove_preloader();
                $('.page_alert_danger').show();
                $('.page_alert_danger').html('*Network error, try again later');
            }
		}
	});
});




// ========================================
// REMOVE PRELOADER
// ========================================
function remove_preloader(){
    $(".preloader-container").show();
    setTimeout(function(){
        $(".preloader-container").hide();
        $(".alert-success").hide();
    }, 2000);
}







// ==========================================
// OPEN EDIT CATEGORY MODAL
// ==========================================
$(".edit_category_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    var category = $(this).attr('data-category');
    $("#category_id_edit_input").val(id);
    $("#category_name_input").val(category)
    $('.page_alert_danger').hide();
    $(".top_alert_modal").hide();
});




// =========================================
// PRESS ENTER BUTTON TO EDIT CATEGORY
// =========================================
$("#category_name_input").keypress(function(e){
    if(e.keyCode == 13 || e.which == 13){
        edit_category();
    }
});


// ==========================================
// EDIT CATEGORY
// ==========================================
$("#submit_category_edit_btn").click(function(e){
    e.preventDefault();
    edit_category();
});



function edit_category(){
    var id = $("#category_id_edit_input").val();
     var category = $("#category_name_input").val();

     
     var url = $('.ajax_url_page').attr('href')
     $(".top_alert_modal").hide();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			category_id: id,
            category: category,
			category_edit_action: 'category_edit_action'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                $(".top_alert_modal").show();
                $(".top_alert_modal").html(data.error.category);
            }else if(data.data){
                remove_preloader();
                $("#modal_category_edit_close").click();
                location.reload();
            }else{
                remove_preloader();
                $("#modal_category_edit_close").click();
                $('.page_alert_danger').show();
                $('.page_alert_danger').html('*Network error, try again later');
            }
		}
	});
}










// =========================================
// PRESS ENTER BUTTON TO EDIT CATEGORY
// =========================================
$("#category_category_name_input").keypress(function(e){
    if(e.keyCode == 13 || e.which == 13){
        add_category();
    }
});


// ==========================================
// EDIT CATEGORY
// ==========================================
$("#submit_category_add_btn").click(function(e){
    e.preventDefault();
    add_category();
});



function add_category(){
     var category = $("#category_category_name_input").val();
     var url = $('.ajax_url_page').attr('href')
     $(".top_alert_modal").hide();
    //  $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
            category: category,
			add_new_category: 'add_new_category'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                $(".top_alert_modal").show();
                $(".top_alert_modal").html(data.error.category);
            }else if(data.data){
                remove_preloader();
                $("#modal_category_add_close").click();
                location.reload();
            }else{
                remove_preloader();
                $("#modal_category_edit_close").click();
                $('.page_alert_danger').show();
                $('.page_alert_danger').html('*Network error, try again later');
            }
		}
	});
}






// end
});
</script>