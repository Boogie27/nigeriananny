<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}

$connection = new DB();
$shop_categories = $connection->select('shop_categories');


if($search = Input::get('search'))
{
    $shop_categories->where('category_name', 'RLIKE', $search);
}



$shop_categories->paginate(50);





// app banner settings
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
                       <?php endif;?>
                       <div class="alert-danger text-center p-3 mb-2 category_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage categories</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/add-product.php') ?>" data-toggle="modal" data-target="#modal_add_category" class="btn-fill">Add category</a></li>
                            </ol>
                        </nav>
                        <div class="text">
                            Total Categories: <?= count($shop_categories->result())?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="top-table-container">
                            <div class="icon-container"><i class="fa fa-cubes"></i></div>
                            <form action="" method="GET" class="form-search-input">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" value="" placeholder="Search...">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Category image</th>
                                    <th scope="col">Category name</th>
                                    <th scope="col">Category header</th>
                                    <th scope="col">Feature</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($shop_categories->result()): 
                                foreach($shop_categories->result() as $category):    
                                ?>
                                    <tr>
                                        <td>
                                            <img src="<?= asset($category->category_image) ?>" alt="" class="table-img">
                                        </td>
                                        <td><?= $category->category_name ?></td>
                                        <td><?= $category->category_header ?></td>
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" data-url="<?= url('/admin/ajax.php') ?>" data-id="<?= $category->category_id?>" class="custom-control-input category_feature_btn" id="customSwitch_<?= $category->category_id?>" <?= $category->is_category_feature ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $category->category_id?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($category->category_date_added)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin/edit-category.php?eid='.$category->category_id) ?>" title="Edit category"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal" data-target="#modal_category_delete" class="delete_category_btn" id="<?= $category->category_id ?>" title="Delete"><i class="fa fa-trash"></i></a>
                                           <span class="expand"></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                              
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $shop_categories->links(); ?>

                                <?php if(!count($shop_categories->result())): ?>
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





<!-- Modal -->
<div class="sign_up_modal modal fade" id="modal_category_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_delete_category_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete this category?</p>
                                <input type="hidden" id="category_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="confirm_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="sign_up_modal modal fade" id="modal_add_category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_add_category_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <h3 class="text-center" style="color: #555;">Add new category</h3>
                                <p class="text-center">Create new category</p>
                                <div class="alert-category alert-danger alert_x p-3 text-center" style="display: none;"> </div>
                            </div>
                            <div class="row">
                                  <div class="col-lg-12">
                                      <div class="form-group">
                                          <div class="alert-category alert_0 text-danger"></div>
                                          <input type="text" id="category_name_input" class="form-control" value="" placeholder="Name">
                                      </div>
                                  </div>
                                  <div class="col-lg-12">
                                      <div class="form-group">
                                          <div class="alert-category alert_1 text-danger"></div>
                                          <input type="text" id="category_title_input" class="form-control" value="" placeholder="Title">
                                      </div>
                                  </div>
                                  <div class="col-lg-12">
                                      <div class="form-group">
                                            <div class="alert-category alert_2 text-danger"></div>
                                            <input type="file" id="category_file_input" class="form-control" value="" hidden>
                                            <button type="button" id="category_file_btn" class="category_image_btn">Upload image...</button>
                                      </div>
                                  </div>
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="add_category_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<a href="<?= url('/admin/ajax.php') ?>" class="ajax_url_link" hidden></a>




<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){


// ======================================
// OPEN FILE FIELD
// =====================================
$("#category_file_btn").click(function(e){
    $("#category_file_input").click();
});





// ==========================================
// CATEGORY FEATURE BUTTON
// ==========================================
$('.category_feature_btn').click(function(e){
    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			category_id: id,
			is_category_feature: 'is_category_feature'
		},
		success: function(response){
			console.log(response)
            remove_preloader();
		}
	});
});






// ==========================================
// ADD NEW CATEGORY
// ==========================================
$("#add_category_btn").click(function(e){
    e.preventDefault();
    $('.alert_x').hide();
    $(".alert-category").html('');
    var url = $(".ajax_url_link").attr('href');
    var category_name = $("#category_name_input").val();
    var category_title = $("#category_title_input").val();
    var image = $("#category_file_input");

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('category_name', category_name);
    data.append('category_title', category_title);
    data.append('add_new_category', true);
    
    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var info = JSON.parse(response);
           if(info.error){
                get_error(info.error);
           }else if(info.data){
               location.reload();
           }else{
                $("#modal_add_category_close").click();
               $('.alert_x').show();
               $(".alert_x").html('*something went wrong!');
           }
        console.log(response)
        }
    });
});


function get_error(error){
    $(".alert_0").html(error.category_name);
    $(".alert_1").html(error.category_title);
    $(".alert_2").html(error.image);
}


// ==========================================
// DELETE CATEORY
// ==========================================
$(".delete_category_btn").click(function(e){
    e.preventDefault();
    $(".category_alert_danger").hide();
    var id = $(this).attr('id');
    $("#category_delete_id").val(id);
});

$("#confirm_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#category_delete_id").val();
     var url = $(this).attr('data-url');

    $.ajax({
		url: url,
		method: 'post',
		data: {
			category_id: id,
			delete_category: 'delete_category'
		},
		success: function(response){
            var info = JSON.parse(response);
            if(info.data){
                $("#modal_delete_category_close").click();
                location.reload();
            }else{
                $("#modal_delete_category_close").click();
               $(".category_alert_danger").show();
               $(".category_alert_danger").html('Could not delete this category, try again later!');
            }
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





// end
});
</script>