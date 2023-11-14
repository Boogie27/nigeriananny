<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}


$products = $connection->select('shop_products');


if($search = Input::get('search'))
{
    $products->where('product_name', 'RLIKE', $search);
}

$products->paginate(50);

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
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage products</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/add-product.php') ?>" class="btn-fill">Add product</a></li>
                            </ol>
                        </nav>
                        <div class="text">
                            Total Products: <?= count($products->result())?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="top-table-container">
                            <div class="icon-container"><i class="fa fa-shopping-cart"></i></div>
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
                                    <th scope="col">Product</th>
                                    <th scope="col">product name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Sold</th>
                                    <th scope="col">Feature</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($products->result()): 
                                foreach($products->result() as $product):    
                                ?>
                                    <tr>
                                        <td>
                                            <a href="<?= url('/admin/product-detail.php?pid='.$product->id) ?>">
                                                <img src="<?= asset(explode(',', $product->big_image)[0]) ?>" alt="" class="table-img">
                                            </a>
                                        </td>
                                        <td><?= ucfirst($product->product_name) ?></td>
                                        <td><?= $product->product_quantity ?></td>
                                        <td><?= money($product->product_price) ?></td>
                                        <td><?= $product->product_quantity_sold ?></td>
                                        <td>
                                            <div class="ui_kit_whitchbox">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" data-url="<?= url('/admin/ajax.php') ?>" data-id="<?= $product->id ?>" class="custom-control-input product_feature_btn" id="customSwitch<?= $product->id ?>" <?= $product->product_is_featured ? 'checked' : '';?>>
                                                    <label class="custom-control-label" for="customSwitch<?= $product->id ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($product->product_date_added)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin/edit-product.php?eid='.$product->id) ?>" title="Edit product"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal" data-target="#exampleModal_product_delete" class="delete_product_btn" id="<?= $product->id ?>" title="Delete"><i class="fa fa-trash"></i></a>
                                           <span class="expand"></span>
                                           <a href="<?= url('/admin/product-detail.php?pid='.$product->id) ?>" title="details"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
            
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $products->links(); ?>

                                <?php if(!count($products->result())): ?>
                                    <div class="empty-table">There are no products yet!</div>
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
<div class="sign_up_modal modal fade" id="exampleModal_product_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_delete_product_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete this product?</p>
                                <input type="hidden" id="product_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="change_password_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){
// ==========================================
// FEATURE BUTTON
// ==========================================
$('.product_feature_btn').click(function(e){
    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');
    $(".preloader-container").show() //show preloader

    $.ajax({
		url: url,
		method: 'post',
		data: {
			product_id: id,
			is_product_feature: 'is_product_feature'
		},
		success: function(response){
			console.log(response)
            remove_preloader();
		}
	});
});



// ==========================================
// DELETE PRODUCT
// ==========================================
$(".delete_product_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    $("#product_delete_id").val(id);
});

$("#change_password_btn").click(function(e){
    e.preventDefault();
     var id = $("#product_delete_id").val();
     var url = $(this).attr('data-url');
     $("#modal_delete_product_close").click();

    $.ajax({
		url: url,
		method: 'post',
		data: {
			product_id: id,
			delete_product: 'delete_product'
		},
		success: function(response){
            var info = JSON.parse(response);
            if(info.data){
                location.reload();
                $("#modal_delete_product_close").click();
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