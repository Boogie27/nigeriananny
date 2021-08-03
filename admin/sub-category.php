<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}

    $connection = new DB();
    $sub_categories = $connection->select('shop_subcategories')->leftJoin('shop_categories', 'shop_subcategories.shop_categories_id', '=', 'shop_categories.category_id');


    if($search = Input::get('search'))
    {
        $sub_categories->where('shop_subCategory_name', 'RLIKE', $search);
    }



    $sub_categories->paginate(50);



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
                            <h4 class="title float-left">Manage subcategories</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/add-product.php') ?>" data-toggle="modal" data-target="#modal_add_category" class="btn-fill">Add subcategory</a></li>
                            </ol>
                        </nav>
                        <div class="text">
                            Total Subcategories: <?= count($sub_categories->result())?>
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
                                    <th scope="col">Subcategory name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Feature</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($sub_categories->result()): 
                                foreach($sub_categories->result() as $subCategory):    
                                ?>
                                    <tr>
                                        <td><?= ucfirst($subCategory->shop_subCategory_name) ?></td>
                                        <td><?= ucfirst($subCategory->category_name) ?></td>
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" data-url="<?= url('/admin/ajax.php') ?>" data-id="<?= $subCategory->shop_subCategory_id ?>" class="custom-control-input subcategory_feature_btn" id="customSwitch_<?= $subCategory->shop_subCategory_id ?>" <?= $subCategory->shop_subCategory_isFeature ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch_<?= $subCategory->shop_subCategory_id ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($subCategory->subcategory_date_added)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin/edit-subcategory.php?scid='.$subCategory->shop_subCategory_id ) ?>" title="Edit product"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal" data-target="#modal_subCategory_delete" class="delete_subCategory_btn" id="<?= $subCategory->shop_subCategory_id  ?>" title="Delete"><i class="fa fa-trash"></i></a>
                                           <span class="expand"></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $sub_categories->links(); ?>

                                <?php if(!count($sub_categories->result())): ?>
                                <div class="empty-table">There are no subcategories yet!</div>
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
<div class="sign_up_modal modal fade" id="modal_subCategory_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="delete_modal_subcategroy_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete this subcategory?</p>
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









<?php
$categories = $connection->select('shop_categories')->get();
?>
<!-- Modal -->
<div class="sign_up_modal modal fade" id="modal_add_category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_subcategory_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <h3 class="text-center" style="color: #555;">Add new subcategory</h3>
                                <p class="text-center">Create new subcategory</p>
                                <div class="alert-category alert_0 alert-danger p-3 text-center" style="display: none;"> </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="ui_kit_select_box">
                                        <div class="alert-category alert_1 text-danger"></div>
                                        <label for="">Category</label>
                                        <select id="category_name_input" class="selectpicker custom-select-lg mb-3">
                                            <?php if($categories): 
                                                foreach($categories as $category): ?>
                                                <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                            <option value="local pickup">Category empty</option>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                </div>
                                  <div class="col-lg-12">
                                      <div class="form-group">
                                          <div class="alert-category alert_2 text-danger"></div>
                                          <input type="text" id="subcategory_input" class="form-control" value="" placeholder="Subcategory">
                                      </div>
                                  </div>
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="add_subcategory_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Submit</button>
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


// ==========================================
// SUBCATEGORY FEATURE BUTTON
// ==========================================
$('.subcategory_feature_btn').click(function(e){
    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');

    $.ajax({
		url: url,
		method: 'post',
		data: {
			subCategory_id: id,
			is_subCategory_feature: 'is_subCategory_feature'
		},
		success: function(response){
			console.log(response)
		}
	});
});






// ==========================================
// ADD NEW CATEGORY
// ==========================================
$("#add_subcategory_btn").click(function(e){
    e.preventDefault();
    $('.alert_0').hide();
    $(".alert-category").html('');
    var url = $(".ajax_url_link").attr('href');
    var category_name = $("#category_name_input").val();
    var subcategory = $("#subcategory_input").val();
    
    $.ajax({
        url: url,
        method: "post",
        data: {
            category_name: category_name,
            subcategory: subcategory,
            add_subcategory: 'subcategory'
        },
        success: function (response){
           var info = JSON.parse(response);
           if(info.error){
                get_error(info.error);
           }else if(info.data){ 
               location.reload();
           }else{
            $('.alert_0').show();
            $('.alert_0').html('Subcategory could not be created, try again later!');
           }
        // console.log(info)
        }
    });
});


function get_error(error){
    $(".alert_1").html(error.category_id);
    $(".alert_2").html(error.subcategory);
}









// ==========================================
// DELETE SUBCATEORY
// ==========================================
$(".delete_subCategory_btn").click(function(e){
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
			subCategory_id: id,
			delete_subCategory: 'delete_subCategory'
		},
		success: function(response){
            var info = JSON.parse(response);
            if(info.data){
                location.reload();
            }else{
                $("#delete_modal_subcategroy_close").click();
               $(".category_alert_danger").show();
               $(".category_alert_danger").html('Could not delete this subcategory, try again later!');
            }
		}
	});
});















// end
});
</script>